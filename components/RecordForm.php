<?php

namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;

use Samubra\Training\Classes\CheckRecord;
use Samubra\Training\Models\Record as RecordModel;
use Samubra\Training\Models\Train;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\ProjectRepository;
use Flash;
use Samubra\Training\Repositories\Train\RecordRepository;
use Samubra\Training\Repositories\Train\UserAddressesRepository;
use Validator;
use ValidationException;
use SystemException;
use ApplicationException;
use Auth;
use Log;
use ShoppingCart;
use OctoCart;

class RecordForm extends ComponentBase
{
    protected $auth;
    protected $certificateRepository;
    protected $recordRepository;
    protected $projectRepository;
    protected $projectModel;
    protected $recordModel;
    protected $certificateModel = false;

    public function init()
    {
        $this->certificateRepository = new CertificateRepository();
        $this->projectRepository = new ProjectRepository();
        $this->recordRepository = new RecordRepository();
    }
    /**
    * @return array
    */
    public function componentDetails()
    {
        return [
            'name'        => '添加培训申请',
            'description' => '添加培训申请表单',
        ];
    }
    public function defineProperties()
    {
        return [
            'project_slug' => [
                'title'       => '培训项目缩略名',
                'description' => 'url中显示培训项目的缩略名',
                'default'     => '{{ :slug }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRender()
    {
        $this->page['project'] = $this->loadProject($this->property('project_slug'));
        $this->prepareVars();
        if( !$this->projectModel->active){
            Flash::error('该培训项目已不允许申请培训，请重新选择培训项目！');
            return redirect()->back();
        }
    }

    protected function prepareVars(){
        $this->page['is_retraining'] = $this->projectModel->plan->is_retraining;
        $this->page['previous'] = url()->previous();
        $this->page['eduOptions'] = \Samubra\Training\Models\Train::$eduTypeMap;
    }



    public function onAddRecord()
    {
        $this->loadProject();

        $this->loadCertificate();

        $recordData = [
             'record_edu_type' => post('edu_type'),
             'project_id' => post('project_id'),
             'record_phone' => post('phone'),
             'record_address' => post('address'),
             'record_company' => post('company','个体'),
        ];

            //trace_log($this->certificateModel);
            $recordData['certificate_id'] = $this->certificateModel->id;
            $recordData['record_name'] = $this->certificateModel->name;
            $recordData['record_id_type'] = $this->certificateModel->id_type;
            $recordData['record_id_num'] = $this->certificateModel->id_num;

        $recordModel = $this->saveRecord($recordData);
        $this->onAddToCart();
        //trace_log($this->auth->id);
        if(!$this->auth->addresses->count()){
            $addressRepository = new UserAddressesRepository();
            $addressModel = $addressRepository->makeModel();
                $addressModel->province = '重庆市';
            $addressModel->city = '重庆市';
            $addressModel->district = '巫溪县';
            $addressModel->address = post('address');
            $addressModel->zip = '405800';
            $addressModel->contact_name = $this->certificateModel->name;
            $addressModel->contact_phone = post('phone');
            $addressModel->user_id = Auth::getUser()->id;
            $addressModel->save();
        }

        return redirect('/carts');
        /**
        return [
            '#result' =>$this->renderPartial('pages-training/add_record_result',['record' => $this->recordModel])
        ];
         * **/
    }
    public function onAddToCart() {
        OctoCart::add($this->recordModel->id, 1,
            [
                'record_name' => $this->recordModel->record_name,
                'record_id_type' => $this->recordModel->record_id_type,
                'record_id_num' => $this->recordModel->record_id_num,
                'project_id' => $this->projectModel->id,
                'category_id' => $this->projectModel->plan->category_id,
            ]);

    }

    public function onLoadCertificatesList()
    {
        $this->projectModel  = $this->loadProject();
        $postData = post();
        $rules = [
            'identity' => 'required|identity|exists:samubra_training_certificates,id_num,category_id,'.$this->projectModel->plan->category_id,
        ];
        $messages = [
            'identity.required' => '证件号码必须填写!',
            'identity' => '身份证号码格式错误!',
            'exists' => '你所输入的证件号码没有找到符合该培训项目的证书，请重新输入新的证件号码，或重新选择相匹配的培训项目!'
        ];

        $validation = Validator::make($postData, $rules,$messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $certificates = $this->certificateRepository->where('id_num',$postData['identity'])->where('category_id',$this->projectModel->plan->category_id)->where('active',true)->get();
        return [
            '#certificateListTable' => $this->renderPartial('@certificate_list_modal',['certificates' => $certificates])
        ];
    }

    public function onLoadRecord()
    {
        $this->loadCertificate();

        if(!$this->certificateModel)
        {
            trace_log('查找ID为'.post('certificate_id').'的培训证书。');
            throw new ApplicationException('没有找到相应的培训证书，如需重新输入证件号码！');
        }
        $resultData = [
            'certificate' => $this->certificateModel,
            'eduOptions' => Train::$eduTypeMap,
            'project' => $this->loadProject()
        ];
        return [
            '#selectCertificate' => $this->renderPartial('@certificate',['certificate' => $this->certificateModel]),
            '#otherField' => $this->renderPartial('@other_field',['certificate' => $this->certificateModel,'eduOptions' => Train::$eduTypeMap,])
        ];
    }

    protected function saveRecord($data)
    {
        $recordModel = $this->recordRepository->makeModel();
        if($this->certificateModel->print_date){
            $rules = [
                'certificate_id' => ['required','record:'.$this->projectModel->id],
                ];
            $messages = [
                'record' => '当前证书不符合该培训项目的申请条件！',
            ];

        $validation = Validator::make($data, $rules,$messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }
        }

        $this->recordModel = $recordModel->create($data);

    }
    protected function loadCertificate($certificate_id = null,$certificateData = null)
    {
        $certificate_id = is_null($certificate_id) ? post('certificate_id'):$certificate_id;

            try {
                $certificateModel = $this->certificateRepository->with('category')->where('active',true)->getById($certificate_id);
            } catch (ModelNotFoundException $ex) {
                $this->setStatusCode(404);
                return $this->controller->run('404');
            }
        return $this->certificateModel = $certificateModel;
    }

    protected function loadProject($project_slug = null,$project_id = null)
    {
        $slug = is_null($project_slug) ? post('project_slug',$this->property('project_slug')) : $project_slug;
        $project_id = is_null($project_id) ? post($project_id):$project_id;
        try {
            $projectModel = $this->projectRepository->with('plan','plan.category','status','records_count','courses','courses.course','courses.teacher')->getByColumn($slug,'slug' );
        } catch (ModelNotFoundException $ex) {
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }
        if(!$projectModel)
        {
            trace_log('缩略名为：'.$slug .'的培训项目没找到！');
            throw new ApplicationException('没找到对应的培训项目！');
        }
        return $this->projectModel = $projectModel;
    }


    protected function loadUser($data)
    {
        $identity = Str::snake(trim($data['identity']));
        if(Auth::check()){
            $this->auth = Auth::getUser();
        }elseif(request()->has('password') && $this->projectModel->plan->is_retraining){
            $this->auth = Auth::authenticate([
                'login' => $identity,
                'password' => post('password')
            ]);
        }elseif(!$this->projectModel->plan->is_retraining && request()->has('name')){
            if(!$this->auth = Auth::findUserByLogin($identity)){
                $this->auth = Auth::register([
                    'email' => $identity . '@tiikoo.cn',
                    'password' => substr($identity,-8),
                    'password_confirmation' => substr($identity,-8),
                    'name' => $data['name'] ,
                    'username' => $identity ,
                    'identity' => $identity ,
                    'phone' => $data['phone'],
                    'company' => $data['company'],
                ],true);
                Auth::login($this->auth);
            }

        }else{
            //注册guest账户
            $this->auth = Auth::registerGuest(['email' => 'guest@tiikoo.cn'
            ]);
        }

        return $this;
    }

}
