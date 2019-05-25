<?php namespace Samubra\Training\Updates;

use Samubra\Training\Models\Train;
use Seeder;
use Samubra\Training\Models\Category;
use Samubra\Training\Repositories\Train\CategoryRepository;
use Samubra\Training\Repositories\Train\OrganizationRepository;
use Samubra\Training\Models\Route;

class categoryseeder extends Seeder
{
    
    protected $categoryList = [
        [
            'title' => '电工作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '低压电工作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '高压电工作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '焊接与热切割作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '熔化焊接与热切割作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '高处作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '高处安装、维护、拆除作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '登高架设作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '场内机动车辆驾驶作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '挖掘机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '装载机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '压路机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '推土机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '烟花爆竹安全作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '烟花爆竹储存作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ]
            ]
        ],
    ];
    public function run()
    {
        foreach ($this->categoryList as $category)
        {
            $model = $this->creteDate($category);
            if(isset($category['child']))
            {
                foreach ( $category['child'] as $item) {
                    $this->creteDate($item,$model->id);
                }
            }
        }
    }

    protected function creteDate($data,$parent_id=null)
    {
        $category = new CategoryRepository;
        $fillData = [
            'name' => $data['title'],
            'slug' => Train::generateRandomString(),
            'active' => 1,
            'num_display' => 10
        ];
        if(!is_null($parent_id))
            $fillData['parent_id']= $parent_id;
        $model = $category->create($fillData);
        $slug = $model->slug;
        $entityId = $model->id;
        $type = Route::ROUTE_CATEGORY;
        Route::saveRoutes('0', $slug, $entityId, $type);
        return $model;
    }
}