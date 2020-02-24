<?php namespace Samubra\Training\Classes;

/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-7-22
 * Time: 下午3:05
 *
 * 检查证书和培训项目是否符合
 */

use October\Rain\Exception\ApplicationException;
use Carbon\Carbon;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\ProjectRepository;

class CheckAddRecord
{
    protected $certificateModel;
    protected $projectModel;
    protected $certificateRepository;
    protected $projectRepository;

    protected $certificatePrintDate;

    protected $filter = [];

    public function __construct()
    {
       $this->certificateRepository = new CertificateRepository();
       $this->projectRepository = new ProjectRepository();
    }

    public function setProjectModel($project_id)
    {
        $this->projectRepository->with('plan')->getById($project_id);
        return $this;
    }
    public function setCertificateModel($certificate_id)
    {
        $this->certificateModel = $this->certificateRepository->getById($certificate_id);
        return $this;
    }

    /**
     * 执行最后的检查
     * @return bool
     */
    public function check()
    {
        return $this->checkType() && $this->projectModel->active && $this->checkCertificateByProjectFilterDate();
    }

    /**
     * 检查当前证书是否符合培训项目的所设定的时间段
     * @return bool
     */
    public function checkCertificateByProjectFilterDate()
    {
        //初始化printDate
        $this->getCertificatePrintDate();

        $this->getProjectFilterData();

        $can_apply_in = false;
        foreach ($this->filter as $filter)
        {
            if($can_apply_in = $this->certificatePrintDate->between($filter['start'],$filter['end']))
                return $can_apply_in && $this->certificateModel->active == $filter['is_valid'];
        }
        return $can_apply_in;
    }

    /**
     * 判断证书是复训
     * @return bool
     */
    public function certificateIsReview()
    {
        return ($this->certificateModel->active && !is_null($this->certificateModel->prin_date));
    }
    /**
     * 判断证书是否新训
     * @return bool
     */
    public function certificateIsNew()
    {
        return (!$this->certificateModel->active && is_null($this->certificateModel->prin_date));
    }
    /**
     * 判断培训项目是复训
     * @return bool
     */
    public function projectIsNotNew()
    {
        return $this->projectModel->plan->is_retraining;
    }

    /**
     *判断培训项目是新训
     * @return bool
     */
    public function projectIsNew()
    {
        return !$this->projectIsNotNew();
    }

    /**
     * 检查培训项目和证书的类别是否一致
     * @return bool
     */
    public function checkType()
    {
        return $this->certificateModel->category_id === $this->projectModel->plan->category_id;
    }



    protected function getProjectFilterData()
    {
        $filter = [];
        if(count($this->projectModel->condition)){
            foreach ($this->projectModel->condition as $data) {
                $filter[] = [
                    'start' => Carbon::createFromFormat('Y-m-d', substr($data['print_date_start'],0,-9)),
                    'end' => Carbon::createFromFormat('Y-m-d', substr($data['print_date_end'],0,-9)),
                    'is_valid' => (bool)$data['is_valid']
                ];
            }
        }
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCertificatePrintDate()
    {
        if(!is_null($this->certificateModel->print_date))
            $this->certificatePrintDate = Carbon::createFromFormat('Y-m-d',$this->certificateModel->print_date);
        return $this;
    }

    /**
     * @return static
     */
    protected function getNow()
    {
        return Carbon::now();
    }


}
