<?php

/**
 * Curl wrapper for making an easy post request
 *
 * @author     Matthijs Verheijen <info@matthijsverheijen.com>
 */

namespace lib\Curl;

class Curl {
    /**
     * Execute a POST request via curl
     *
     * @param String $url
     * @param array $post
     * @param array $headers
     * @return bool|string|array
     * @throws \Exception
     */
    public static function post ( string $url, array $post = [], array $headers = [] ): bool|string|array
    {

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options for POST request
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Add the headers
        foreach ( $headers as $key => $value ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$key => $value]);
        }

        // Execute cURL session and get the response
        $response = curl_exec($ch);
        // Check for cURL errors
        if ( curl_errno($ch) ) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        return $response;
    }

    /**
     * Execute a GET request via curl
     *
     * @param string $url
     * @param array $headers
     * @return bool|string|array
     * @throws \Exception
     */
    public static function get ( string $url, array $headers = [] ): bool|string|array
    {

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options for POST request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Add the headers
        foreach ( $headers as $key => $value ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$key => $value]);
        }

        // Execute cURL session and get the response
        $response = curl_exec($ch);
        // Check for cURL errors
        if ( curl_errno($ch) ) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        return $response;
    }
}