<?php namespace Samubra\Training\Updates;

use Samubra\Training\Models\Status;
use Seeder;
use Samubra\Training\Repositories\Train\StatusRepository;

class statusseeder extends Seeder
{
    
    protected $statusList = [
        [
            'name' => '接受报名申请',
            'type' => Status::STATUS_PROJECT,
            'sort' => 1,
            'color' => '#1abc9c'
        ],
        [
            'name' => '已停止报名申请',
            'type' => Status::STATUS_PROJECT,
            'sort' => 2,
            'color' => '#16a085'
        ],
        [
            'name' => '培训资料已存档',
            'type' => Status::STATUS_PROJECT,
            'sort' => 3,
            'color' => '#2ecc71'
        ],
        [
            'name' => '等待受理',
            'type' => Status::STATUS_RECORD,
            'sort' => 1,
            'color' => '#27ae60'
        ],
        [
            'name' => '正在受理',
            'type' => Status::STATUS_RECORD,
            'sort' => 3,
            'color' => '#3498db'
        ],
        [
            'name' => '申请审核已通过，等待考试',
            'type' => Status::STATUS_RECORD,
            'sort' => 4,
            'color' => '#2980b9'
        ],
        [
            'name' => '申请未审核通过',
            'type' => Status::STATUS_RECORD,
            'sort' => 5,
            'color' => '#9b59b6'
        ],
        [
            'name' => '考试未通过',
            'type' => Status::STATUS_RECORD,
            'sort' => 6,
            'color' => '#8e44ad'
        ],
        [
            'name' => '考试通过，等待领证',
            'type' => Status::STATUS_RECORD,
            'sort' => 7,
            'color' => '#34495e'
        ],
        [
            'name' => '已领证',
            'type' => Status::STATUS_RECORD,
            'sort' => 8,
            'color' => '#2b3e50'
        ],
    ];
    public function run()
    {
            $model = new StatusRepository;

            $model->createMultiple($this->statusList);
    }
}