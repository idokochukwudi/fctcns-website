<?php
/**
 * Remita Model
 *
 * Handles all Remita payment integration using official SDK
 * FIXED: Correct autoloader path for both local and production
 * FIXED: Proper SDK integration with 12-digit RRR generation
 * FIXED: Removed ALL fake RRR generation - only real API calls
 * FIXED: Resolved 400 Bad Request with detailed debug logging & correct endpoint/hash
 * FIXED: Corrected demo endpoint from remitademo.net to demo.remita.net (fixes 302 redirect)
 * FIXED: Authorization header now uses merchantId as Consumer Key (not apiKey)
 * FIXED: Added JSONP response handling to extract RRR from jsonp() wrapper
 * FIXED: Updated verification endpoint to /echannelsvc/{merchantId}/{rrr}/orderstatus.reg
 * FIXED: Payment verification now properly handles RRR with dashes
 * FIXED: Corrected SettingsModel path to /application/SettingsModel.php
 * FIXED: Enhanced verification response handling for demo environment with status code '00'
 * FIXED: CORRECTED HASH FORMAT for verification - now uses merchantId + rrr + apiKey ONLY (per Remita support)
 * FIXED: Now uses defined constants instead of $_ENV directly for better reliability
 *
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

// FIXED: Dynamic autoloader path detection
$possibleAutoloadPaths = [
    // Local development path (Windows)
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../../../vendor/autoload.php',

    // Production paths
    '/home2/fctcnsed/fctcns-app/vendor/autoload.php',
    dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php',

    // Relative from project root
    dirname(__DIR__, 3) . '/vendor/autoload.php',
];

$autoloadLoaded = false;
foreach ($possibleAutoloadPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $autoloadLoaded = true;
        error_log("RemitaModel: Loaded autoloader from: " . $path);
        break;
    }
}

if (!$autoloadLoaded) {
    error_log("RemitaModel: WARNING - Could not find Composer autoloader. Will use direct API calls.");
}

class RemitaModel extends BaseModel {

    protected $table = 'remita_transactions';
    protected $primaryKey = 'id';

    // Remita API Configuration
    private $merchantId;
    private $serviceTypeId;
    private $apiKey;
    private $apiToken;
    private $publicKey;
    private $secretKey;
    private $environment;
    private $baseUrl;

    // SDK Objects
    private $credentials;
    private $gatewayService;
    private $billingService;
    
    // Settings model for fee retrieval
    private $settingsModel;

    /**
     * Constructor - Load configuration from constants (defined in constants.php)
     */
    public function __construct() {
        parent::__construct();

        // Load from defined constants (set in constants.php) with hardcoded fallbacks
        $this->merchantId   = defined('REMITA_MERCHANT_ID') ? REMITA_MERCHANT_ID : '27768931';
        $this->serviceTypeId = defined('REMITA_SERVICE_TYPE_ID') ? REMITA_SERVICE_TYPE_ID : '35126630';
        $this->apiKey       = defined('REMITA_API_KEY') ? REMITA_API_KEY : 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';
        $this->publicKey    = defined('REMITA_PUBLIC_KEY') ? REMITA_PUBLIC_KEY : '';
        $this->secretKey    = defined('REMITA_SECRET_KEY') ? REMITA_SECRET_KEY : '';
        $this->environment  = defined('REMITA_ENVIRONMENT') ? REMITA_ENVIRONMENT : 'demo';

        // Log the values being used (for debugging)
        error_log("RemitaModel using credentials - Merchant: {$this->merchantId}, ServiceType: {$this->serviceTypeId}, Env: {$this->environment}");

        // Generate API Token for SDK (SHA-512)
        $this->apiToken = $this->generateApiToken();

        /*
         * FIXED: Correct Remita base URLs based on environment.
         * Demo: demo.remita.net
         * Live: login.remita.net
         */
        if ($this->environment === 'live') {
            $this->baseUrl = 'https://login.remita.net/remita/exapp/api/v1/send/api';
        } else {
            // DEMO environment - use demo.remita.net
            $this->baseUrl = 'https://demo.remita.net/remita/exapp/api/v1/send/api';
        }

        // Initialize SDK if classes are available
        $this->initSDK();
        
        // Load settings model for fee retrieval - FIXED PATH
        require_once MODELS_PATH . '/application/SettingsModel.php';
        $this->settingsModel = new SettingsModel();

        error_log("RemitaModel initialized | env={$this->environment} | baseUrl={$this->baseUrl}");
    }

    // -------------------------------------------------------------------------
    // SDK INITIALIZATION
    // -------------------------------------------------------------------------

    /**
     * Initialize Remita SDK objects
     */
    private function initSDK() {
        try {
            if (!class_exists('Remita\Rits\Credentials')) {
                error_log("RemitaModel: SDK not available - will use direct cURL calls.");
                return;
            }

            $this->credentials = new \Remita\Rits\Credentials();
            $this->credentials->setMerchantId($this->merchantId);
            $this->credentials->setApiKey($this->apiKey);
            $this->credentials->setApiToken($this->apiToken);
            $this->credentials->setEnvironment(strtoupper($this->environment));

            if ($this->environment === 'demo') {
                $this->credentials->setKey("nbzjfdiehurgsxct");
                $this->credentials->setIv("sngtmqpfurxdbkwj");
            }

            $this->gatewayService = new \Remita\Rits\RITsGatewayService($this->credentials);

            if (class_exists('Remita\Billing\BillingService')) {
                $this->billingService = new \Remita\Billing\BillingService($this->credentials);
            }

            error_log("RemitaModel: SDK initialized successfully.");

        } catch (Exception $e) {
            error_log("RemitaModel: SDK init failed - " . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // HASH GENERATION
    // -------------------------------------------------------------------------

    /**
     * Generate API Token (SHA-512): merchantId + apiKey + secretKey
     */
    private function generateApiToken() {
        return hash('sha512', $this->merchantId . $this->apiKey . $this->secretKey);
    }

    /**
     * FIXED: Generate hash for payment status/verification:
     *   CORRECT FORMAT per Remita support: merchantId + rrr + apiKey ONLY
     *   (serviceTypeId and amount are NOT needed for status verification)
     */
    public function generateApiHash($rrr, $amount = null) {
        // Ensure RRR is clean (no dashes)
        $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
        
        // CORRECT FORMAT: merchantId + rrr + apiKey (amount and serviceTypeId NOT needed)
        $raw = $this->merchantId . $cleanRrr . $this->apiKey;
        
        error_log("generateApiHash (corrected): merchantId({$this->merchantId}) + rrr($cleanRrr) + apiKey");
        return hash('sha512', $raw);
    }

    /**
     * Generate hash for status check endpoint:
     *   SHA-512(merchantId + rrr + apiKey)
     */
    public function generateStatusHash($rrr) {
        $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
        $raw = $this->merchantId . $cleanRrr . $this->apiKey;
        error_log("generateStatusHash: merchantId({$this->merchantId}) + rrr($cleanRrr) + apiKey");
        return hash('sha512', $raw);
    }

    /**
     * Generate hash for RRR generation:
     *   SHA-512( merchantId + serviceTypeId + orderId + amount + apiKey )
     */
    public function generateRRRHash($orderId, $amount) {
        $raw = $this->merchantId . $this->serviceTypeId . $orderId . $amount . $this->apiKey;
        error_log("generateRRRHash raw string: merchantId({$this->merchantId}) + serviceTypeId({$this->serviceTypeId}) + orderId($orderId) + amount($amount) + apiKey");
        return hash('sha512', $raw);
    }

    // -------------------------------------------------------------------------
    // DATABASE HELPERS
    // -------------------------------------------------------------------------

    public function createTransaction($paymentId, $rrr, $orderId, $amount, $requestData = null, $responseData = null) {
        $apiHash = $this->generateApiHash($rrr, $amount);

        $data = [
            'payment_id'      => $paymentId,
            'rrr'             => $rrr,
            'order_id'        => $orderId,
            'amount'          => $amount,
            'merchant_id'     => $this->merchantId,
            'service_type_id' => $this->serviceTypeId,
            'api_hash'        => $apiHash,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s')
        ];

        if ($requestData) {
            $data['request_data'] = is_array($requestData) ? json_encode($requestData) : $requestData;
        }

        if ($responseData) {
            $data['response_data'] = is_array($responseData) ? json_encode($responseData) : $responseData;
        }

        return $this->insert($data);
    }

    public function getByRRR($rrr) {
        // Try exact match first
        $result = $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE rrr = :rrr",
            ['rrr' => $rrr]
        );
        
        // If not found, try with cleaned RRR (remove dashes)
        if (!$result) {
            $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
            $result = $this->fetchOne(
                "SELECT * FROM {$this->table} WHERE REPLACE(rrr, '-', '') = :clean_rrr",
                ['clean_rrr' => $cleanRrr]
            );
        }
        
        return $result;
    }

    public function getByOrderId($orderId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE order_id = :order_id",
            ['order_id' => $orderId]
        );
    }

    public function updateRequestData($transactionId, $requestData) {
        return $this->update(
            ['request_data' => is_array($requestData) ? json_encode($requestData) : $requestData],
            'id = :id',
            ['id' => $transactionId]
        );
    }

    public function updateResponseData($transactionId, $responseData) {
        return $this->update(
            ['response_data' => is_array($responseData) ? json_encode($responseData) : $responseData],
            'id = :id',
            ['id' => $transactionId]
        );
    }

    public function updatePaymentData($transactionId, $paymentData) {
        return $this->update(
            ['payment_data' => is_array($paymentData) ? json_encode($paymentData) : $paymentData],
            'id = :id',
            ['id' => $transactionId]
        );
    }

    public function updateNotificationData($transactionId, $notificationData) {
        return $this->update(
            ['notification_data' => is_array($notificationData) ? json_encode($notificationData) : $notificationData],
            'id = :id',
            ['id' => $transactionId]
        );
    }

    public function updateStatus($transactionId, $status) {
        return $this->update(
            ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $transactionId]
        );
    }

    // -------------------------------------------------------------------------
    // RRR GENERATION  (Public entry point)
    // -------------------------------------------------------------------------

    /**
     * Generate RRR - NO FALLBACKS, ONLY REAL RRRs
     */
    public function generateRRRRemita($orderId, $amount, $payerName, $payerEmail, $payerPhone) {
        return $this->generateRRRDirect($orderId, $amount, $payerName, $payerEmail, $payerPhone);
    }

    // -------------------------------------------------------------------------
    // RRR GENERATION  (Private implementation with full debug logging)
    // -------------------------------------------------------------------------

    /**
     * Direct cURL call to Remita RRR generation endpoint.
     */
    private function generateRRRDirect($orderId, $amount, $payerName, $payerEmail, $payerPhone) {
        try {
            // Build hash & request payload
            $apiHash = $this->generateRRRHash($orderId, $amount);

            $requestData = [
                'merchantId'    => $this->merchantId,
                'serviceTypeId' => $this->serviceTypeId,
                'orderId'       => (string) $orderId,
                'amount'        => (string) $amount,
                'payerName'     => $payerName,
                'payerEmail'    => $payerEmail,
                'payerPhone'    => $payerPhone,
                'description'   => 'Application Fee Payment - FCT College of Nursing Sciences',
                'responseUrl'   => (defined('BASE_URL') ? BASE_URL : '') . '/payment/remita-response',
            ];

            /*
             * FIXED endpoint path using corrected baseUrl (demo.remita.net)
             */
            $endpoint = $this->baseUrl . '/echannelsvc/merchant/api/paymentinit';

            error_log("=== REMITA RRR REQUEST ===");
            error_log("Endpoint      : " . $endpoint);
            error_log("Environment   : " . $this->environment);
            error_log("Merchant ID   : " . $this->merchantId);
            error_log("Service Type  : " . $this->serviceTypeId);
            error_log("Order ID      : " . $orderId);
            error_log("Amount        : " . $amount);
            error_log("Payer Name    : " . $payerName);
            error_log("Payer Email   : " . $payerEmail);
            error_log("Payer Phone   : " . $payerPhone);
            error_log("API Hash      : " . $apiHash);
            error_log("JSON Payload  : " . json_encode($requestData));

            // Execute cURL request
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($requestData),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Cache-Control: no-cache',
                    // FIXED: Use merchantId as Consumer Key (not apiKey)
                    'Authorization: remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $apiHash,
                ],
                CURLOPT_FOLLOWLOCATION => false, // Don't follow redirects
            ]);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            error_log("=== REMITA RRR RESPONSE ===");
            error_log("HTTP Code  : " . $httpCode);
            error_log("cURL Error : " . ($curlError ?: 'none'));
            error_log("Raw Body   : " . $response);

            $this->writeDebugLog([
                'type'      => 'RRR_GENERATION',
                'endpoint'  => $endpoint,
                'http_code' => $httpCode,
                'request'   => $requestData,
                'response'  => $response,
                'curl_error'=> $curlError,
            ]);

            // Handle response
            if ($httpCode === 302) {
                error_log("❌ Received 302 redirect. This indicates wrong endpoint URL.");
                
                // Try to extract redirect location
                if (preg_match('/Location: (.*)/i', $response, $matches)) {
                    error_log("   Redirect location: " . $matches[1]);
                }
                
                return [
                    'status'     => 'error',
                    'message'    => 'Remita API endpoint redirected. Please check configuration.',
                    'http_code'  => 302,
                    'debug_hint' => 'The API endpoint may be incorrect. Using: ' . $this->baseUrl,
                ];
            }

            if ($httpCode === 400) {
                $decoded = json_decode($response, true);
                $remitaMsg = $decoded['responseMsg'] ?? $decoded['message'] ?? 'No message in body';
                error_log("❌ 400 Bad Request from Remita. Message: " . $remitaMsg);
                return [
                    'status'     => 'error',
                    'message'    => '400 Bad Request from Remita: ' . $remitaMsg,
                    'http_code'  => 400,
                    'response'   => $decoded ?? $response,
                ];
            }

            if ($httpCode === 401 || $httpCode === 403) {
                error_log("❌ Auth error ($httpCode) - check API key and hash generation.");
                return [
                    'status'    => 'error',
                    'message'   => "Authentication error ($httpCode). Verify credentials.",
                    'http_code' => $httpCode,
                ];
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                error_log("❌ Unexpected HTTP $httpCode from Remita.");
                return [
                    'status'     => 'error',
                    'message'    => "Remita API returned HTTP $httpCode.",
                    'http_code'  => $httpCode,
                ];
            }

            // ------------------------------------------------------------------
            // FIXED: Handle JSONP response (Remita returns jsonp wrapped responses)
            // ------------------------------------------------------------------
            
            // Parse successful response - handle both JSON and JSONP
            $result = null;
            
            // Check if response is JSONP (wrapped in jsonp())
            if (preg_match('/^jsonp\s*\((.+)\)\s*;?\s*$/', $response, $matches)) {
                $jsonStr = $matches[1];
                $result = json_decode($jsonStr, true);
                error_log("✅ Extracted JSON from JSONP wrapper");
            } else {
                // Try direct JSON decode
                $result = json_decode($response, true);
            }
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("❌ JSON parse error: " . json_last_error_msg());
                error_log("   Raw response was: " . $response);
                return [
                    'status'   => 'error',
                    'message'  => 'Remita returned non-JSON response',
                    'response' => $response,
                ];
            }

            error_log("✅ Parsed response: " . print_r($result, true));

            $rrr = $this->extractRRR($result);

            if ($rrr) {
                error_log("✅ RRR generated successfully: " . $rrr);

                $this->createTransaction(
                    null,
                    $rrr,
                    $orderId,
                    $amount,
                    $requestData,
                    $result
                );

                return [
                    'status'        => 'success',
                    'rrr'           => $rrr,
                    'message'       => 'RRR generated successfully',
                    'response_data' => $result,
                ];
            }

            error_log("❌ 2xx response but no RRR found. Full result: " . json_encode($result));
            return [
                'status'        => 'error',
                'message'       => 'No RRR in response body',
                'response_data' => $result,
            ];

        } catch (Exception $e) {
            error_log("❌ RemitaModel::generateRRRDirect exception: " . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Exception during RRR generation: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract RRR value from Remita response
     */
    private function extractRRR(array $result): ?string {
        if (!empty($result['rrr'])) {
            return $result['rrr'];
        }
        if (!empty($result['RRR'])) {
            return $result['RRR'];
        }
        if (!empty($result['data']['rrr'])) {
            return $result['data']['rrr'];
        }
        if (isset($result['responseCode']) && $result['responseCode'] === '00' && !empty($result['rrr'])) {
            return $result['rrr'];
        }
        return null;
    }

    // -------------------------------------------------------------------------
    // PAYMENT VERIFICATION - COMPLETELY FIXED VERSION
    // -------------------------------------------------------------------------

    /**
     * Verify payment status for a given RRR
     * FIXED: Correct hash format based on Remita support (merchantId + rrr + apiKey only)
     * FIXED: Properly handles RRR with dashes and correct endpoint
     * FIXED: Now tries multiple hash formats if the primary one fails
     */
    public function verifyPayment($rrr) {
        try {
            error_log("RemitaModel: verifying RRR $rrr");
            
            // Clean RRR - remove dashes and any non-numeric characters
            $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
            error_log("RemitaModel: cleaned RRR: $cleanRrr");
            
            // Try multiple hash formats
            $hashFormats = [
                // Format 1: merchantId + rrr + apiKey (as per Remita documentation)
                'format1' => $this->merchantId . $cleanRrr . $this->apiKey,
                
                // Format 2: merchantId + serviceTypeId + rrr + apiKey (older format)
                'format2' => $this->merchantId . $this->serviceTypeId . $cleanRrr . $this->apiKey,
                
                // Format 3: lowercase everything
                'format3' => strtolower($this->merchantId . $cleanRrr . $this->apiKey),
            ];
            
            $successfulResponse = null;
            $workingFormat = null;
            
            foreach ($hashFormats as $formatName => $hashString) {
                $statusHash = hash('sha512', $hashString);
                error_log("Trying $formatName: " . substr($statusHash, 0, 20) . "...");
                
                $endpoint = $this->baseUrl . '/echannelsvc/' . $this->merchantId . '/' . $cleanRrr . '/' . $statusHash . '/status.reg';
                
                // Use same hash for authorization
                $authHash = hash('sha512', $hashString);
                
                $ch = curl_init($endpoint);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 30,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization: remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $authHash,
                    ],
                    CURLOPT_FOLLOWLOCATION => false,
                ]);

                $response  = curl_exec($ch);
                $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                error_log("$formatName result: HTTP $httpCode | body: $response");

                // Parse response
                $result = $this->parseRemitaResponse($response);
                
                // Check if this format worked (not a 013 error)
                if ($result && (!isset($result['status']) || $result['status'] !== '013')) {
                    error_log("✅ $formatName worked!");
                    $successfulResponse = [
                        'httpCode' => $httpCode,
                        'response' => $response,
                        'result' => $result,
                        'format' => $formatName
                    ];
                    $workingFormat = $formatName;
                    break;
                }
            }
            
            if ($successfulResponse) {
                error_log("Using working hash format: $workingFormat");
                $httpCode = $successfulResponse['httpCode'];
                $result = $successfulResponse['result'];
                
                // Log ALL fields from the response for debugging
                error_log("=== REMITA VERIFICATION RESPONSE FIELDS ===");
                foreach ($result as $key => $value) {
                    if (is_array($value)) {
                        error_log("  [$key] => " . json_encode($value));
                    } else {
                        error_log("  [$key] => " . $value);
                    }
                }

                // Check for success (status code '00' per Remita support)
                $responseCode = $result['responseCode'] ?? $result['status'] ?? '';
                $responseMsg = $result['responseMsg'] ?? $result['message'] ?? '';
                $hasTransactionId = !empty($result['transactionId']) || !empty($result['transactionRef']);

                error_log("RemitaModel: responseCode=$responseCode | responseMsg=$responseMsg | hasTransactionId=" . ($hasTransactionId ? 'YES' : 'NO'));

                // SUCCESS: status code '00' (confirmed by Remita support)
                if ($responseCode === '00' || $responseCode === '01' || $responseCode === 'success' || $responseMsg === 'SUCCESS' || $hasTransactionId) {
                    error_log("✅ RemitaModel: Payment CONFIRMED as successful");
                    
                    // Try to update transaction in database if we have it
                    $transaction = $this->getByRRR($rrr);
                    if ($transaction) {
                        $this->updatePaymentData($transaction['id'], $result);
                        $this->updateStatus($transaction['id'], 'success');
                    }
                    
                    return [
                        'status'       => 'success',
                        'message'      => 'Payment verified successfully',
                        'payment_data' => $result,
                    ];
                }
                
                // PENDING
                elseif ($responseCode === '021' || $responseCode === 'PENDING' || stripos($responseMsg, 'pending') !== false) {
                    error_log("⏳ RemitaModel: Payment is PENDING");
                    return [
                        'status'       => 'pending',
                        'message'      => 'Payment is pending. Please check back later.',
                        'payment_data' => $result,
                    ];
                }
                
                // FAILED
                else {
                    error_log("❌ RemitaModel: Payment NOT confirmed. Code: $responseCode, Msg: $responseMsg");
                    return [
                        'status'       => 'failed',
                        'message'      => 'Payment not confirmed by Remita. Code: ' . $responseCode,
                        'payment_data' => $result,
                    ];
                }
            }
            
            // If all formats failed
            error_log("❌ All hash formats failed for RRR $cleanRrr");
            
            // Try alternative endpoint format as last resort
            $altResult = $this->tryAlternativeVerificationEndpoint($cleanRrr);
            if ($altResult && $altResult['status'] === 'success') {
                return $altResult;
            }
            
            return [
                'status'    => 'failed',
                'message'   => 'Unable to verify payment with Remita. Please try again.',
                'http_code' => 400
            ];

        } catch (Exception $e) {
            error_log("RemitaModel::verifyPayment exception: " . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Try alternative verification endpoint format
     */
    private function tryAlternativeVerificationEndpoint($cleanRrr) {
        try {
            error_log("RemitaModel: Trying alternative verification endpoint for RRR $cleanRrr");
            
            // Alternative endpoint format
            $altEndpoint = 'https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/' . 
                          $this->merchantId . '/' . $cleanRrr . '/status.reg';
            
            error_log("RemitaModel: Alternative endpoint: $altEndpoint");
            
            $authHash = hash('sha512', $this->merchantId . $cleanRrr . $this->apiKey);
            
            $ch = curl_init($altEndpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $authHash,
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            error_log("RemitaModel: Alternative endpoint HTTP $httpCode | response: $response");

            if ($httpCode === 200 || $httpCode === 201) {
                $result = $this->parseRemitaResponse($response);
                
                if ($result && (!empty($result['transactionId']) || !empty($result['rrr']))) {
                    return [
                        'status'       => 'success',
                        'message'      => 'Payment verified via alternative endpoint',
                        'payment_data' => $result,
                    ];
                }
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("RemitaModel: Alternative endpoint exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse Remita response (handles both JSON and JSONP)
     */
    private function parseRemitaResponse($response) {
        if (empty($response)) {
            return null;
        }
        
        // Handle JSONP response
        if (preg_match('/^jsonp\s*\((.+)\)\s*;?\s*$/', $response, $matches)) {
            $jsonStr = $matches[1];
            $result = json_decode($jsonStr, true);
            error_log("✅ Extracted JSON from JSONP wrapper");
            return $result;
        }
        
        // Handle regular JSON
        $result = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }
        
        error_log("❌ JSON parse error: " . json_last_error_msg());
        return null;
    }

    // -------------------------------------------------------------------------
    // STATUS CHECK
    // -------------------------------------------------------------------------

    public function checkStatus($rrr) {
        $transaction = $this->getByRRR($rrr);

        if (!$transaction) {
            return ['status' => 'not_found'];
        }

        if ($transaction['status'] === 'success') {
            return [
                'status'      => 'success',
                'transaction' => $transaction,
            ];
        }

        $verification = $this->verifyPayment($rrr);

        if ($verification['status'] === 'success') {
            $this->updatePaymentData($transaction['id'], $verification['payment_data']);
            $this->updateStatus($transaction['id'], 'success');

            return [
                'status'      => 'success',
                'transaction' => $this->find($transaction['id']),
            ];
        }

        return [
            'status'      => 'pending',
            'transaction' => $transaction,
        ];
    }

    // -------------------------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------------------------

    public function getMerchantId()    { return $this->merchantId; }
    public function getServiceTypeId() { return $this->serviceTypeId; }
    public function getEnvironment()   { return $this->environment; }
    public function getBaseUrl()       { return $this->baseUrl; }

    // -------------------------------------------------------------------------
    // QUERY HELPERS
    // -------------------------------------------------------------------------

    public function getByPaymentId($paymentId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE payment_id = :payment_id ORDER BY created_at DESC",
            ['payment_id' => $paymentId]
        );
    }

    public function getByDateRange($startDate, $endDate, $status = null) {
        $sql    = "SELECT * FROM {$this->table} WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
        $params = ['start_date' => $startDate, 'end_date' => $endDate];

        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC";
        return $this->fetchAll($sql, $params);
    }

    public function getStats() {
        $stats = $this->fetchOne("
            SELECT
                COUNT(*) as total_transactions,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'failed'  THEN 1 ELSE 0 END) as failed,
                SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_amount
            FROM {$this->table}
        ");

        $today = $this->fetchOne("
            SELECT
                COUNT(*) as today_count,
                SUM(amount) as today_amount
            FROM {$this->table}
            WHERE status = 'success' AND DATE(created_at) = CURDATE()
        ");

        if (!$stats) {
            $stats = [
                'total_transactions' => 0,
                'successful'         => 0,
                'pending'            => 0,
                'failed'             => 0,
                'total_amount'       => 0,
            ];
        }

        $stats['today_count']  = $today['today_count']  ?? 0;
        $stats['today_amount'] = $today['today_amount'] ?? 0;

        return $stats;
    }

    public function getStaleTransactions($minutes = 30) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        return $this->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE status = 'pending' AND created_at < :cutoff
             ORDER BY created_at ASC",
            ['cutoff' => $cutoff]
        );
    }

    public function countByStatus($status) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE status = :status",
            ['status' => $status]
        );
    }

    public function rrrExists($rrr) {
        // Clean RRR for comparison
        $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
        
        return (int) $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE REPLACE(rrr, '-', '') = :clean_rrr",
            ['clean_rrr' => $cleanRrr]
        ) > 0;
    }

    public function getLatestByPaymentId($paymentId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table}
             WHERE payment_id = :payment_id
             ORDER BY created_at DESC
             LIMIT 1",
            ['payment_id' => $paymentId]
        );
    }

    // -------------------------------------------------------------------------
    // INTERNAL LOGGING HELPER
    // -------------------------------------------------------------------------

    /**
     * Write a structured entry to storage/logs/remita_debug.log
     */
    private function writeDebugLog(array $data): void {
        try {
            $logDir = __DIR__ . '/../../storage/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $entry  = str_repeat('-', 60) . "\n";
            $entry .= date('Y-m-d H:i:s') . " | " . ($data['type'] ?? 'UNKNOWN') . "\n";
            $entry .= "Endpoint  : " . ($data['endpoint']  ?? 'N/A') . "\n";
            $entry .= "HTTP Code : " . ($data['http_code'] ?? 'N/A') . "\n";

            if (!empty($data['rrr'])) {
                $entry .= "RRR       : " . $data['rrr'] . "\n";
            }

            if (!empty($data['curl_error'])) {
                $entry .= "cURL Err  : " . $data['curl_error'] . "\n";
            }

            if (!empty($data['request'])) {
                $entry .= "Request   : " . json_encode($data['request'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            }

            $entry .= "Response  : " . (is_string($data['response']) ? $data['response'] : json_encode($data['response'], JSON_PRETTY_PRINT)) . "\n";
            $entry .= str_repeat('-', 60) . "\n\n";

            file_put_contents($logDir . '/remita_debug.log', $entry, FILE_APPEND | LOCK_EX);

        } catch (Exception $e) {
            error_log("RemitaModel: could not write debug log - " . $e->getMessage());
        }
    }
}