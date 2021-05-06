<?php

namespace App\Attributes;

#[Attribute(Attribute::TARGET_ALL))]
class Permission
{
    //名称
    public function __construct($permission)
    {
    }
}
