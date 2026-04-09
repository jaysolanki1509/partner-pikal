<?php

namespace App;

class HttpClientWrapper
{
    private $max_retries = 3;
    private $retry_delay = 1000; // milliseconds

    public function send_request($method, $params, $url, $token = null)
    {
        $method = strtoupper($method);

        if (!preg_match('#^https?://#i', $url)) {
            $url = 'http://' . ltrim($url, '/');
        }

        if ($method === 'GET' && !empty($params)) {
            $url .= strpos($url, '?') === false ? '?' . http_build_query($params) : '&' . http_build_query($params);
        }

        // Retry logic for timeout resilience
        $attempt = 0;
        while ($attempt < $this->max_retries) {
            $response = $this->executeRequest($method, $params, $url, $token);

            $result = json_decode($response, true);

            // If no timeout error, return immediately
            if (!isset($result['status']) || $result['status'] !== 'error' || strpos($result['message'], 'timeout') === false) {
                return $response;
            }

            $attempt++;
            if ($attempt < $this->max_retries) {
                usleep($this->retry_delay * 1000); // Convert ms to microseconds
            }
        }

        return $response;
    }

    private function executeRequest($method, $params, $url, $token = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Increased timeout for slow local connections
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 60000);

        // Optimize connection
        curl_setopt($ch, CURLOPT_TCP_KEEPALIVE, 1);
        curl_setopt($ch, CURLOPT_TCP_KEEPIDLE, 240);
        curl_setopt($ch, CURLOPT_TCP_KEEPINTVL, 60);

        // Follow redirects and headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Set HTTP headers
        $headers = [];
        if (!empty($token)) {
            $headers[] = 'Cookie: laravel_session=' . $token;
        }
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Connection: keep-alive';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Handle different HTTP methods
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } elseif ($method === 'PUT' || $method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        // SSL options
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        // DNS cache
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 3600);

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $curl_error) {
            return json_encode([
                'status' => 'error',
                'message' => $curl_error ?: 'No response from server',
                'http_code' => $http_code
            ]);
        }

        if ($http_code >= 400) {
            return json_encode([
                'status' => 'error',
                'message' => 'HTTP Error: ' . $http_code,
                'response' => $response,
                'http_code' => $http_code
            ]);
        }

        return $response;
    }
}
