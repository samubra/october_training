<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-4-10
 * Time: 下午8:40
 */

namespace Samubra\Training\Models\Traits;


trait CustomValidateMessage
{
    public $customMessages = [
        'identity' => ':attribute 格式不正确。',
        'phone' => ':attribute 格式不正确。'
    ];
}