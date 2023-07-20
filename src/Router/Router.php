<?php

namespace App;

class Router
{
//    private array $handlers;
//    private $notFoundHandler;
//    private const METHOD_POST = "POST";
//    private const METHOD_GET = "GET";
//
//    public function get(string $path, $handler): void
//    {
//        $this->addHandler(self::METHOD_GET, $path, $handler);
//    }
//
//    public function post(string $path, $handler): void
//    {
//        $this->addHandler(self::METHOD_POST, $path, $handler);
//    }
//
//    public function addHandler(string $method, string  $path, $handler): void
//    {
//        $this->handlers[$method . $path] = [
//            "path" =>$path,
//            "method" => $method,
//            "handler" => $handler
//        ];
//    }
//
//    /**
//     * @return mixed
//     */
//    public function addNotFoundHandler($handler): void
//    {
//        $this->notFoundHandler = $handler;
//    }

//    public function run()
//    {
//        $requestUri = parse_url($_SERVER["REQUEST_URI"]);
//        $requestPath = $requestUri["path"];
//        $method = $_SERVER["REQUEST_METHOD"];
//
//        $callback = null;
//        foreach ($this->handlers as $handler) {
//            if ($handler["path"] === $requestPath && $method === $handler["method"]) {
//                $callback = $handler["handler"];
//            }
//        }
//
//        if(is_string($callback)) {
//            $parts = explode("::", $callback);
//
//            if (is_array($parts)) {
//                $className = array_shift($parts);
//                $handler = new $className;
//
//                $method = array_shift($parts);
//                $callback = [$handler, $method];
//            }
//        }
//
//        if(!$callback){
//            header("HTTP/1.0 404 Not Found");
//            if(!empty($this->notFoundHandler)) {
//                $callback = $this->notFoundHandler;
//            }
//        }
//
//        call_user_func_array($callback, [
//            array_merge($_GET, $_POST)
//        ]);
//    }



    public static array $patterns = [
        ':id[0-9]?' => '([0-9]+)',
        ':url[0-9]?' => '([0-9a-zA-Z-_]+)'
    ];

    public static bool $hasRoute = false;
    public static array $routes = [];
    public static string $prefix = '';

    /**
     * @param $path
     * @param $callback
     * @return Route
     */
    public static function get(string $path, $callback): Route
    {
        self::$routes['get'][self::$prefix . $path] = [
            'callback' => $callback
        ];
        return new self();
    }

    /**
     * @param string $path
     * @param $callback
     */
    public static function post(string $path, $callback): void
    {
        self::$routes['post'][$path] = [
            'callback' => $callback
        ];
    }

    public static function dispatch()
    {
        $url = self::getUrl();
        $method = self::getMethod();
        foreach (self::$routes[$method] as $path => $props) {

            foreach (self::$patterns as $key => $pattern) {
                $path = preg_replace('#' . $key . '#', $pattern, $path);
            }
            $pattern = '#^' . $path . '$#';

            if (preg_match($pattern, $url, $params)) {

                self::$hasRoute = true;
                array_shift($params);

                if (isset($props['redirect'])) {
                    Redirect::to($props['redirect'], $props['status']);
                } else {

                    $callback = $props['callback'];

                    if (is_callable($callback)) {
                        echo call_user_func_array($callback, $params);
                    } elseif (is_string($callback)) {

                        [$controllerName, $methodName] = explode('@', $callback);

                        $controllerName = '\Prototurk\App\Controllers\\' . $controllerName;
                        $controller = new $controllerName();
                        echo call_user_func_array([$controller, $methodName], $params);

                    }

                }

            }
        }
        self::hasRoute();
    }

    public static function hasRoute()
    {
        if (self::$hasRoute === false) {
            die('Page not found');
        }
    }

    /**
     * @return string
     */
    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public static function getUrl(): string
    {
        return str_replace(getenv('BASE_PATH'), null, $_SERVER['REQUEST_URI']);
    }

    public function name(string $name): void
    {
        $key = array_key_last(self::$routes['get']);
        self::$routes['get'][$key]['name'] = $name;
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function url(string $name, array $params = []): string
    {
        $route = array_key_first(array_filter(self::$routes['get'], function ($route) use ($name) {
            return isset($route['name']) && $route['name'] === $name;
        }));
        return getenv('BASE_PATH') . str_replace(array_map(fn($key) => ':' . $key, array_keys($params)), array_values($params), $route);
    }

    /**
     * @param $prefix
     * @return Route
     */
    public static function prefix($prefix): Route
    {
        self::$prefix = $prefix;
        return new self();
    }

    /**
     * @param \Closure $closure
     */
    public static function group(\Closure $closure): void
    {
        $closure();
        self::$prefix = '';
    }

    public function where($key, $pattern)
    {
        self::$patterns[':' . $key] = '(' . $pattern . ')';
    }

    public static function redirect($from, $to, $status = 301)
    {
        self::$routes['get'][$from] = [
            'redirect' => $to,
            'status' => $status
        ];
    }

}