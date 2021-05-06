<?php

namespace App\Services;

use App\Contracts\ServiceModel;
use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model model
 */
class Service implements ServiceModel
{
    protected $error = '';
    protected $httpCode = 200;
    protected $message = 'success';

    public function getError(): string
    {
        return $this->error;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Specify Model class name.
     *
     * @return string
     * @throws Exception
     */
    public function model()
    {
        throw new Exception('model method not implemented');
    }

    public function __get($name)
    {
        if ($name == 'model' && empty($this->model)) {
            return $this->model = app($this->$name());
        }

        return $this->$name ?? null;
    }

    /**
     * 接管部分model查询
     *
     * @param $method
     * @param $arguments
     * @return false|mixed
     */
    public function __call($method, $arguments)
    {
        if ($method == 'find') {
            return call_user_func_array([$this->model, $method], $arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
