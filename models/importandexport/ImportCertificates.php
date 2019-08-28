<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-5-25
 * Time: 下午8:35
 */

namespace Samubra\Training\Models\ImportAndExport;


use RainLab\User\Facades\Auth;
use Samubra\Training\Repositories\Train\CertificateRepository;

class ImportCertificates extends \Backend\Models\ImportModel
{

    protected $certificate = [];
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'id_num' => 'required|identity',
        'id_type' => 'required',
        'name' => 'required',
        'phone' => 'required|phone:CN',
        'edu_type' => 'required',
        'category_id' => 'required',
        'organization_id' => 'required',
        'first_get_date' => 'required|date',
        'print_date' => 'required|date|after_or_equal:first_get_date',
        'review_date' => 'date|after:print_date',
        'invalid_date' => 'date|after:review_date',
        'active' => 'boolean|required'
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $certificate){
            try {
                trace_sql();

                switch ($this->create_or_update) {
                    case 'create':
                        unset($certificate['id']);
                        $this->certificate = $certificate;
                        $importResult = $this->createImport();
                        break;
                    case 'update':
                        $this->certificate = $certificate;
                        $importResult = $this->updateImport();
                        break;
                    default://auto
                        $this->certificate = $certificate;
                        if (isset($certificate['id']))
                            $importResult = $this->updateImport();
                        else
                            $importResult = $this->createImport();
                }
                $this->logMessage($row,$importResult);
                 $this->certificate = [];
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }

    protected function logMessage($row,$message)
    {
        switch ($message['status']) {
            case 'skip':
                $this->logSkipped($row, $message['message']);
                break;
            case 'warning':
                $this->logWarning($row, $message['message']);
                break;
            default:
                if (isset($message['message']))
                    $this->logUpdated();
                else
                    $this->logCreated();
        }
    }
    protected function createImport()
    {
        $this->certificate['id_num'] = substr($this->certificate['id_num'] , 0 , 1) == "'" ? substr($this->certificate['id_num'] , 1 , 18) : $this->certificate['id_num'];

        $this->certificate['user_id'] = $this->getUserId(['id_num' => $this->certificate['id_num'],'name' => $this->certificate['name']]);

        if($this->getRepository()->where('id_num',$this->certificate['id_num'])->where('id_type',$this->certificate['id_type'])->where('category_id',$this->certificate['category_id'])->get()->count())
        {
            return ['status' => 'skip','message' => '当前数据已存在'];
        }
        if($this->getRepository()->create($this->certificate))
            return ['status' => 'success'];
        else
            return ['status' => 'warning','message' => '数据添加失败！'];
    }

    protected function updateImport()
    {
        unset($this->certificate['name']);
        unset($this->certificate['id_num']);
        unset($this->certificate['category_id']);
        $certificateId = isset($this->certificate['id']) ? $this->certificate['id'] : false;
        unset($this->certificate['id']);
        if($certificateId && $this->getRepository()->updateById($certificateId,$this->certificate)){
            return ['status' => 'success','message' => '数据更新成功！'];
        }elseif (!$certificateId){
            return ['status' => 'skip','message' => '当前数据不包含主键ID！'];
        }else{
            return ['status' => 'warning','message' => '数据更新失败！'];
        }
    }
    protected function getRepository()
    {
        return new CertificateRepository();
    }

    protected function getUserId($userData)
    {
        if(!$user = Auth::findUserByLogin($userData['id_num'].'@tiikoo.cn')){
            $user = Auth::register([
                'name' => $userData['name'],
                'email' => $userData['id_num'].'@tiikoo.cn',
                'password' => substr($userData['id_num'], -8),
                'password_confirmation' => substr($userData['id_num'], -8),
            ],true);
        }
        return $user->id;

    }

    public function getCreateOrUpdateOptions()
    {
        return [
            'create' => '创建新数据',
            'update' => '只更新数据',
            'auto' => '自动识别',
        ];
    }

}