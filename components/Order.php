<?php namespace Samubra\Training\Components;

use Cms\Classes\ComponentBase;
use Samubra\Training\Models\Settings;
use Samubra\Training\Models\Order as OrderModel;
use Samubra\Training\Models\Project as ProjectModel;

class Order extends ComponentBase
{
    /**
     * @var Samubra\Training\Models\Order The order model used for display.
     */
    public $order;

    /**
     * An array of projects
     * @var Collection
     */
    public $cartItems;

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

    public function componentDetails()
    {
        return [
            'name'        => 'samubra.training::lang.order.name',
            'description' => 'samubra.training::lang.order.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'samubra.training::lang.order.id',
                'description' => 'samubra.training::lang.order.id_description',
                'default'     => '{{ :id }}',
                'type'        => 'string'
            ],
        ];
    }

    public function onRun()
    {
        $id = $this->property('id');
        if ($order = OrderModel::with('items','payment_method','shipping_method')->find($id)) {
            $order->cart_items = json_decode($order->cart_items, true);
            $this->order = $this->page['order'] = $order;
        }
        else {
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }

        $this->prepareVars();
    }

    protected function prepareVars()
    {
        /*
         * Page links
         */
        $this->projectDisplayPage = $this->page['projectDisplayPage'] = Settings::get('project_display_page', 'project');
        $this->categoryPage = $this->page['categoryPage'] = Settings::get('category_page', 'category');

        $this->cartItems = $this->page['cartItems'] = $this->listItems();
    }

    protected function listItems()
    {
        $carItems = $this->order->cart_items;

        foreach ($carItems as $itemId => $item) {
            $project = ProjectModel::find($item['project']);
            $project->setUrl($this->projectDisplayPage, $this->controller);
            $items[$itemId]['project'] = $project;
        }

        return $carItems;
    }


}
