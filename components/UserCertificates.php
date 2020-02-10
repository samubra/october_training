<?php namespace Samubra\Training\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Lang;
use Input;
use Redirect;
use Lovata\Buddies\Facades\AuthHelper;
use Lovata\Buddies\Models\User;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\RecordRepository;

class UserCertificates extends ComponentBase
{
    protected $loginUser;
    protected $certificateRepository;
    protected $recordRepository;
    public function init()
    {
        $this->loginUser = AuthHelper::getUser();
        $this->certificateRepository = new CertificateRepository();
        $this->recordRepository = new RecordRepository();
    }
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => '用户证书',
            'description' => '用户证书相关操作',
        ];
    }

    public function onLoadCertificates()
    {
        $certificates = false;
        if(AuthHelper::check()){
            $userModel = User::with('certificates','certificates.category')->find($this->loginUser->id);

            if($userModel->certificates->count())
                $certificates = $userModel->certificates;
        }

        if(request()->has(['partial','select'])){
            $partial = post('partial','pages-training/certificates_list');
            $select = post('select','#account');
            return [
                $select => $this->renderPartial($partial,['certificates'=> $certificates])
            ];
        }else{
            $this->page['certificates'] = $certificates;
        }

    }

    public function onLoadRecords()
    {
        $certificate_id = post('certificate_id');
        $result = [];

        $result['certificateModel'] = $this->certificateRepository->with('category','records','records.project','records.status')->getById($certificate_id);
        $result['records'] = $result['certificateModel']->records->count() ? $result['certificateModel']->records:false;

        if(request()->has(['partial','select'])){
            $partial = post('partial','pages-training/record_lists');
            $select = post('select','#account');
            return [
                $select => $this->renderPartial($partial,$result)
            ];
        }else{
            foreach ($result as $key=>$item){
                $this->page[$key] = $item;
            }
        }
    }

    protected function renderResult($result,$partial = null,$select = null)
    {
        if(!is_null($partial) && !is_null($select)){
            return [
                $select => $this->renderPartial($partial,$result)
            ];
        }else{
            foreach ($result as $key => $item) {
                $this->page[$key] = $item;
            }
        }
    }

    /**
     * Auth user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function onRun()
    {
        if(!request()->has(['partial','select']))
            $this->onLoadCertificates();

        $inputData = Input::get(['identity']);
        if (empty($arUserData)) {
            return null;
        }
        var_dump($inputData);

        $certificateRepository = new CertificateRepository();
        $certificateModels = $certificateRepository->getByColumn($inputData , 'identity');

        if($certificateModels->count()){
            foreach ($certificateModels as $certificate){
                $certificate->user_id = $this->loginUser->id;
                $certificate->save();
            }
        }

        $sRedirectPage = $this->property('redirectPage');
        if (empty($sRedirectPage)) {
            return Redirect::to('/');
        }

        $sRedirectURL = Page::url($sRedirectPage);

        return Redirect::to($sRedirectURL);
    }

}

