<?php namespace Samubra\Training\Components;

use Log;
use Input;
use Session;
use OctoCart;
use Cms\Classes\ComponentBase;
use Samubra\Training\Models\Settings;
use Samubra\Training\Models\Project as ProectModel;

class Cart extends ComponentBase
{

    /**
     * An array of projects.
     * @var Collection
     */
    public $items;

    /**
     * Message to display when there are no projects.
     * @var string
     */
    public $noCartsMessage;

    /**
     * Reference to the page name for linking to projects.
     * @var string
     */
    public $projectDisplayPage;

    /**
     * Reference to the page name for linking to categories.
     * @var string
     */
    public $categoryPage;

    /**
     * Reference to the page name for linking to cart.
     * @var string
     */
    public $cartPage;

    /**
     * Reference to the page name for linking to checkout.
     * @var string
     */
    public $checkoutPage;

    /**
     * The price total.
     * @var float
     */
    public $totalPrice;

    /**
     * The number of items in the cart.
     * @var integer
     */
    public $count;

    /**
     * An array of projects that you promote in the cart, based on the current project.
     * @var Collection
     */
    public $crossSells;

    public function componentDetails()
    {
        return [
            'name'        => 'samubra.training::lang.cart.name',
            'description' => 'samubra.training::lang.cart.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'noCartsMessage' => [
                'title'        => 'samubra.training::lang.cart.no_projects',
                'description'  => 'samubra.training::lang.cart.no_projects_description',
                'type'         => 'string',
                'default'      => '你的购物车是空的',
                'showExternalParam' => false
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->items = $this->page['items'] = $this->listItems();
    }

    protected function prepareVars()
    {

        $this->noCartsMessage = $this->page['noCartsMessage'] = $this->property('noCartsMessage');

        $this->totalPrice = $this->page['totalPrice'] = OctoCart::total();
        $this->count = $this->page['count'] = OctoCart::count();

        /*
         * Page links
         */
        $this->cartPage = $this->page['cartPage'] = Settings::get('cart_page', 'cart');
        $this->checkoutPage = $this->page['checkoutPage'] = Settings::get('checkout_page', 'checkout');
        $this->projectDisplayPage = $this->page['projectDisplayPage'] = Settings::get('project_display_page', 'project');
        $this->categoryPage = $this->page['categoryPage'] = Settings::get('category_page', 'category');
    }

    protected function listItems()
    {
        $items = OctoCart::get();
        if (!is_null($items)) {
            foreach ($items as $itemId => $item) {
                $project = ProectModel::with('plan','plan.category')->find($item['project']);
                $project->setUrl($this->projectDisplayPage, $this->controller);
                $items[$itemId]['project'] = $project;
            }
        }
        return $items;
    }

    public function onUpdateQuantity() {
        $params = Input::all();
        if (isset($params['itemId']) && isset($params['quantity']) && is_numeric($params['quantity'])) {
            $itemId = $params['itemId'];
            $quantity = $params['quantity'];
            $cart = OctoCart::update($itemId, $quantity);
        }
    }

    public function onRemoveProduct() {
        $params = Input::all();
        if (isset($params['itemId'])) {
            $itemId = $params['itemId'];
            $cart = OctoCart::remove($itemId);
        }
    }

    public function onClear() {
        $cart = OctoCart::clear();
    }

}
