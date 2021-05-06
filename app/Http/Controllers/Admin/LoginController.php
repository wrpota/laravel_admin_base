<?php
/**
 * Created by PhpStorm.
 * User: alina
 * DateTime: 2020/12/15 14:07
 */

namespace App\Http\Controllers\Admin;

use App\Services\AdminService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseController
{
    /**
     * 登录
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->validate($request, [
                'name' => 'required',
                'password' => 'required',
                'captcha' => 'required|captcha',
            ],[
                'captcha.required' => trans('validation.required'),
                'captcha.captcha' => trans('validation.captcha'),
            ]);

            $service = new AdminService();
            $re = $service->login($request->name, $request->password, (bool)$request->remember);
            if (!$re) {
                return $this->ajaxError($service->getError(), $service->getHttpCode());
            }

            return $this->ajaxSuccess();
        }

        return view('admin.login.login');
    }

    /**
     * 退出登录
     *
     * @return Redirector
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect(route('auth.login'));
    }
}
