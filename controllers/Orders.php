<?php namespace Samubra\Training\Controllers;

use Flash;
use Config;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use Samubra\Training\Models\Order as OrderModel;

/**
 * Orders Back-end Controller
 */
class Orders extends Controller
{

    public $implement = [
      'Backend.Behaviors.FormController',
      'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Samubra.Training', 'orders', 'orders');
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $orderId) {
                if (!$order = OrderModel::find($orderId)) {
                    continue;
                }

                $order->delete();
            }

            Flash::success('这些订单已成功删除.');
        }

        return $this->listRefresh();
    }

    public function index()
    {
        $this->addCss('/plugins/samubra/training/assets/css/xeor.octocart.css');
        $this->addJs('/plugins/samubra/training/assets/js/xeor.octocart.js');
        $this->asExtension('ListController')->index();
    }

    public function update($recordId = null)
    {
        $this->addCss('/plugins/samubra/training/assets/css/xeor.octocart.css');
        $this->addJs('/plugins/samubra/training/assets/js/xeor.octocart.js');

        return $this->asExtension('FormController')->update($recordId);
    }

    public function index_onEdit()
    {
        $id = post('id');
        return $this->redirectTo('orders/update/' . $id);
    }

    public function index_onRemove()
    {
        $id = post('id');
        if ($order = OrderModel::find($id)) {
            $order->delete();
        }
        return $this->redirectTo('orders');
    }

    public function index_onMarkStatus()
    {
        $id = post('id');
        $status = post('status');
        if ($order = OrderModel::find($id)) {
            $order->status = $status;
            $order->save();
        }
        return $this->redirectTo('orders');
    }

    protected function redirectTo($url)
    {
        $redirectTo = Config::get('cms.backendUri', 'backend');
        $redirectTo .= '/samubra/training/' . $url;
        return Redirect::to($redirectTo);
    }

}
