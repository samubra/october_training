<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-9-14
 * Time: 上午11:07
 */
namespace Samubra\Training\Components;

class RecordForm extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => '添加申请记录表单',
            'description' => '添加培训申请的表单'
        ];
    }

    // This array becomes available on the page as {{ component.posts }}
    public function posts()
    {
        return ['First Post', 'Second Post', 'Third Post'];
    }
}