<?php

namespace Controller;

class Controller
{
    protected $__variables;

    /**
     * 실행할 함수 지정, 메소드 체이닝
     * 
     * 파싱 된 URL 변수($__parse_url)를 넘긴 경우, 
     * path 키 존재 여부를 확인하여 일반 URL 형식으로 메소드 체이닝
     * '/' 요청이 오면 index 함수가 실행 될 수 있도록 별도 지정
     * 
     * 
     * @param array $__variables
     */
    public function __construct($__variables = [])
    {
        if (isset($__variables['path']) && !empty($__variables['path'])) {
            // for NORMAL_URL
            $__var_path = array_values(array_map('trim', array_filter(explode('/', $__variables['path'])))) ?: array('index');

            foreach ($__var_path as $key => $value) {
                if ($key === array_key_first($__var_path)) {
                    $__variables['method'] .= $value;
                } else {
                    $__variables['method'] .= ucwords($value);
                }
            }

            $this->__variables = $__variables;
        } else {
            // for RESTFUL_API
            $__variables['method'] = $__variables['method'] ?: 'index';
            $this->__variables = $__variables;
        }
    }

    /**
     * 화면에 표시할 페이지 include 처리
     * 
     * 
     * @param string $req    화면에 표시 할 페이지
     * @param array $res     페이지에서 사용 할 데이터
     * @param string $header 입력 된 헤더가 없는 경우, 기본 헤더 적용
     * @param string $footer 입력 된 푸터가 없는 경우, 기본 푸터 적용
     */
    public function view(string $req, array $res = array(), string $header = 'assets/includes/header', string $footer = 'assets/includes/footer'):void
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
     * 컨트롤러 로직 처리 중, 이벤트 발생 시 페이지 전환
     * 
     * 
     * @param string $location  이동할 페이지명
     * @param string $message   이동 전 표시할 메시지
     */
    public function relocation(string $location = '/', string $message = ''):void
    {
        $alert = !empty($message) ? "alert('{$message}');" : "";
        echo "<script>{$alert}location.href='{$location}';</script>";
        exit;
    }

    /**
     * $_POST 또는 $_GET 등 입력 값에 대한 기본적인 injection 방어 처리
     * 
     * 
     * @param array  $request  $_POST, $_GET, $_REQUEST 등의 input 배열 변수
     * @return array 별도 injection 방어 처리 후 동일한 배열 반환
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