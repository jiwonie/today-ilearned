<?php


// * session start
session_start();



// * error report
error_reporting(E_ERROR);
ini_set('display_errors', 1);



// * class autoload
spl_autoload_register(function(string $class) {
    $filename = lcfirst(str_replace('\\', '/', $class)) . '.php';
    if (is_file($filename)) {
        include_once $filename;
    } else {
        http_response_code(404);
        exit('404 Not Found');
    }
});



// * router setting
$__router = array(
    '/'                 => 'Controller\Sample\Index',
    '/join'             => 'Controller\Sample\Member',
    '/login'            => 'Controller\Sample\Member',
    '/logout'           => 'Controller\Sample\Member',
    '/board'            => 'Controller\Sample\Board'
);



// * ----- url parsing
parse_str(parse_url($_SERVER['REQUEST_URI'])['query'], $_GET);
$__rest_path = explode('/', parse_url($_SERVER['REQUEST_URI'])['path']);

// * get page number
if ($__path_index = array_search('page', $__rest_path) ?? false) {
    $__variables['page'] = $__rest_path[$__path_index + 1];
    unset($__rest_path[$__path_index], $__rest_path[$__path_index + 1]);
}

// * get url last odd resource
$__exec_func = end(
    array_filter(
        $__rest_path, 
        function ($get_odd_index) {
            return $get_odd_index & 1;
        }, 
        ARRAY_FILTER_USE_KEY
    )
);

// * get url even resource
for ($i = 2; $i < count($__rest_path); $i++) {
    if ($i % 2 === 0) {
        $__variables[$__rest_path[$i - 1]] = $__rest_path[$i];
    }
}
// * ----- end of url parsing



// * url control
if (class_exists($__router["/{$__rest_path[1]}"])) {
    new $__router["/{$__rest_path[1]}"]($__exec_func, $__variables);
} else {
    http_response_code(404);
    require 'view/errors/404.php';
    exit;
}