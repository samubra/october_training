<?php namespace Samubra\Training\Components;

use Log;
use Mail;
use Input;
use Event;
use OctoCart;
use Auth;
use DB;
use Redirect;
use Flash;
use Cms\Classes\ComponentBase;
use Samubra\Training\Models\Order;
use Samubra\Training\Models\Settings;
use Samubra\Training\Models\Project as ProjectModel;
use Samubra\Training\Models\ShippingMethod;
use Samubra\Training\Models\PaymentMethod;
use Samubra\Training\Models\Train;
use Samubra\Training\Models\UserAddress;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\RecordRepository;
use Samubra\Training\Repositories\Train\UserAddressesRepository;

class Checkout extends ComponentBase
{

    public $successPage;

    public $total;

    public $availableMethods;

    public $availableGateways;

    public $user = null;

    public function componentDetails()
    {
        return [
            'name'        => 'xeor.octocart::lang.checkout.name',
            'description' => 'xeor.octocart::lang.checkout.description'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRender() {
        $this->prepareVars();
    }

    protected function prepareVars()
    {
        $this->user = Auth::getUser();

        $this->total = $this->page['total'] = OctoCart::total();
        $this->availableMethods = $this->page['availableMethods'] = $this->loadAvailableMethods();
        $this->availableGateways = $this->page['availableGateways'] = $this->loadavAilableGateways();

        $this->page['userAddresses'] = $this->loadUserAddresses();

        /*
         * Page links
         */
        $this->successPage = $this->page['successPage'] = Settings::get('success_page', 'success');
    }

    protected function loadAvailableMethods()
    {
        return ShippingMethod::where('active', true)->orderBy('weight')->get();
    }

    protected function loadAvailableGateways()
    {
        return PaymentMethod::where('active', true)->orderBy('weight')->get();
    }


    public function onCheckout()
    {
        $input    = Input::all();
        $billing  = [];
        $shipping = [];
        $address = [];
        $orderEmail = null;
        $orderPhone = null;
        $shippingMethod = null;
        $paymentMethod = null;


        if (!$this->availableMethods) {
            $this->availableMethods = $this->loadAvailableMethods();
        }

        if (!$this->availableGateways) {
            $this->availableGateways = $this->loadavAilableGateways();
        }

        if(isset($input['address_id']) && !empty($input['address_id']))
        {
            $addressId = $input['address_id'];
            $addressModel = (new UserAddressesRepository())->getById($addressId);
            $address = [
                'address'       => $addressModel->full_address,
                'zip'           => $addressModel->zip,
                'contact_name'  => $addressModel->contact_name,
                'contact_phone' => $addressModel->contact_phone,
            ];
        }

        // Prepare vars
        if (isset($input['billing']) && $input['billing']['need']) {
            $billing = $input['billing'];

            if (isset($billing['email'])) {
                $orderEmail = $billing['email'];
                unset($billing['email']);
            }

            if (isset($billing['phone'])) {
                $orderPhone = $billing['phone'];
                unset($billing['phone']);
            }
        }elseif(isset($input['address_id']) && $input['billing']['need'] ){
            $orderPhone = $address['contact_phone'];
        }
        $billing = $billing + $address;

        unset($billing['need']);

        if (isset($input['shipping'])) {
            $shipping = $input['shipping'];
        }else{
            $shipping = $address;
        }



        if (isset($input['shipping_method']) && !empty($input['shipping_method'])) {
            $shippingMethodId = $input['shipping_method'];
            $shippingMethod = ShippingMethod::find($shippingMethodId);
        }

        if (isset($input['payment_method']) && !empty($input['payment_method'])) {
            $paymentMethodId = $input['payment_method'];
            $paymentMethod = PaymentMethod::find($paymentMethodId);
        }



        $items = $projects = OctoCart::get();
        if (!is_null($items)) {
            foreach ($items as $itemId => $item) {
                $project = ProjectModel::with('plan')->find($item['project']);
                $project->setUrl($this->projectDisplayPage, $this->controller);
                $projects[$itemId]['project'] = $project;
            }
        }


        $totalPrice = OctoCart::total();
        $count = OctoCart::count();



        $placeOrder = isset($input['place_order']) && !empty($input['place_order']) ? (int)$input['place_order'] : 0;

        if ($placeOrder === 0)
            //return true;
        // Get Settings
        $settings = Settings::instance();

        // Mail
        $sendUserMessage = $settings->send_user_message;

        $appName = class_exists('\\Backend\\Models\\BrandSettings') ? \Backend\Models\BrandSettings::get('app_name') : \Backend\Models\BrandSetting::get('app_name');

        // Create New OrderBack
        //status`, `no`, `user_id`, `email`, `phone`, `billing_info`, `shipping_info`, `shipping_method_id`, `shipping_total`, `shipping_tax`, `note`, `vat`, `total`, `currency`, `payment_method_id`, `transaction_id`, `date_paid`, `payment_data`, `payment_response`, `created_at`, `updated_at`, `date_completed
        $user = $this->user ? $this->user : Auth::getUser();
        $order = DB::transaction(function () use (
            $user,
            $orderEmail,
            $orderPhone,
            $billing,
            $shipping,
            $totalPrice,
            $settings,
            $shippingMethod,
            $paymentMethod,
            $address,
            $projects,
            $items
        ) {
            $order = new Order;
            $order->email = $orderEmail;
            $order->phone = $orderPhone;
            $order->user_id = $user->id;
            $order->cart_items = json_encode($items);
            $order->address = $this->prepareJSON($address);
            $order->billing_info = $this->prepareJSON($billing);
            $order->shipping_info = $this->prepareJSON($shipping);
            $order->total = $totalPrice->total;
            $order->vat = $totalPrice->vat;
            $order->currency = $settings->currency;
            $order->note = isset($input['note']) && !empty($input['note']) ? $input['note'] : NULL;
            if (!is_null($shippingMethod)) {
                $order->shipping_method_id = $shippingMethod->id;
                $order->shipping_total = $shippingMethod->price;
            }
            if (!is_null($paymentMethod)) {
                $order->payment_method_id = $paymentMethod->id;
            }
            $order->save();

            foreach ($projects as $cart){
                $record = $this->saveRecord($cart['attributes']['saveData']);
                $item = $order->items()->make([
                    'amount' => $cart['quantity'],
                    'price'  => $cart['price'],
                ]);
                $item->project()->associate($cart['project']);
                $item->record()->associate($record);
                $item->save();
            }
            Flash::success('培训申请订单提交成功！');
            return $order;
        });

        // To Customer
        if($sendUserMessage && !empty($sendUserMessage) && $orderEmail) {
            $mailData = [
                'email' => $orderEmail,
                'view' => 'samubra.training::mail.order_confirm',
                'viewData' => [
                    'name'     => '学员',
                    'site'     => $appName,
                    'order'    => $order,
                    'items'    => $projects,
                    'shipping' => $shipping,
                    'billing'  => $billing,
                    'total'    => $totalPrice->total,
                    'vat'      => $totalPrice->vat,
                    'count'    => $count,
                ]
            ];
            Mail::queue('samubra.training::mail.order_confirm', $mailData['viewData'], function ($message) use($orderEmail) {
                $message->from('samubra@live.cn', '培训管理系统');
                $message->to($orderEmail);
                return $message;
            });
        }



        // To Admins
        $adminEmails = $settings->admin_emails;
        if($adminEmails && !empty($adminEmails)) {
            $adminEmails = explode("\n", $adminEmails);
            foreach($adminEmails as $email) {
                $email = trim($email);

                Mail::queue('samubra.training::mail.order_confirm_admin', [
                    'order'    => $order,
                    'billing'  => $billing,
                    'shipping' => $shipping,
                    'site'     => $appName,
                    'items'    => $projects,
                    'total'    => $totalPrice->total,
                    'vat'      => $totalPrice->vat,
                    'count'    => $count,
                ], function ($message) use($email) {
                    $message->from('samubra@live.cn', '培训管理系统');
                    $message->to($email);
                    return $message;
                });
            }
        }

        // Clear Cartback
        $cart = OctoCart::clear();

        $result = null;

        /*
         * Extensibility
         */
        if ($event = Event::fire('samubra.training.afterOrderSave', [$order], true)) {
            $result = $event;
        }

        return $result;

    }


    protected function saveRecord($saveData)
    {
	    $this->user = $this->user ? $this->user : Auth::getUser();
        $recordModel = (new RecordRepository())->create($saveData);
        if(is_null($recordModel->certificate_id)){

            $plan = $recordModel->project->plan;
            $categoryId = $plan->category_id;
            $organizationId = $plan->organization_id;

            $certificateSaveData = [
                'id_num' => $recordModel->record_id_num,
                'id_type' => $recordModel->record_id_type,
                'name' => $recordModel->record_name,
                'edu_type' => $recordModel->record_edu_type,
                'phone' => $recordModel->record_phone,
                'address' => $recordModel->record_address,
                'company' => $recordModel->record_company,
                'category_id' => $categoryId,
                'organization_id' => $organizationId,
                'user_id' => $this->user->id,
            ];
            //trace_sql();
            $certificateAll = (new CertificateRepository())
                ->with('category')
                ->where('id_num',$certificateSaveData['id_num'])
                ->where('id_type',$certificateSaveData['id_type'])
                ->where('category_id',$categoryId)
                ->whereNull('print_date')->get();

            $certificateModel = !$certificateAll->count() ?
                (new CertificateRepository())->create($certificateSaveData) :
                $certificateAll->first()->update($certificateSaveData);
            $recordModel->certificate()->associate($certificateModel);
            $recordModel->save();
        }
        return $recordModel;
    }

    protected function loadUserAddresses()
    {
        try {
            $userAddressesRepository = new UserAddress();
            $userLists = $userAddressesRepository->where('user_id',$this->user->id)->orderBy('last_used_at',' DESC')->get();
        } catch (ModelNotFoundException $ex) {
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }
        return $userLists;
    }
    protected function prepareJSON($array)
    {
        if (!is_array($array) || empty($array))
            return;

        $json = [];

        foreach ($array as $key => $value) {
            $json[] = [
                'name' => $key,
                'value' => $value
            ];
        }

        return $json;
    }

}
