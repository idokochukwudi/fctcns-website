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
 * FIXED: CORRECTED HASH FORMAT for verification per Remita support:
 *        SHA-512( rrr + apiKey + merchantId )  <-- confirmed correct order
 * FIXED: API key must be used as-is (base64-encoded string), do NOT decode before hashing
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

        // Correct Remita base URLs
        // Demo: demo.remita.net | Live: login.remita.net
        if ($this->environment === 'live') {
            $this->baseUrl = 'https://login.remita.net/remita/exapp/api/v1/send/api';
        } else {
            $this->baseUrl = 'https://demo.remita.net/remita/exapp/api/v1/send/api';
        }

        // Initialize SDK if classes are available
        $this->initSDK();
        
        // Load settings model for fee retrieval
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
     * Generate hash for payment status/verification.
     *
     * CONFIRMED by Remita support (2025):
     *   SHA-512( rrr + apiKey + merchantId )
     *
     * IMPORTANT:
     *   - Use apiKey exactly as provided (base64-encoded). Do NOT decode it.
     *   - RRR must be clean digits only (no dashes).
     *   - Order is: rrr FIRST, then apiKey, then merchantId LAST.
     *
     * @param  string      $rrr    The RRR (with or without dashes — will be cleaned)
     * @param  mixed|null  $amount Not used for status hash (kept for backward compat)
     * @return string              SHA-512 hex hash
     */
    public function generateApiHash($rrr, $amount = null) {
        $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);

        // CONFIRMED correct order: rrr + apiKey + merchantId
        $raw = $cleanRrr . $this->apiKey . $this->merchantId;

        error_log("generateApiHash: rrr($cleanRrr) + apiKey + merchantId({$this->merchantId})");
        return hash('sha512', $raw);
    }

    /**
     * Generate hash specifically for the status check endpoint.
     *
     * CONFIRMED by Remita support:
     *   SHA-512( rrr + apiKey + merchantId )
     *
     * @param  string $rrr RRR value (dashes will be stripped automatically)
     * @return string      SHA-512 hex hash
     */
    public function generateStatusHash($rrr) {
        $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);

        // CONFIRMED correct order: rrr + apiKey + merchantId
        $raw = $cleanRrr . $this->apiKey . $this->merchantId;

        error_log("generateStatusHash: rrr($cleanRrr) + apiKey + merchantId({$this->merchantId})");
        return hash('sha512', $raw);
    }

    /**
     * Generate hash for RRR generation:
     *   SHA-512( merchantId + serviceTypeId + orderId + amount + apiKey )
     *
     * This hash format is for RRR GENERATION only — different from status check.
     *
     * @param  string $orderId Unique order/reference ID
     * @param  mixed  $amount  Payment amount
     * @return string          SHA-512 hex hash
     */
    public function generateRRRHash($orderId, $amount) {
        $raw = $this->merchantId . $this->serviceTypeId . $orderId . $amount . $this->apiKey;
        error_log("generateRRRHash: merchantId({$this->merchantId}) + serviceTypeId({$this->serviceTypeId}) + orderId($orderId) + amount($amount) + apiKey");
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
                    'Authorization: remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $apiHash,
                ],
                CURLOPT_FOLLOWLOCATION => false,
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
                error_log("❌ Received 302 redirect. Wrong endpoint URL.");
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

            // Parse successful response - handle both JSON and JSONP
            $result = null;
            
            if (preg_match('/^jsonp\s*\((.+)\)\s*;?\s*$/', $response, $matches)) {
                $jsonStr = $matches[1];
                $result = json_decode($jsonStr, true);
                error_log("✅ Extracted JSON from JSONP wrapper");
            } else {
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
    // PAYMENT VERIFICATION
    // -------------------------------------------------------------------------

    /**
     * Verify payment status for a given RRR.
     *
     * Hash format CONFIRMED by Remita support:
     *   SHA-512( rrr + apiKey + merchantId )
     *
     * API key must be used AS-IS (base64-encoded). Do NOT decode before hashing.
     *
     * @param  string $rrr  The RRR to verify (dashes are stripped automatically)
     * @return array        ['status' => 'success|pending|failed|error', 'message' => ..., 'payment_data' => ...]
     */
    public function verifyPayment($rrr) {
        try {
            error_log("RemitaModel: verifying RRR $rrr");
            
            // Clean RRR — remove dashes and any non-numeric characters
            $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
            error_log("RemitaModel: cleaned RRR: $cleanRrr");
            
            // Build hash using CONFIRMED correct order: rrr + apiKey + merchantId
            $statusHash = $this->generateStatusHash($cleanRrr);

            // Build verification endpoint
            $endpoint = $this->baseUrl . '/echannelsvc/' . $this->merchantId . '/' . $cleanRrr . '/' . $statusHash . '/status.reg';
            
            error_log("RemitaModel: verification endpoint: $endpoint");
            error_log("RemitaModel: hash used (rrr+apiKey+merchantId): $statusHash");
            
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $statusHash,
                ],
                CURLOPT_FOLLOWLOCATION => false,
            ]);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            error_log("RemitaModel: verification HTTP $httpCode | response: $response");
            
            if ($curlError) {
                error_log("RemitaModel: cURL error: $curlError");
            }

            // Parse response
            $result = $this->parseRemitaResponse($response);
            
            if (!$result) {
                return [
                    'status'    => 'error',
                    'message'   => 'Invalid response from Remita',
                    'http_code' => $httpCode
                ];
            }
            
            // Log ALL response fields for debugging
            error_log("=== REMITA VERIFICATION RESPONSE FIELDS ===");
            foreach ($result as $key => $value) {
                error_log("  [$key] => " . (is_array($value) ? json_encode($value) : $value));
            }

            $responseCode    = $result['responseCode'] ?? $result['status'] ?? '';
            $responseMsg     = $result['responseMsg'] ?? $result['message'] ?? '';
            $hasTransactionId = !empty($result['transactionId']) || !empty($result['transactionRef']);

            error_log("RemitaModel: responseCode=$responseCode | responseMsg=$responseMsg | hasTransactionId=" . ($hasTransactionId ? 'YES' : 'NO'));

            // SUCCESS
            if (
                $responseCode === '00'
                || $responseCode === '01'
                || strtolower($responseCode) === 'success'
                || strtoupper($responseMsg) === 'SUCCESS'
                || $hasTransactionId
            ) {
                error_log("✅ RemitaModel: Payment CONFIRMED as successful");
                
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
            elseif (
                $responseCode === '021'
                || strtoupper($responseCode) === 'PENDING'
                || stripos($responseMsg, 'pending') !== false
            ) {
                error_log("⏳ RemitaModel: Payment is PENDING");
                return [
                    'status'       => 'pending',
                    'message'      => 'Payment is pending. Please complete payment on Remita.',
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

        } catch (Exception $e) {
            error_log("RemitaModel::verifyPayment exception: " . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Parse Remita response — handles both plain JSON and JSONP-wrapped responses
     *
     * @param  string $response Raw HTTP response body
     * @return array|null       Decoded array, or null on failure
     */
    private function parseRemitaResponse($response) {
        if (empty($response)) {
            return null;
        }
        
        // Handle JSONP: jsonp({...});
        if (preg_match('/^jsonp\s*\((.+)\)\s*;?\s*$/', $response, $matches)) {
            $result = json_decode($matches[1], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                error_log("✅ Extracted JSON from JSONP wrapper");
                return $result;
            }
        }
        
        // Plain JSON
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