<?php


// session start
session_start();



// error report
ini_set('error_reporting', E_ERROR);
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
    '/'                 => 'Controller\Index',
    '/join'             => 'Controller\Member',
    '/login'            => 'Controller\Member',
    '/logout'           => 'Controller\Member',
    '/board'            => 'Controller\Board',
    '/board/write'      => 'Controller\Board'
);



// url parsing
$__parse_url = parse_url($_SERVER['REQUEST_URI']);
$__restful_path = explode('/', $__parse_url['path']);
$__restful_root = '/' . $__restful_path[1];



// get value setting
parse_str($__parse_url['query'], $_GET);



// get url even resource (for restful url)
for ($i = 0; $i < count($__restful_path); $i++) {
    switch ($__restful_path[$i]) {
        case 'page': 
            $__var['page'] = $__restful_path[$i + 1];
            break;
        case 'write': 
            $__var['writable'] = true;
            break;
        case 'edit': 
            $__var['editable'] = true;
            break;
        default: 
            $__var[$__restful_path[$i - 1]] = $__restful_path[$i];

            if (!is_numeric($__restful_path[$i]) && ($i & 1)) {
                $__var['method'] = $__restful_path[$i];
            }
            break;
    }
}



// url control
if (class_exists($__router[$__parse_url['path']])) {

    // for basic url
    new $__router[$__parse_url['path']]($__parse_url);

} else if (class_exists($__router[$__restful_root])) {

    // for restful url
    new $__router[$__restful_root]($__var);

} else {

    // class is not exists
    http_response_code(404);
    require 'view/errors/404.php';
    exit;

}