<?php namespace Samubra\Training\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Lang;
use Input;
use Redirect;
use Lovata\Buddies\Facades\AuthHelper;
use Kharanenka\Helper\Result;
use Samubra\Training\Repositories\Train\CertificateRepository;

class RelateCertificate extends ComponentBase
{
    protected $loginUser;
    public function init()
    {
        $this->loginUser = AuthHelper::getUser();
    }
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => '关联证书',
            'description' => '用户关联与证书',
        ];
    }

    public function defineProperties()
    {
        return [
            'redirectPage' => [
                'title' => '关联后定向页面',
                'type' => 'dropdown'
            ]
        ];
    }

    public function getRedirectPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Auth user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function onRun()
    {
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

