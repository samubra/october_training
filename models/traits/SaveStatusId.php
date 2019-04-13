<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-4-4
 * Time: 下午9:14
 */

namespace Samubra\Training\Models\Traits;

use mysql_xdevapi\Exception;
use Samubra\Training\Models\Status;

trait SaveStatusId
{
    //public $status_filed = '';

    public function beforeSave()
    {
        //此处在新建记录时应设置默认值
        $lastStatus = $this->status_change->sortByDesc('updated_at')->pluck('id');
        //traceLog($lastStatus->toArray());
        if(!count($lastStatus->toArray())) {
            switch (self::class) {
                case 'Samubra\Training\Models\Record':
                    $statusModel = Status::record();
                    break;
                case 'Samubra\Training\Models\Project':
                    $statusModel = Status::project();
                    break;
                default:
                    $statusModel = false;
                    break;
            }
            $ids = $statusModel->orderBy('sort','ASC')->pluck('id');
            throw_if(!$ids,new \Exception('无法设置初始值'));
            //traceLog($ids);
            $status_id = $ids[0];
        }else{
            $status_id = $lastStatus->last();
        }
        //trace_log($status_id);
        $this->attributes[$this->status_filed] = $status_id;
        //trace_log($lastStatus->first()->id);
    }
}