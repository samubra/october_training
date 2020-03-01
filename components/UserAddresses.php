<?php namespace Samubra\Training\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Flash;
use Auth;
use Lang;
use Input;
use Redirect;
use RainLab\User\Models\User;
use Samubra\Training\Models\UserAddress;
use Samubra\Training\Repositories\Train\UserAddressesRepository;
use Validator;
use ValidationException;
use SystemException;
use ApplicationException;

class UserAddresses extends ComponentBase
{
    protected $loginUser;
    protected $userAddressesRepository;
    protected $userAddressModel;
    public function init()
    {
        $this->loginUser = Auth::getUser();
        $this->userAddressesRepository = new UserAddressesRepository();

    }


    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => '用户收货地址',
            'description' => '用户收货地址管理',
        ];
    }



    public function onLoadAddressForm()
    {
        if(!request()->has('address_id')){
            return [
                '#address_modal' => $this->renderPartial('pages-training/address_form',['action' => '新建'])
            ];
        }else{
            $this->loadUserAddressModel(post('address_id'));

            $responseData = [
                'address' => $this->userAddressModel,'action' => '修改'
            ];
            return [
                '#address_modal' => $this->renderPartial('pages-training/address_form',['responseData' => $responseData])
            ];
        }
    }

    public function onSaveAddress()
    {
        $postData = request()->only(['address','contact_name','zip','contact_phone']);
        list($postData['province'],$postData['city'],$postData['district']) = explode('/',post('province_city_district'));

        $rules = [
            'province'      => 'required',
            'city'          => 'required',
            'district'      => 'required',
            'address'       => 'required',
            'zip'           => 'required|numeric',
            'contact_name'  => 'required',
            'contact_phone' => 'required',
        ];
        $messages = [
            'address.required'       => '详细地址必须填写1',
            'zip.required'           => '邮政编码必须填写！',
            'contact_name.required'  => '联系人姓名必须填写！',
            'contact_phone.required' => '联系电话必须填写！',
            'numeric' => '邮政编码只能是数字'
        ];
        $validation = Validator::make($postData, $rules,$messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        $this->loadUserAddressModel(post('address_id'));
        $this->userAddressModel->province = $postData['province'];
        $this->userAddressModel->city = $postData['city'];
        $this->userAddressModel->district = $postData['district'];
        $this->userAddressModel->address = $postData['address'];
        $this->userAddressModel->zip = $postData['zip'];
        $this->userAddressModel->contact_name = $postData['contact_name'];
        $this->userAddressModel->contact_phone = $postData['contact_phone'];
        $this->userAddressModel->user_id = $this->loginUser->id;
        $this->userAddressModel->save();
        Flash::success('收货地址保存成功！');
    }

    public function onLoadDeleteAddress()
    {
        if($this->userAddressesRepository->deleteById(post('address_id')))
            Flash::success('收货地址删除成功！');
        else
            Flash::error('收货地址删除失败！');
    }

    public function onRun()
    {
        $this->addCss('assets/city-picker-master/dist/css/city-picker.css');
        $this->addJs('assets/city-picker-master/dist/js/city-picker.data.min.js');
        $this->addJs('assets/city-picker-master/dist/js/city-picker.min.js');
       // $this->addJs('assets/coustom.js');
        $this->page['addresses'] = $this->onLoadAddresses();
        $this->page['last_use_address'] = $this->onLoadLastUseAddress();
    }

    protected function loadUserAddressModel($address_id=null)
    {
        if($address_id){
            return $this->userAddressModel = $this->userAddressesRepository->where('id',$address_id)->where('user_id',$this->loginUser->id)->orderBy('last_used_at',' DESC')->first();
        }

        return $this->userAddressModel = $this->userAddressesRepository->makeModel();
    }

    protected function onLoadAddresses()
    {
        return $this->userAddressesRepository->where('user_id',$this->loginUser->id)->orderBy('last_used_at',' DESC')->get();
    }

    protected function onLoadLastUseAddress()
    {
        return $this->userAddressesRepository->where('user_id',$this->loginUser->id)->first();
    }


}

