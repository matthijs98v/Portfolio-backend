<?php

/**
 * Jwt class is used to generate a jwt (json web token)
 *
 * @author     Matthijs Verheijen <info@matthijsverheijen.com>
 */

namespace Lib\Auth;

class Jwt {

    private array $payload = [];
    private array $headers = [ "alg" => "HS512"];
    private string $secret = "Test";

    /***
     * Changes the secret of the server
     *
     * @param string $value
     *
     * @return void
     */
    public function changeSecret(string $value) : void{
        $this->secret = $value;
    }

    /***
     * Returns a proper base64 url encode
     *
     * @param string $text
     *
     * @return string
     */
    private function base64UrlEncode(string $text) : string {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    /***
     * Set the id of the payload
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): Jwt {
        $this->payload['id'] = $id;
        return $this;
    }

    /***
     * Set the id of the subject
     *
     * @param string $sub
     *
     * @return $this
     */
    public function setSub(string $sub): Jwt {
        $this->payload['sub'] = $sub;
        return $this;
    }

    /***
     * Set the exploration date of the payload
     *
     * @param string $exp
     *
     * @return $this
     */
    public function setExp(string $exp): Jwt {
        $this->payload['exp'] = $exp;
        return $this;
    }

    /***
     * Set the issuer of the payload
     *
     * @param string $iss
     *
     * @return $this
     */
    public function setIssuer(string $iss): Jwt {
        $this->payload['iss'] = $iss;
        return $this;
    }

    /***
     * Set issued at of the payload
     *
     * @param string $iss
     *
     * @return $this
     */
    public function issuedAt(string $iss): Jwt {
        $this->payload['iat'] = $iss;
        return $this;
    }

    /***
     * Checks if token is valid
     *
     * @param string $token
     *
     * @return bool
     */
    public function validate(string $token): bool {

        // Get the parts of the token
        $jwtParts = explode('.', $token);

        // Check if token has a valid length
        if(count($jwtParts) != 3){
            return false;
        }

        // The parts
        $header = $jwtParts[0];
        $payload = $jwtParts[1];
        $signature = $jwtParts[2];

        // Decoded header and payload
        $header_decoded = json_decode(base64_decode($header));
        $payload_decoded = json_decode(base64_decode($payload));

        // Generate signature for comparison
        $payloadEncoded = $payload;
        $headersEncoded = $header;
        $signature_comp = $this->base64UrlEncode(hash_hmac('sha512',"$headersEncoded.$payloadEncoded",$this->secret,true));

        // Check if signature is valid
        if($signature_comp != $signature) {
            return false;
        }

        // Check if token is already valid
        if(isset($payload_decoded->iat) && time() < $payload_decoded->iat){
            return false;
        }

        // Check if token is not expired
        if(isset($payload_decoded->exp) && time() > $payload_decoded->exp ) {
            return false;
        }

        // Requirements do not fail
        return true;
    }

    public function getPayload( string $token ) : array | object {
        $jwtParts = explode('.', $token);
        $payload = $jwtParts[1];
        return $payloadDecoded = json_decode(base64_decode($payload));
    }

    /***
     * Generates the token
     *
     * @return string
     */
    public function create(): string {
        $payloadEncoded = $this->base64UrlEncode(json_encode($this->payload));
        $headersEncoded = $this->base64UrlEncode(json_encode($this->headers));

        // For the signature
        $signature = $this->base64UrlEncode(hash_hmac('sha512',"$headersEncoded.$payloadEncoded",$this->secret,true));

        // Return generated token
        return "$headersEncoded.$payloadEncoded.$signature";
    }
}

?>