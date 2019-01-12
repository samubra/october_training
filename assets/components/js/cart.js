$(document).ready(function() {

    var msgJsJson = $('#msg_js').text();
    var msgJs = JSON.parse(msgJsJson);

    //add or update cart
    jQuery.addOrUpdateCart = function(thisDiv, qtyAdd, updateQty) {
        var productId = thisDiv.attr('attr-product-id');
        var qtyOrigin = thisDiv.attr('attr-qty');
        var cart = sessionStorage.getItem('cart');
        var cartObject = {};
        if (cart != null && cart != '') {
            cartObject = JSON.parse(cart);
        }
        var itemDetail = {};
        itemDetail.name = thisDiv.attr('attr-name');
        itemDetail.image = thisDiv.attr('attr-image');
        itemDetail.price = thisDiv.attr('attr-price');
        itemDetail.qty = qtyAdd;//number product customer buy
        itemDetail.qty_origin = qtyOrigin;//quantity in stock
        itemDetail.qty_order = thisDiv.attr('attr-qty-order');//quantity that ordered
        itemDetail.qty_return = thisDiv.attr('attr-qty-return');//quantity that return
        itemDetail.id = productId;
        itemDetail.slug = thisDiv.attr('attr-slug');
        itemDetail.weight = thisDiv.attr('attr-weight');
        itemDetail.weight_id = thisDiv.attr('attr-weight-id');
        var msg = '';
        if (productId in cartObject) {
            var itemInCart = cartObject[productId];
            itemInCart.qty = itemInCart.qty + 1;
            if (updateQty == true) {
                msg = msgJs.update_item_successful;
                itemInCart.qty = qtyAdd;
            } else {
                $('#modalConfirmCart').modal();
            }
            cartObject[productId] = itemInCart;
        } else {
            cartObject[productId] = itemDetail;
            $('#modalConfirmCart').modal();
        }
        sessionStorage.setItem('cart', JSON.stringify(cartObject));
        $.notify(msg, msgJs.add_item_success);
    };

    //add or update cart in detail page
    jQuery.addOrUpdateInDetail = function(qty, qtyOrder, qtyReturn, thisDiv) {
        var qtyInput = $('#qty-input').val();
        if (parseInt(qtyInput) + parseInt(qtyOrder) > qty + parseInt(qtyReturn) && qty > 0) {
            //if qty = 0 => not manage stock
            $.notify(msgJs.qty_not_enough);
        } else {
            $.addOrUpdateCart(thisDiv, parseInt(qtyInput));
        }
    };

    //handle event click to buy product
    jQuery.buyNow = function(thisDiv, isDetail) {
        var qty = thisDiv.attr('attr-qty');
        var qtyOrder = thisDiv.attr('attr-qty-order');
        var qtyReturn = thisDiv.attr('attr-qty-return');
        var productId = thisDiv.attr('attr-product-id');
        var cart = sessionStorage.getItem('cart');
        var cartObject = {};
        if (cart != null && cart != '') {
            cartObject = JSON.parse(cart);
        }
        if (productId in cartObject) { // if product already in cart
            var itemInCart = cartObject[productId];
            var qtyInCart = itemInCart.qty;
            if (parseInt(qtyInCart) + parseInt(qtyOrder) >= qty && qty > 0) {//if qty = 0 => not manage stock
                $.notify(msgJs.qty_not_enough);
            } else {
                if (isDetail == false) {
                    $.addOrUpdateCart(thisDiv, 1);
                } else {
                    $.addOrUpdateInDetail(qty, qtyOrder, qtyReturn, thisDiv);
                }
            }
        } else {//if product not in cart yet
            if (isDetail == false) {//if not in detail page
                $.addOrUpdateCart(thisDiv, 1);
            } else {
                $.addOrUpdateInDetail(qty, qtyOrder, qtyReturn, thisDiv);
            }
        }
    };

    //buy now in list
    $('.buy-now').click(function() {
        $.buyNow($(this), false);
    });

    //buy now in detail
    $('.buy-now-detail').click(function() {
        $.buyNow($(this), true);
    });

    //delete cart item
    jQuery.deleteItem = function(productId) {
        var cartRs = sessionStorage.getItem('cart');
        var cartRsObj = JSON.parse(cartRs);
        delete cartRsObj[productId];
        sessionStorage.setItem('cart', JSON.stringify(cartRsObj));
    };

    $(document).on('click', '.cart-remove-item', function() {
        if (confirm('Are you sure delete this item ?')) {
            var productId = $(this).attr('attr-product-id');
            $.deleteItem(productId);
        }
    });

    $(document).on('click', '.view-now', function() {
        var slug = $(this).attr('attr-slug');
        var baseUrl = document.location.origin;
        document.location.href = baseUrl + '/product/' + slug;
    });

    jQuery.ajaxCart = function(update) {
        var cartRs = sessionStorage.getItem('cart');
        var cartRsObj = JSON.parse(cartRs);
        $.request('onAjaxCart', {
            data: cartRsObj,
            update: update
        });
    };

    //find #cart-div to fill data for cart
    if (document.getElementById('cart-div') !== null) {
        var update = { 'Cart::onAjaxCart' : '#cart-div'};
        $.ajaxCart(update);
    }

    $(document).on('click', '.cart-remove-item-detail', function() {
        if (confirm(msgJs.sure_to_delete)) {
            var productId = $(this).attr('attr-product-id');
            $.deleteItem(productId);
            var update = { 'Cart::onAjaxCart' : '#cart-div'};
            $.ajaxCart(update);
        }
    });

    /**
     * Update item in cart
     */
    jQuery.updateItemInCart = function(thisDiv) {
        var productId = thisDiv.attr('attr-product-id');
        var qtyInput = thisDiv.val();
        var qtyOrigin = thisDiv.attr('attr-qty');//quantity in stock
        var qtyOrder = thisDiv.attr('attr-qty-order');//quantity was ordered
        var cartRs = sessionStorage.getItem('cart');
        var cartRsObj = JSON.parse(cartRs);
        var item = cartRsObj[productId];
        var qty = item.qty;//number product customer buy
        if (parseInt(qtyInput) + parseInt(qtyOrder) > parseInt(qtyOrigin) && qtyOrigin > 0) {//if qty = 0 => not manage stock
            $.notify(msgJs.qty_not_enough);
        } else {
            $.addOrUpdateCart( thisDiv, parseInt(qtyInput), true);
            $.ajaxCart();
        }
    };

    //catch keypress event when update items in cart
    $(document).on('keypress', '.cart-qty', function(e) {
        if(e.which == 13){
            e.preventDefault();//Enter key pressed
            $.updateItemInCart($(this));
        }
    });

    //catch focusout event when update items in cart
    $(document).on('focusout', '.cart-qty', function() {
        $.updateItemInCart($(this));
    });



});