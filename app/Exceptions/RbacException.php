<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\View\View;
use Throwable;

class RbacException extends Exception
{
    const NOT_RULE = 403;

    public static $errMsg = [
        RbacException::NOT_RULE => '抱歉，你无权访问该页面'
    ];

    /**
     * RbacException constructor.
     * @param int $code
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, $message = "", Throwable $previous = null)
    {
        $code = $code ?: static::NOT_RULE;

        $message = $message ?: static::$errMsg[$code];

        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return View
     */
    public function render($request)
    {
        return view('admin.errors.403', ['message' => $this->message, 'code' => $this->code]);
    }
}
