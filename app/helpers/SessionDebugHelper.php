<?php
/**
 * Session Debug Helper - Use only for testing
 */
class SessionDebugHelper {
    
    public static function logSession($location) {
        $debugFile = __DIR__ . '/../../session_debug.log';
        $data = [
            'time' => date('Y-m-d H:i:s'),
            'location' => $location,
            'session_id' => session_id(),
            'session_status' => session_status(),
            'session_data' => $_SESSION
        ];
        file_put_contents($debugFile, print_r($data, true) . "\n---\n", FILE_APPEND);
    }
    
    public static function ensureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        self::logSession('ensureSession called');
    }
}