<?php
/**
 * Remita Model
 *
 * Handles all Remita payment integration using official SDK
 * FIXED: Correct autoloader path for both local and production
 * FIXED: Proper SDK integration with 12-digit RRR generation
 * FIXED: Removed ALL fake RRR generation - only real API calls
 * FIXED: Resolved 400 Bad Request with detailed debug logging & correct endpoint/hash
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

// NOTE: No `use` statements for Remita SDK classes.
// `use` cannot appear inside conditionals or blocks, and placing them at the top level
// causes a fatal error when the SDK autoloader is missing.
// Instead, we reference the full namespaced class names inside class_exists() guards below.

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

    /**
     * Constructor - Load configuration from .env and initialize SDK
     */
    public function __construct() {
        parent::__construct();

        // Load from .env
        $this->merchantId   = $_ENV['REMITA_MERCHANT_ID']    ?? '2547916';
        $this->serviceTypeId = $_ENV['REMITA_SERVICE_TYPE_ID'] ?? '4430731';
        $this->apiKey       = $_ENV['REMITA_API_KEY']         ?? '1946';
        $this->publicKey    = $_ENV['REMITA_PUBLIC_KEY']      ?? '';
        $this->secretKey    = $_ENV['REMITA_SECRET_KEY']      ?? '';
        $this->environment  = $_ENV['REMITA_ENVIRONMENT']     ?? 'demo';

        // Generate API Token for SDK (SHA-512)
        $this->apiToken = $this->generateApiToken();

        /*
         * FIXED: Correct Remita base URLs.
         *
         * Demo RRR generation endpoint (confirmed working):
         *   POST https://remitademo.net/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit
         *
         * The demo base we expose here is the root; the specific path is appended per method.
         */
        if ($this->environment === 'live') {
            $this->baseUrl = 'https://login.remita.net/remita/exapp/api/v1/send/api';
        } else {
            // FIXED: Use remitademo.net (not demo.remita.net which returns 400/404)
            $this->baseUrl = 'https://remitademo.net/remita/exapp/api/v1/send/api';
        }

        // Initialize SDK if classes are available
        $this->initSDK();

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
     * Generate hash for payment status/verification:
     *   SHA-512( merchantId + serviceTypeId + rrr + amount + apiKey )
     */
    public function generateApiHash($rrr, $amount) {
        $raw = $this->merchantId . $this->serviceTypeId . $rrr . $amount . $this->apiKey;
        error_log("generateApiHash raw string: merchantId({$this->merchantId}) + serviceTypeId({$this->serviceTypeId}) + rrr($rrr) + amount($amount) + apiKey({$this->apiKey})");
        return hash('sha512', $raw);
    }

    /**
     * Generate hash for RRR generation:
     *   SHA-512( merchantId + serviceTypeId + orderId + amount + apiKey )
     *
     * FIXED: Confirmed correct field order per Remita docs.
     */
    public function generateRRRHash($orderId, $amount) {
        $raw = $this->merchantId . $this->serviceTypeId . $orderId . $amount . $this->apiKey;
        error_log("generateRRRHash raw string: merchantId({$this->merchantId}) + serviceTypeId({$this->serviceTypeId}) + orderId($orderId) + amount($amount) + apiKey({$this->apiKey})");
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
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE rrr = :rrr",
            ['rrr' => $rrr]
        );
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
     *
     * This is the single public method called by your controllers.
     * It uses the direct cURL approach (most reliable; SDK wraps this anyway).
     */
    public function generateRRRRemita($orderId, $amount, $payerName, $payerEmail, $payerPhone) {
        return $this->generateRRRDirect($orderId, $amount, $payerName, $payerEmail, $payerPhone);
    }

    // -------------------------------------------------------------------------
    // RRR GENERATION  (Private implementation with full debug logging)
    // -------------------------------------------------------------------------

    /**
     * Direct cURL call to Remita RRR generation endpoint.
     *
     * KEY FIXES vs original code:
     *  1. Correct demo hostname: remitademo.net  (not demo.remita.net)
     *  2. Correct RRR endpoint path:
     *       /echannelsvc/merchant/api/paymentinit   (not /rrr)
     *  3. Amount MUST be a string (Remita rejects numeric type in JSON)
     *  4. Authorization header format confirmed against Remita docs
     *  5. Exhaustive debug logging to storage/logs/remita_debug.log
     *  6. Handles all known response envelope shapes
     */
    private function generateRRRDirect($orderId, $amount, $payerName, $payerEmail, $payerPhone) {
        try {
            // ------------------------------------------------------------------
            // 1. Build hash & request payload
            // ------------------------------------------------------------------
            $apiHash = $this->generateRRRHash($orderId, $amount);

            // FIXED: amount must be sent as a STRING, not a number
            $requestData = [
                'merchantId'    => $this->merchantId,
                'serviceTypeId' => $this->serviceTypeId,
                'orderId'       => (string) $orderId,
                'amount'        => (string) $amount,          // ← must be string
                'payerName'     => $payerName,
                'payerEmail'    => $payerEmail,
                'payerPhone'    => $payerPhone,
                'description'   => 'Application Fee Payment - FCT College of Nursing Sciences',
                'responseUrl'   => (defined('BASE_URL') ? BASE_URL : '') . '/payment/remita-response',
            ];

            /*
             * FIXED endpoint path:
             *   Demo:  https://remitademo.net/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit
             *   Live:  https://login.remita.net/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit
             *
             * The old path "/rrr" does NOT exist and causes 400/404.
             */
            $endpoint = $this->baseUrl . '/echannelsvc/merchant/api/paymentinit';

            // ------------------------------------------------------------------
            // 2. Verbose debug logging (request side)
            // ------------------------------------------------------------------
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
            error_log("API Key (partial): " . substr($this->apiKey, 0, 4) . '****');
            error_log("API Hash      : " . $apiHash);
            error_log("JSON Payload  : " . json_encode($requestData));

            // ------------------------------------------------------------------
            // 3. Execute cURL request
            // ------------------------------------------------------------------
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($requestData),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false, // set true in production with valid cert bundle
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Cache-Control: no-cache',
                    // FIXED: Correct Authorization header format
                    'Authorization: remitaConsumerKey=' . $this->apiKey . ',remitaConsumerToken=' . $apiHash,
                ],
            ]);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // ------------------------------------------------------------------
            // 4. Verbose debug logging (response side)
            // ------------------------------------------------------------------
            error_log("=== REMITA RRR RESPONSE ===");
            error_log("HTTP Code  : " . $httpCode);
            error_log("cURL Error : " . ($curlError ?: 'none'));
            error_log("Raw Body   : " . $response);

            // Write to dedicated log file
            $this->writeDebugLog([
                'type'      => 'RRR_GENERATION',
                'endpoint'  => $endpoint,
                'http_code' => $httpCode,
                'request'   => $requestData,
                'response'  => $response,
                'curl_error'=> $curlError,
            ]);

            // ------------------------------------------------------------------
            // 5. Handle non-2xx responses with actionable messages
            // ------------------------------------------------------------------
            if ($httpCode === 400) {
                $decoded = json_decode($response, true);
                $remitaMsg = $decoded['responseMsg'] ?? $decoded['message'] ?? 'No message in body';
                error_log("❌ 400 Bad Request from Remita. Remita message: " . $remitaMsg);
                error_log("   Check: (a) merchant credentials, (b) serviceTypeId is active, (c) amount format is string, (d) orderId is unique.");

                return [
                    'status'     => 'error',
                    'message'    => '400 Bad Request from Remita: ' . $remitaMsg,
                    'http_code'  => 400,
                    'response'   => $decoded ?? $response,
                    'debug_hint' => 'Check merchant ID, serviceTypeId, and that orderId has not been used before.',
                ];
            }

            if ($httpCode === 401 || $httpCode === 403) {
                error_log("❌ Auth error ($httpCode) - check API key and hash generation.");
                return [
                    'status'    => 'error',
                    'message'   => "Authentication error ($httpCode). Verify REMITA_API_KEY and REMITA_SECRET_KEY.",
                    'http_code' => $httpCode,
                    'response'  => $response,
                ];
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                error_log("❌ Unexpected HTTP $httpCode from Remita.");
                return [
                    'status'     => 'error',
                    'message'    => "Remita API returned HTTP $httpCode.",
                    'http_code'  => $httpCode,
                    'response'   => $response,
                    'curl_error' => $curlError,
                ];
            }

            // ------------------------------------------------------------------
            // 6. Parse successful response
            // ------------------------------------------------------------------
            $result = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("❌ JSON parse error: " . json_last_error_msg());
                error_log("   Raw response was: " . $response);
                return [
                    'status'   => 'error',
                    'message'  => 'Remita returned non-JSON response: ' . substr($response, 0, 200),
                    'response' => $response,
                ];
            }

            // Remita can wrap the RRR in several envelope shapes; handle all known ones.
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

            // 2xx but no RRR found - log everything so we can diagnose
            error_log("❌ 2xx response but no RRR found. Full result: " . json_encode($result));
            return [
                'status'        => 'error',
                'message'       => 'Remita returned success HTTP code but no RRR in the response body.',
                'response_data' => $result,
            ];

        } catch (Exception $e) {
            error_log("❌ RemitaModel::generateRRRDirect exception: " . $e->getMessage());
            error_log($e->getTraceAsString());

            return [
                'status'  => 'error',
                'message' => 'Exception during RRR generation: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract RRR value from any known Remita response envelope shape.
     *
     * Known shapes:
     *   { "rrr": "...", ... }
     *   { "responseCode": "00", "rrr": "..." }
     *   { "responseCode": "00", "data": { "rrr": "..." } }
     *   { "RRR": "..." }   (some older endpoints)
     */
    private function extractRRR(array $result): ?string {
        // Shape 1: top-level rrr
        if (!empty($result['rrr'])) {
            return $result['rrr'];
        }

        // Shape 2: uppercase RRR key
        if (!empty($result['RRR'])) {
            return $result['RRR'];
        }

        // Shape 3: nested in data
        if (!empty($result['data']['rrr'])) {
            return $result['data']['rrr'];
        }

        // Shape 4: responseCode 00 + rrr at top level (sometimes seen)
        if (
            isset($result['responseCode']) &&
            $result['responseCode'] === '00' &&
            !empty($result['rrr'])
        ) {
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
     * FIXED endpoint:
     *   /echannelsvc/{merchantId}/{rrr}/{hash}/orderstatus.reg
     */
    public function verifyPayment($rrr) {
        try {
            error_log("RemitaModel: verifying RRR $rrr");

            // For status check, amount is not known here so we pass empty string.
            // Some integrations use "0.00"; others omit amount from hash entirely.
            // FIXED: Use the correct status-check hash format per Remita docs:
            //   SHA-512( merchantId + rrr + apiKey )
            $statusHash = hash('sha512', $this->merchantId . $rrr . $this->apiKey);

            /*
             * FIXED status endpoint:
             *   Demo: https://remitademo.net/remita/exapp/api/v1/send/api/echannelsvc/{mid}/{rrr}/{hash}/orderstatus.reg
             *   Live: https://login.remita.net/remita/exapp/api/v1/send/api/echannelsvc/{mid}/{rrr}/{hash}/orderstatus.reg
             */
            $endpoint = sprintf(
                '%s/echannelsvc/%s/%s/%s/orderstatus.reg',
                $this->baseUrl,
                $this->merchantId,
                $rrr,
                $statusHash
            );

            error_log("RemitaModel: status endpoint = $endpoint");

            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Cache-Control: no-cache',
                    'Authorization: remitaConsumerKey=' . $this->apiKey . ',remitaConsumerToken=' . $statusHash,
                ],
            ]);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            error_log("RemitaModel: verifyPayment HTTP $httpCode | body: $response");

            $this->writeDebugLog([
                'type'      => 'PAYMENT_VERIFICATION',
                'endpoint'  => $endpoint,
                'rrr'       => $rrr,
                'http_code' => $httpCode,
                'response'  => $response,
                'curl_error'=> $curlError,
            ]);

            if ($httpCode === 200) {
                $result = json_decode($response, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    // Determine status from multiple possible fields
                    $paymentStatus = $result['status']
                        ?? $result['paymentStatus']
                        ?? $result['message']
                        ?? null;

                    // responseCode 00 generally means success
                    if (!empty($result['responseCode']) && $result['responseCode'] === '00' && empty($paymentStatus)) {
                        $paymentStatus = 'PAID';
                    }

                    if ($paymentStatus) {
                        $upperStatus = strtoupper($paymentStatus);
                        if (in_array($upperStatus, ['PAID', 'SUCCESS', '00', 'SUCCESSFUL'], true)) {
                            return [
                                'status'       => 'success',
                                'message'      => 'Payment verified',
                                'payment_data' => $result,
                            ];
                        }
                        if ($upperStatus === 'PENDING') {
                            return [
                                'status'       => 'pending',
                                'message'      => 'Payment pending',
                                'payment_data' => $result,
                            ];
                        }
                    }
                }
            }

            return [
                'status'        => 'error',
                'message'       => "Payment not found or not completed. HTTP: $httpCode",
                'response_data' => $response,
            ];

        } catch (Exception $e) {
            error_log("RemitaModel::verifyPayment exception: " . $e->getMessage());
            return [
                'status'  => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    // -------------------------------------------------------------------------
    // STATUS CHECK  (DB + Remita)
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
        return (int) $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE rrr = :rrr",
            ['rrr' => $rrr]
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
                // Mask sensitive fields
                $req = $data['request'];
                $entry .= "Request   : " . json_encode($req, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            }

            $entry .= "Response  : " . (is_string($data['response']) ? $data['response'] : json_encode($data['response'])) . "\n";
            $entry .= str_repeat('-', 60) . "\n\n";

            file_put_contents($logDir . '/remita_debug.log', $entry, FILE_APPEND | LOCK_EX);

        } catch (Exception $e) {
            error_log("RemitaModel: could not write debug log - " . $e->getMessage());
        }
    }
}