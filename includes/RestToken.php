<?php
/**
 * Simple token protect
 */


/**
 * Class RestToken
 */
class RestToken
{

    protected $error = null;


    public function protect($result)
    {

        $token = get_option('dcoupled_token', '');

        if (!empty($result) || empty($token)) {
            return $result;
        }

        $headers = getallheaders();

        if (!isset($headers['dcoupled-token'])) {
            $this->error = new \WP_Error('rest_authentication_error', 'Access denied.');
        } elseif ($headers['dcoupled-token'] !== $token) {
            $this->error = new \WP_Error('rest_authentication_error', 'Invalid token.');
        }

        if (is_wp_error($this->error)) {
            http_response_code(403);
            wp_send_json_error(['error' => $this->error->get_error_message()]);
        }

        return $result;
    }
}