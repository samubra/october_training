<?php namespace Samubra\Training\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Flash;
use Auth;
use Lang;
use Input;
use Redirect;
use RainLab\User\Models\User;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\RecordRepository;
use Validator;
use ValidationException;
use SystemException;
use ApplicationException;

class UserCertificates extends ComponentBase
{
    protected $loginUser;
    protected $certificateRepository;
    protected $recordRepository;
    public function init()
    {
        $this->loginUser = Auth::getUser();
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
        $userModel = false;
        if(Auth::check()){
            $userModel = User::with('certificates','certificates.category')->find($this->loginUser->id);

            if($userModel->certificates->count())
                $certificates = $userModel->certificates;
        }else{
            return redirect('/login');
        }

        if(request()->has(['partial','select'])){
            $partial = post('partial','pages-training/certificates_list');
            $select = post('select','#account');
            return [
                $select => $this->renderPartial($partial,['certificates'=> $certificates,'userModel' => $userModel])
            ];
        }else{
            $this->page['certificates'] = $certificates;
            $this->page['userModel'] = $userModel;

        }

    }

    public function onLoadRecords()
    {
        $certificate_id = post('certificate_id');
        $result = [];

        $result['certificateModel'] = $this->certificateRepository->with('category','records','records_count','records.project','records.status')->getById($certificate_id);
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


    public function onLoadUserData()
    {
        //$userModel = User::find($this->loginUser->id);
        $partial = post('partial','pages-auth/update_user');
        $select = post('select','#account');
        return [
            $select => $this->renderPartial($partial,['userModel' => $this->loginUser])
        ];
    }
    public function onLoadUserDataForChangePass()
    {
        //$userModel = User::find($this->loginUser->id);
        $partial = post('partial','pages-auth/change_password');
        $select = post('select','#account');
        return [
            $select => $this->renderPartial($partial,['userModel' => $this->loginUser])
        ];
    }

    public function onRelateCertificates()
    {
        if(Auth::check() && request()->has('identity')) {
            $userModel = User::with('certificates', 'certificates.category')->find($this->loginUser->id);
            $userModel->identity = post('identity');
            $userModel->save();
            $this->certificateRepository->relateCertificates($userModel);
            return redirect('/account');
        }
    }

    public function onRun()
    {
        if(!request()->has(['partial','select']))
            $this->onLoadCertificates();

    }


}

