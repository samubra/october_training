<?php namespace Samubra\Training\Updates;

use Samubra\Training\Models\Train;
use Seeder;
use Samubra\Training\Repositories\Train\OrganizationRepository;

class organizationsseeder extends Seeder
{
    
    protected $organizationList = [
        [
            'name' => '重庆市安全生产监督管理局',
            'complete_type' => Train::COMPLETE_TYPE_OPERATIONS_CERTIFICATE
        ],
        [
            'name' => '巫溪县安全生产监督管理局',
            'complete_type' => Train::COMPLETE_TYPE_TRAINING_CERTIFICATE
        ],
        [
            'name' => '重庆市巫溪县职业教育中心',
            'complete_type' => Train::COMPLETE_TYPE_GRADUATION
        ],
    ];
    public function run()
    {
            $model = new OrganizationRepository;

            $model->createMultiple($this->organizationList);
    }
}