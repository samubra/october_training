<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-5-25
 * Time: 下午8:37
 */

namespace Samubra\Training\Models\ImportAndExport;


use Samubra\Training\Models\Train;
use Samubra\Training\Repositories\Train\CategoryRepository;
use Samubra\Training\Repositories\Train\CertificateRepository;

class ExportCertificates extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $certificates = $this->getRepository()->with(['category','organization','user'])->all();
        $certificates->each(function($certificate) use ($columns) {
            $certificate->id_num = '\''.$certificate->id_num;
            $certificate->id_type = Train::$idTypeMap[$certificate->id_type];
            $certificate->edu_type = Train::$eduTypeMap[$certificate->edu_type];
            $certificate->active = $certificate->active ? '有效':'失效';

            $certificate->category_name = $certificate->category->name;
            $certificate->organization_name = $certificate->organization->name;
            $certificate->user_name = $certificate->user->surname;

            $certificate->addVisible($columns);
            $certificate->addVisible(collect($this->getExtendColumns())->keys()->all());
        });
        return $certificates->toArray();
    }

    protected function getRepository()
    {
        return new CertificateRepository();
    }
    protected function exportExtendColumns($columns)
    {
        return parent::exportExtendColumns($columns + $this->getExtendColumns());
    }

    protected function getExtendColumns()
    {
        return [
            'category_name' => '类别名称',
            'organization_name' => '发证机构名称',
            'user_name' => '所属用户姓名',
        ];
    }
    public function getExportByCategoryOptions()
    {
        $category = new CategoryRepository;
        return $category->listsNested(['id','name'])->pluck('name','id')->toArray();
    }
}