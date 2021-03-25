<?php

namespace Controller;

class Controller
{
    protected $__variables;

    /**
    * * REQUEST_URI 값에 따라서 실행 될 함수 지정
    * * '/' 요청이 오면 index 함수가 실행 될 수 있도록 별도 지정
    */
    public function __construct($__variables = [])
    {
        if (isset($__variables['path']) && !empty($__variables['path'])) {
            // for basic url
            $request_uri = array_values(
                array_map(
                    'trim', 
                    array_filter(explode('/', $__variables['path']))
                )
            ) ?: array('index');

            foreach ($request_uri as $key => $val) {
                if ($key === array_key_first($request_uri)) {
                    $__variables['method'] .= $val;
                } else {
                    $__variables['method'] .= ucwords($val);
                }
            }

            $this->__variables = $__variables;
        } else {

            // for restful url
            $__variables['method'] = $__variables['method'] ?: 'index';
            $this->__variables = $__variables;
        }
    }

    /**
    * * $req : 화면에 표시 할 페이지
    * * $res : 페이지에서 사용 할 데이터
    * 
    * @param string $req
    * @param array $res
    * @param string $header
    * @param string $footer
     */
    public function view(string $req, array $res = array(), string $header = 'assets/includes/header', string $footer = 'assets/includes/footer')
    {
        if (is_file("view/{$req}.php") && is_file("view/{$header}.php") && is_file("view/{$footer}.php")) {
            include_once "view/{$header}.php";
            include_once "view/{$req}.php";
            include_once "view/{$footer}.php";
        } else {
            http_response_code(404);
            require 'view/errors/404.php';
            exit;
        }
    }

    /**
    * * 컨트롤러 로직 처리 중, 이벤트 발생 시 페이지 전환
    * 
    * @param string $location
    * @param string $message
     */
    public function relocation(string $location = '/', string $message = '')
    {
        $alert = !empty($message) ? "alert('{$message}');" : "";
        echo "<script>{$alert}location.href='{$location}';</script>";
        exit;
    }

    /**
    * * $_POST 또는 $_GET 등 입력 값에 대한 기본적인 injection 방어
    * 
    * @param array $request
    * @return array
     */
    function injection(array $request = array()):array
    {
        foreach ($request as $key => $req) {
            if (is_array($req)) {
                array_map('injection', $req);
            } else {
                $request[$key] = htmlspecialchars(strip_tags($req), ENT_QUOTES);
            }
        }

        return $request;
    }

}