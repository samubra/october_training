<?php

namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Samubra\Training\Classes\CheckRecord;
use Samubra\Training\Models\Settings;
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
    protected $projectModel = null;
    protected $recordModel;
    protected $certificateModel = false;

    protected $redirectUrl;

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

    /**
     * 获取
     */
    protected function prepareVars(){
        $this->redirectUrl = Settings::get('redirect_user_after_add_to_cart', 'carts');
        $this->page['is_retraining'] = $this->projectModel->plan->is_retraining;
        $this->page['previous'] = url()->previous();
        $this->page['eduOptions'] = \Samubra\Training\Models\Train::$eduTypeMap;
    }


    /**
     * 将数据添加到购物车，并返回到购物车页面
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function onAddToCart() {
        $postData = $this->getPostRecordData();
        $this->validRecordData($postData);

        $this->loadUser($postData);

        $cartData['saveData']=$postData;

        $cartData['attributesShow'] = [
            "record_name" => ['label' => "姓名" , 'value' => $postData['record_name']],
            "record_id_type" => ['label' => "证件类型" , 'value' => Train::$idTypeMap[$postData['record_id_type']]],
            "record_id_num" => ['label' => "证件号码" , 'value' => $postData['record_id_num']],
           // "certificate_id" => ['label' => "培训证书" , 'value' => $this->certificateRepository->with('category')->getById($postData['certificate_id'])->category->name],
            "record_edu_type" => ['label' => "文化程度" , 'value' => Train::$eduTypeMap[$postData['record_edu_type']]],
            "record_phone" => ['label' => "联系电话" , 'value' => $postData['record_phone'] ],
            "record_address" => ['label' => "联系地址" , 'value' => $postData['record_address']],
            "record_company" => ['label' => "单位名称" , 'value' => $postData['record_company'] ],
        ];

        if(isset($postData['certificate_id']))
            $cartData['attributesShow']["certificate_id"] = ['label' => "培训证书" , 'value' => $this->certificateRepository->with('category')->getById($postData['certificate_id'])->category->name];

        if(!(new UserAddressesRepository())->where('user_id',$this->auth->id)->get()->count()){
            $address = (new UserAddressesRepository())->create([
                'user_id' => $this->auth->id,
                'province' => '重庆市',
                'city' => '重庆市',
                'district' => '巫溪县',
                'address' => $postData['record_address'],
                'zip' => '405800',
                'contact_name' => $postData['record_name'],
                'contact_phone' => $postData['record_phone']
            ]);
        }
        OctoCart::add($postData['project_id'], 1,null,null,$cartData);

        return redirect(Settings::get('redirect_user_after_add_to_cart', 'carts'));
    }


    /**
     * 根据提供的身份证号码获取其所具有的培训证书
     * @return array
     */
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


    /**
     * 获取选中的培训证书作为培训申请中certificate_id
     * @return array
     */
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


    /**
     * 获取提交上来的数据，并进行格式化
     * @return array
     */
    protected function getPostRecordData()
    {
        $recordData = [
            'record_edu_type' => post('edu_type'),
            'project_id' => post('project_id'),
            'record_phone' => post('phone'),
            'record_address' => post('address'),
            'record_company' => post('company','个体'),
        ];

        if(request()->has('certificate_id'))
            $recordData['certificate_id'] = post('certificate_id',null);
        $recordData['record_name'] = post('name');
        $recordData['record_id_type'] = Train::ID_TYPE_IDENTITY;
        $recordData['record_id_num'] = post('identity');

        return $recordData;
    }

    /**
     * 验证提交的培训申请数据
     * @param $data
     */
    protected function validRecordData($data)
    {
        $rules = [
            'record_edu_type' => 'required' ,
            'record_name' => 'required' ,
            'record_phone' => 'required|phone:CN' ,
            'record_address' => 'required',
            'record_company' => 'required',
            'agree' => 'required'
        ];
        if(is_null($this->projectModel))
            $this->loadProject();

        $messages = [
            'record' => '当前证书不符合该培训项目的申请条件！',
            'record_id_num.unique' => '该身份证号码已经在该培训项目中申请过培训！',
            'agree.required' => '请先查看并《培训申请须知》',
            'record_edu_type.required' => '文化程度必须填写！',
            'record_name.required' => '姓名必须填写！',
            'record_phone.required' => '电话号码必须填写！',
            'record_address.required' => '联系地址必须填写！',
            'record_company.required' => '单位名称必须填写，如没有工作单位请填“个体”！',
        ];

        if($this->projectModel->plan->is_certificate && isset($data['certificate_id']))
        {
            $rules['certificate_id'] = ['required','record:'.$data['project_id']];
        }
        $rules['record_id_num'] = ['required','identity'];

        $rules['record_id_num'][] = Rule::unique('samubra_training_records', 'record_id_num')->where('project_id', $data['project_id']);


        $data['agree'] = post('agree');
        $validation = Validator::make($data, $rules,$messages);
        //trace_log($validation);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
    }

    /**
     *
     * 获取单个培训证书
     * @param null $certificate_id
     * @param null $certificateData
     * @return \Cms\Classes\BaseResponse
     */
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

    /**
     * 获取培训项目模型
     * @param null $project_slug
     * @param null $project_id
     * @return \Cms\Classes\BaseResponse
     */
    protected function loadProject($project_slug = null,$project_id = null)
    {

        try {
            $slug = is_null($project_slug) ? post('project_slug',$this->property('project_slug')) : $project_slug;

            if($slug){
                $projectModel = $this->projectRepository->with('plan','plan.category','status','records_count','courses','courses.course','courses.teacher')->getByColumn($slug,'slug' );
            }else{
                $project_id = is_null($project_id) ? post($project_id):$project_id;
                $projectModel = $this->projectRepository->with('plan','plan.category','status','records_count','courses','courses.course','courses.teacher')->getById($project_id );
            }

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


    /**
     * 获取登录账户，如果未登录，则用该账户注册或登录
     * @param $data
     * @return $this
     */
    protected function loadUser($data)
    {
        $identity = Str::snake(trim($data['record_id_num']));
        if(Auth::check()){
            $this->auth = Auth::getUser();
        }elseif($user = Auth::findUserByLogin($identity)){
            $this->auth = Auth::login($user);
        }else{
            $this->auth = Auth::register([
                'email' => $identity . '@tiikoo.cn',
                'password' => substr($identity,-8),
                'password_confirmation' => substr($identity,-8),
                'name' => $data['record_name'] ,
                'username' => $identity ,
                'identity' => $identity ,
                'phone' => $data['record_phone'],
                'company' => $data['record_company'],
            ],true);
        }

        return $this->auth;
    }

}
