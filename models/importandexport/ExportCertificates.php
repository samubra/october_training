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
    protected $appends = ['export_type','export_by_category','export_by_first_get_date_start','export_by_first_get_date_end','export_by_print_date_start','export_by_print_date_end','export_by_company','export_by_is_valid'];
    protected $fillable = [
        'export_type','export_by_category','export_by_first_get_date_start','export_by_first_get_date_end','export_by_print_date_start','export_by_print_date_end','export_by_company','export_by_is_valid'
    ];
    public function exportData($columns, $sessionKey = null)
    {
        $certificates = $this->getRepository()->makeModel();
        $certificates = $certificates->export($this->setConditions())->with(['category','organization','user'])->get();
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

    protected function setConditions()
    {
        $condition = [];
        $export_type = $this->export_type;
        if(in_array($export_type,['auto','by_category']) && $this->export_by_category)
            $condition['category'] = $this->export_by_category;

        if(in_array($export_type,['auto','by_first_get_date']) && $this->export_by_first_get_date_start)
            $condition['firstGetDate']['start'] = $this->export_by_first_get_date_start;
        if(in_array($export_type,['auto','by_first_get_date']) && $this->export_by_first_get_date_end)
            $condition['firstGetDate']['end'] = $this->export_by_first_get_date_end;

        if(in_array($export_type,['auto','by_print_date']) && $this->export_by_print_date_start)
            $condition['printDate']['start'] = $this->export_by_print_date_start;
        if(in_array($export_type,['auto','by_print_date']) && $this->export_by_print_date_end)
            $condition['printDate']['end'] = $this->export_by_print_date_end;

        if(in_array($export_type,['auto','by_is_valid']))
            $condition['active'] = $this->export_by_is_valid ? '1' : '0';

        if(in_array($export_type,['auto','by_company']) && $this->export_by_company)
            $condition['company'] = '%'.$this->export_by_company.'%';
        return $condition;
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
        $category = new CategoryRepository();
        return $category->getTree('name','id','---');
    }
}