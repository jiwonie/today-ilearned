<?php


// session start
session_start();



// error report
error_reporting(E_ERROR);
ini_set('display_errors', 1);



// class autoload
spl_autoload_register(function(string $class) {
    $filename = lcfirst(str_replace('\\', '/', $class)) . '.php';
    if (is_file($filename)) {
        include_once $filename;
    } else {
        http_response_code(404);
        require 'view/errors/404.php';
        exit;
    }
});



// router setting
$__router = array(
    ''                 => 'Controller\Index',
    'join'             => 'Controller\Member',
    'login'            => 'Controller\Member',
    'logout'           => 'Controller\Member',
    'board'            => 'Controller\Board'
);



// ----- url parsing
parse_str(parse_url($_SERVER['REQUEST_URI'])['query'], $_GET);
$__path = explode('/', parse_url($_SERVER['REQUEST_URI'])['path']);
$__root = $__path[1];



// get url even resource
for ($i = 0; $i < count($__path); $i++) {
    switch ($__path[$i]) {
        case 'page': 
            $__var['page'] = $__path[$i + 1];
            break;
        case 'write': 
            $__var['writable'] = true;
            break;
        case 'edit': 
            $__var['editable'] = true;
            break;
        default: 
            $__var[$__path[$i - 1]] = $__path[$i];

            if (!is_numeric($__path[$i]) && ($i & 1)) {
                $__var['method'] = $__path[$i];
            }
            break;
    }
}



// url control
if (class_exists($__router[$__root])) {
    new $__router[$__root]($__var);
} else {
    http_response_code(404);
    require 'view/errors/404.php';
    exit;
}