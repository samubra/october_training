<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-4-4
 * Time: 下午9:14
 */

namespace Samubra\Training\Models\Traits;

trait SaveStatusId
{
    //public $status_filed = '';

    public function beforeSave()
    {
        //此处在新建记录时应设置默认值
        $lastStatus = $this->status_change->sortByDesc('updated_at');
        $this->attributes[$this->status_filed] = $lastStatus->first()->id;
        trace_log($lastStatus->first()->id);
    }
}