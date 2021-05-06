<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Service;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 渲染后台view
     *
     * @param null $view
     * @param array $data
     * @param array $mergeData
     * @return Application|Factory|View
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }

    /**
     * 正确信息返回
     *
     * @param array $data
     * @param string $meta
     * @param string $msg
     * @param int $httpCode
     * @param array $other
     * @return JsonResponse
     */
    public function ajaxSuccess(array $data = [], $meta = '', string $msg = 'success', int $httpCode = 200, $other = [])
    {
        $return = [
            'other' => $other,
            'code' => 0,
            'msg' => $msg,
            'data' => $data,
            'meta' => $meta,
            'httpCode' => $httpCode,
        ];

        return response()->json($return, $httpCode);
    }

    /**
     * 返回错误ajax返回
     *
     * @param string $errMsg
     * @param int $httpCode
     * @return JsonResponse
     */
    public function ajaxError(string $errMsg = 'error', int $httpCode = 200)
    {
        $return = [
            'code' => 1,
            'msg' => $errMsg,
            'exception' => $errMsg
        ];

        return response()->json($return, $httpCode);
    }

    /**
     * 简单ajax返回
     *
     * @param bool $result
     * @param Service $service
     *
     * @return JsonResponse
     */
    protected function ajax(bool $result,Service $service)
    {
        if ($result) {
            return $this->ajaxSuccess();
        }

        return $this->ajaxError($service->getError(), $service->getHttpCode());
    }
}
