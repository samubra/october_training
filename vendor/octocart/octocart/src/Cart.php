<?php namespace OctoCart\OctoCart;

use Session;
use Samubra\Training\Models\Project as ProjectModel;
use Samubra\Training\Models\Settings;

class Cart {

    protected $sessionKey = 'octocart';

    public function add($projectId, $quantity = 1,$price = 0, $type=null,array $attributes = [])
    {
        $cart = $this->getCart();
        $projectId = (int) $projectId;
        $quantity = (int) $quantity;
        if ($projectId > 0 && $quantity > 0) {
            $itemId = $this->generateItemId($projectId, $attributes);
            // If a project is added more times, just update the quantity.
            if ($this->hasItemId($itemId)) {
                // Clicked 2 times on  to cart button. Increment quantity.
                $cart[$itemId]['quantity'] += $quantity;
            }
            else {
                $cart[$itemId]['project'] = $projectId;
                $cart[$itemId]['quantity'] = $quantity;
                if(is_null($type)){
                    $type = ProjectModel::class;
                }

                if(is_null($price) && $type == ProjectModel::class){
                    $project = (new $type)->with('plan','plan.category')->find($projectId);
                    $cart[$itemId]['price'] = $project->cost;
                }else{
                    $cart[$itemId]['price'] = $price;
                }
                $cart[$itemId]['attributes'] = $attributes;
            }
        }
        return $this->updateCart($cart);
    }

    public function remove($itemId)
    {
        $cart = $this->getCart();
        if ($this->hasItemId($itemId)) {
            unset($cart[$itemId]);
        }
        return $this->updateCart($cart);
    }

    public function update($itemId, $quantity = 1)
    {
        $cart = $this->getCart();
        if ($this->hasItemId($itemId) && $quantity > 0) {
            $cart[$itemId]['quantity'] = $quantity;
        }
        return $this->updateCart($cart);
    }

    public function clear() {
        Session::forget($this->sessionKey);
        return array();
    }

    public function get($itemId = NULL)
    {
        $cart = $this->getCart();
        if (isset($cart[$itemId])) {
            return (isset($cart[$itemId])) ? $cart[$itemId] : NULL;
        }
        else {
            return (empty($cart)) ? NULL : $cart;
        }
    }

    public function total()
    {
        // Building the return array.
        $return = array(
            'price' => 0,
            'vat' => 0,
            'total' => 0,
        );
        $cart = $this->getCart();
        if (empty($cart)) {
            return (object) $return;
        }

        $total_price = 0;
        foreach ($cart as $itemId => $item) {
            if (isset($item['quantity']) && isset($item['price'])) {
                $total_price += $item['quantity'] * $item['price'];
            }
        }

        $return['price'] = $total_price;

        // Checking whether to apply the VAT or not.
        $settings = Settings::instance();
        $vat_is_enabled = $settings->vat_state;
        if($vat_is_enabled) {
            $vat_value = (float) $settings->vat_value;
            if($vat_value) {
                $vat_value = ($total_price * $vat_value) / 100;
                $total_price += $vat_value;
                $return['vat'] = $vat_value;
            }
        }

        $return['total'] = $total_price;
        return (object) $return;
    }

    /**
     * Get the number of items in the cart
     *
     * @param  boolean  $totalItems  Get all the items (when false, will return the number of rows)
     * @return int
     */
    public function count($totalItems = true)
    {
        $cart = $this->getCart();
        if( ! $totalItems) {
            return count($cart);
        }
        $count = 0;
        foreach($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    protected function getCart() {
        if (Session::has($this->sessionKey)) {
            return Session::get($this->sessionKey);
        }
        // Empty cart.
        return array();
    }

    protected function updateCart($cart) {
        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    /**
     * Generate a unique id for the new item
     *
     * @param  string  $id       Unique ID of the item
     * @param  array   $attributes  Array of additional options, such as 'size' or 'color'
     * @return boolean
     */
    protected function generateItemId($projectId, $attributes)
    {
        ksort($attributes);
        return md5($projectId . serialize($attributes));
    }

    /**
     * Check if a rowid exists in the current cart instance
     *
     * @param  string  $id  Unique ID of the item
     * @return boolean
     */
    protected function hasItemId($itemId)
    {
        $cart = $this->getCart();
        return (!empty($cart) && in_array($itemId, array_keys($cart)) ? TRUE : FALSE);
    }

}
