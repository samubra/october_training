<?php


namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;

use Samubra\Training\Classes\CheckRecord;
use Samubra\Training\Models\Train;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\ProjectRepository;
use Flash;
use Samubra\Training\Repositories\Train\RecordRepository;
use Validator;
use ValidationException;
use SystemException;
use ApplicationException;
use Cart;
use Auth;
use Log;

class AddRecord extends ComponentBase
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
            ],
            'check_auth' => [
                'title'       => '检查登录状态',
                'description' => '检查当前是否有用户登录',
                'default'     => '1',
                'type'        => 'checkbox ',
            ]
        ];
    }

    public function onRun()
    {
        $this->projectModel = $this->loadProject();
        /**如果没有登录用户且培训项目为复训，则需要输入密码
        if(Auth::guest() && $this->projectModel->is_retraining)
            $this->page['check_password'] = true;
        else
            $this->page['check_password'] = false;
        //检查是否需要检测，如果需要检测，则检测培训项目是否允许申请**/
        if( !$this->projectModel->active){
            Flash::error('该培训项目已不允许申请培训，请重新选择培训项目！');
            return redirect()->back();
        }
    }

    public function onAddRecord()
    {

        $this->loadCertificate();
        $this->loadProject();


        $recordData = [
             'record_edu_type' => post('edu_type'),
             'project_id' => post('project_id'),
             'record_phone' => post('phone'),
             'record_address' => post('address'),
             'record_company' => post('company','个体'),
        ];

        if ($this->certificateModel){
            $recordData['certificate_id'] = post('certificate_id');
            $recordData['record_name'] = $this->certificateModel->name;
            $recordData['record_id_type'] = $this->certificateModel->id_type;
            $recordData['record_id_num'] = $this->certificateModel->id_num;
        }else{
            $recordData['record_name'] = post('name');
            $recordData['record_id_type'] = Train::ID_TYPE_IDENTITY;
            $recordData['record_id_num'] = post('identity');
        }
        $this->saveRecord($recordData);

        Cart::add([
            'id' => $this->recordModel->id,
            'name' => $this->recordModel->record_name . '申请' . $this->projectModel->title.'培训',
            'qty' => 1,
            'price' => $this->projectModel->cost
        ]);
        //trace_log(Cart::total());

        return [];
    }
    public function onLoadCertificatesList()
    {
        //trace_sql();
        $this->projectModel  = $this->loadProject();
        $postData = post();
        $rules = [
            'identity' => 'required|identity|exists:samubra_training_certificates,id_num,category_id,'.$this->projectModel->plan->category_id,
            'agree' => 'required'
        ];
        $messages = [
            'identity.required' => '证件号码必须填写!',
            'identity' => '身份证号码格式错误!',
            'agree.required' => '必须同意《培训申请须知》,请勾选！',
            'exists' => '你所输入的证件号码没有找到符合该培训项目的证书，请重新输入新的证件号码，或重新选择相匹配的培训项目!'
        ];

        if(request()->has('password')){
            $rules['password'] = 'required|between:4,255';
            $messages['password.between'] = '密码长度必须在4至255之间！';
            $messages['password.required'] = '密码必须填写！';
        }


        $validation = Validator::make($postData, $rules,$messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $this->loadUser();

        $certificates = $this->certificateRepository->where('id_num',$postData['identity'])->where('category_id',$this->projectModel->plan->category_id)->get();

        if(!$this->auth->certificates_count)
            throw new ApplicationException('没有找到'.$postData['identity'].'的培训证书，如需重新输入证件号码，请刷新页面重试！');

        return [
            '#retraining_form' => $this->renderPartial('pages-training/certificate_list_modal',['certificates' => $certificates])
        ];
    }

    public function onLoadRecordForm()
    {
        $this->loadCertificate();

        if(!$this->certificateModel)
        {
            trace_log(post('查找ID为'.'certificate_id').'的培训证书。');
            throw new ApplicationException('没有找到相应的培训证书，如需重新输入证件号码，请刷新页面重试！');
        }
        $resultData = [
            'certificate' => $this->certificateModel,
            'eduOptions' => Train::$eduTypeMap,
            'project' => $this->loadProject()
        ];
        return [
            '#result' => $this->renderPartial('pages-training/record_form',['result' => $resultData])
        ];
    }

    protected function saveRecord($data)
    {

        $recordModel = $this->recordRepository->makeModel();
        if($this->certificateModel){
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
    protected function loadCertificate($certificate_id = null)
    {
        $certificate_id = is_null($certificate_id) ? post('certificate_id'):$certificate_id;
        if($certificate_id){
            try {
                $certificateModel = $this->certificateRepository->with('category')->getById($certificate_id);
            } catch (ModelNotFoundException $ex) {
                $this->setStatusCode(404);
                return $this->controller->run('404');
            }
            return $this->certificateModel = $certificateModel;
        }
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


    protected function loadUser()
    {
        $identity = Str::snake(trim(post('identity')));
        if(Auth::check()){
            $this->auth = Auth::getUser();
        }elseif(request()->has('password') && $this->projectModel->plan->is_retraining){
            $this->auth = Auth::authenticate([
                'login' => post('identity'),
                'password' => post('password')
            ]);
        }elseif(!$this->projectModel->plan->is_retraining && request()->has('name')){
            $this->auth = Auth::register([
                'email' => $identity . '@tiikoo.cn',
                'password' => substr($identity,-8),
                'password_confirmation' => substr($identity,-8),
                'name' => post('name') ,
                'username' => $identity ,
                'identity' => $identity ,
                'phone' => post('phone') ,
                'company' => post('company'),
            ],true);
        }else{
            //注册guest账户
            $this->auth = Auth::registerGuest(['email' => 'guest@tiikoo.cn'
            ]);
        }

        return $this;
    }
}
