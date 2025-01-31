<?php

/**
 * UrlParser is used to make a clean url
 *
 * @author     Matthijs Verheijen <info@matthijsverheijen.com>
 */

namespace Lib\Router;

use Exception;

class Router {
    private static array $routes = [];
    private static array $allowedMethods = ['GET', 'POST', 'DELETE', 'PUT'];

    public function __construct() {
        // If options request method is options
        if ($this->getMethod() === 'OPTIONS') {
            die();
        }
    }

    /**
     * Inserts the url parts into an array
     *
     * @param string $url
     *
     * @return array
     */
    private function urlToArray(string $url): array {
        // set url into array for later use
        $alt_url = explode('/', $url);

        // Make copy for removing elements while looping thought the array
        if ($alt_url[0] == null) {
            array_splice($alt_url, 0, 1);
        }
        $copy_alt_url = $alt_url;

        foreach ($copy_alt_url as $index => $a_url) {
            if ($a_url == null) {
                array_splice($alt_url, $index, $index);
            }
        }

        return $alt_url;
    }

    /***
     * Checks if there is a match with an url
     *
     * @param string $alt_url
     *
     * @return array|false
     */
    private function checkUrl(string $alt_url): array|false {
        // Check if mapping isset
        $_GET['mapping'] = $_GET['mapping'] ?? "";

        // Set the get into the array
        $c_url = $this->urlToArray($_GET['mapping']);
        $o_url = $this->urlToArray($alt_url);

        // check the length of the url
        if (count($c_url) != count($o_url)) {
            return false;
        }

        // parse url
        $data = $this->Parse($alt_url);
        $o_url = $data['alt_url'];

        // url
        foreach ($o_url as $index => $url) {
            if ($url[0] == '?') {
                continue;
            }

            if ($url != $c_url[$index]) {
                return false;
            }
        }

        return $data;
    }

    /**
     * Parses the url and get the vars
     *
     * @param string $url
     *
     * @return array
     */
    private function parse(string $url): array {
        // parse url
        $vars = [];

        // convert url to array
        $alt_url = $this->urlToArray($url);

        // holds the keys, so we can map the vars later om
        $var_keys = [];

        // Set value in array []
        for ($i = 0; $i < strlen($url); $i++) {
            $temp = "";
            $skip = 0;

            if ($url[$i] == "[") {
                for ($j = $i + 1; $j < strlen($url); $j++) {
                    if ($url[$j] == "]") {
                        break;
                    }
                    $skip++;
                    // store var name in a temporary string
                    $temp .= $url[$j];
                }
            }
            if ($temp != null) {
                $var_keys[] = $temp;
            }
            // Skip, so that the second loop does not repeat itself
            $i += $skip;
        }

        for ($i = 0; $i < count($alt_url); $i++) {
            if ($alt_url[$i][0] == "[" && $alt_url[$i][-1] == "]") {
                $alt_url[$i] = '?';
            }
        }

        // Map vars to array
        $var_count = 0;
        $index_count = 0;

        foreach ($alt_url as $value) {
            if ($value == "?") {
                $tmp = $this->urlToArray($_GET['mapping'])[$var_count];
                $vars[$var_keys[$index_count]] = $tmp;
                $index_count++;
            }
            $var_count++;
        }

        return ['vars' => $vars, 'url' => $url, 'alt_url' => $alt_url];
    }

    /***
     * Returns the method of the request
     *
     * @return string
     */
    public static function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'] ?? "";
    }

    /***
     * Adds a route and controller
     *
     * @param string            $method
     * @param string            $route
     * @param array|object|null $action
     *
     * @return void
     */
    private static function add(
        string $method,
        string $route,
        array|object|null $action = null
    ): void {
        self::$routes[$method][] = [
            'uri'    => $route,
            'action' => $action
        ];
    }

    /***
     * Get route
     *
     * @param string            $route
     * @param array|object|null $action
     *
     * @return void
     */
    public static function get(
        string $route,
        null|array|object $action = null
    ): void {
        self::add('GET', $route, $action);
    }

    /***
     * Post route
     *
     * @param string            $route
     * @param array|object|null $action
     *
     * @return void
     */
    public static function post(
        string $route,
        null|array|object $action = null
    ): void {
        self::add('POST', $route, $action);
    }

    /***
     * Delete route
     *
     * @param string            $route
     * @param array|object|null $action
     *
     * @return void
     */
    public static function delete(
        string $route,
        null|array|object $action = null
    ): void {
        self::add('DELETE', $route, $action);
    }

    /***
     * PUT route
     *
     * @param string            $route
     * @param array|object|null $action
     *
     * @return void
     */
    public static function put(
        string $route,
        null|array|object $action = null
    ): void {
        self::add('PUT', $route, $action);
    }

    /***
     * Returns all available routes
     *
     * @return array
     */
    public static function getRoutes(): array {
        return self::$routes;
    }

    /***
     * Runs the parser and checker
     *
     * @return array
     * @throws Exception
     */
    public function run(): array {
        // Check if method is allowed
        if (!in_array(self::getMethod(), self::$allowedMethods)) {
            throw new Exception('Method not allowed');
        }

        // Check if array is not empty
        if (!isset(self::$routes[self::getMethod()])) {
            throw new Exception('Url not found');
        }

        // Loop through each route, it also gets the right method
        foreach (self::$routes[self::getMethod()] as $route) {
            $check = $this->checkUrl($route['uri']);

            $func_return = null;

            if ($check) {
                // Execute controller
                if (!empty($route['action']) && is_array($route['action'])
                    && count($route['action']) == 2
                    && is_callable(
                        $route['action'][0] . '::' . $route['action'][1]
                    )
                ) {
                    // When it is a controller
                    $func_return = call_user_func(
                        $route['action'][0] . '::' . $route['action'][1],
                        new Request($check['vars'])
                    );
                } elseif (is_callable($route['action'])
                    && !is_string(
                        $route['action']
                    )
                ) {
                    // When it is a function
                    $func_return = call_user_func(
                        $route['action'],
                        new Request($check['vars'])
                    );
                }
                // Return all the information
                return [
                    ...$check, 
                    'action' => $route['action'], 
                    'return' => $func_return
                ];
            }
        }

        throw new Exception('Url not found');
    }
}

?>