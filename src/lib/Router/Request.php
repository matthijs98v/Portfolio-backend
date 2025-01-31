<?php

/**
 * This is a wrapper for the request
 *
 * @author     Matthijs Verheijen <info@matthijsverheijen.com>
 */

namespace Lib\Router;

use Exception;

class Request {
    private array $required = [];
    private array $params = [];
    private array $post = [];
    private array $files = [];
    private array $get = [];

    public function __construct(array $params = []) {
        $this->params = $params;

        // Convert json data to post array
        $jsonData = file_get_contents('php://input');
        $json = json_decode($jsonData, true);

        if (json_last_error() == null) {
            $this->post = $json;
        } else {
            $this->post = $_POST;
        }

        // Save get and files data
        $this->get = $_GET;
        $this->files = $_FILES;
    }

    /***
     * Returns a post array
     *
     * @param string|null $field
     *
     * @return string|array|null
     */
    public function post(?string $field = null): string|array|null {
        if ($field == null) {
            return $this->post;
        }

        return $this->post[$field] ?? null;
    }

    /***
     * Returns a file array
     *
     * @param string|null $field
     *
     * @return string|array|null
     */
    public function files(?string $field = null): string|array|null {
        if ($field == null) {
            return $this->files;
        }

        return $this->files[$field] ?? null;
    }

    /***
     * Returns a get array
     *
     * @param string|null $field
     *
     * @return string|array|null
     */
    public function get(?string $field = null): string|array|null {
        if ($field == null) {
            return $this->get;
        }

        return $this->get[$field] ?? null;
    }

    /***
     * Returns a server array
     *
     * @param string|null $field
     *
     * @return string|array|null
     * @throws Exception
     */
    public function server(?string $field = null): string|array|null {
        if ($field == null) {
            return $_SERVER;
        }

        // Checks if field isset
        if(!isset($_SERVER[$field])) throw new Exception('Server var '.$field.' not set');

        return $_SERVER[$field];
    }

    /***
     * Used for url fragments
     *
     * @param null $field
     *
     * @return string|array|null
     */
    public function params($field = null): string|array|null {
        if ($field == null) {
            return $this->params;
        }

        return $this->params[$field] ?? null;
    }

    /***
     * Ads a requirement
     *
     * @param string $field
     * @param string $type // can be either file or post
     *
     * @return void
     */
    public function addRequired(string $field, string $type='post'): void {
        $this->required[$type][] = $field;
    }

    /**
     * Validates the requirements
     *
     * @throws Exception
     */
    public function validateRequired(): void {
        // Keep track of the missing post data
        $missing = [];

        // Check for required post data
        if (!empty($this->required['post']) && isset($this->required['post'])) {
            // Check post array for requirements
            foreach ($this->required['post'] as $require) {
                if ($this->post($require) == null) {
                    $missing[] = $require.'(post)';
                }
            }
        }

        // Check for required file data
        if (!empty($this->required['file']) && isset($this->required['file'])) {
            foreach ($this->required['file'] as $require) {
                if ($this->files($require) == null) {
                    $missing[] = $require . '(file)';
                }
            }
        }

        // Check for required server data
        if (!empty($this->required['server']) && isset($this->required['server'])) {
            foreach ($this->required['server'] as $require) {
                if (!isset($_SERVER[$require])) {
                    $missing[] = $require . '(server)';
                }
            }
        }

        // Throw exception if something is missing
        if (!empty($missing)) {
            throw new Exception(
                implode(', ', $missing) . ' ' . (count($missing) > 1 ? 'are'
                    : 'is') . ' missing'
            );
        }
    }

}

?>