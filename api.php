<?php

require_once dirname(__FILE__) . '/src/service/ResponseService.php';

class Api
{
    private $routes;
    private $responseService;

    public function __construct()
    {
        $this->responseService = new ResponseService();

        $this->HandleRequest($_GET, $_POST);

        Header('Content-type: applicaton/json');
    }

    public function HandleRequest($get, $post)
    {
        $endpoint = isset($get['route']) ? $get['route'] : null;

        if (!$endpoint) {
            echo $this->responseService->Send(false, 404, 'REQUEST_ROUTE_IS_REQUIRED', null);
            exit;
        }

        $service = isset($get['service']) ? $get['service'] : null;

        if (!$service) {
            echo $this->responseService->Send(false, 404, 'REQUEST_SERVICE_IS_REQUIRED', null);
            exit;
        }

        require_once(dirname(__FILE__) . '/src/controller/' . $endpoint . 'Controller.php');

        $controller = $endpoint . 'Controller';

        if (!class_exists($controller)) {
            echo $this->responseService->Send(false, 404, 'REQUEST_ENDPOINT_NOT_FOUND', null);
            exit;
        }

        $controller = new $controller;

        if (!method_exists($controller, $service)) {
            echo $this->responseService->Send(false, 404, 'REQUEST_INVALID_SERVICE', null);
            exit;
        }

        call_user_func_array([$controller, $service], $post);
    }
}

new Api();