<?php

namespace App\Attributes\Annotation;

/**
 * 权限注释类
 *
 * Target表示注解生效的范围(ALL,CLASS,METHOD,PROPERTY,ANNOTATION)
 *
 * @Annotation
 * @Target({"ALL"})
 */
class Permission
{
    /**
     * 权限标示 - 不区分大小写
     *
     * @Required()
     * @var string
     */
    public $identification;

    /**
     * 请求方式验证
     *
     * @Enum({"POST", "GET", "PUT", "DELETE", "ALL"})
     * @var string
     */
    public $method = 'ALL';
}
