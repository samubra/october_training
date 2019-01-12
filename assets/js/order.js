$(document).ready(function() {
    var msgJson = $('#msg_js').text();
    var msgObj = JSON.parse(msgJson);

    jQuery.reIndexOrderProductSelect = function() {
        var index = 1;
        $('.order-product-select').each(function() {
            $(this).attr('id', 'order-product-select-'+index);
            index++;
        })
    };

    $('#add-product').click(function() {
        var index = $('.order-product').length;
        var backendUrl = $('#backend_url').val();
        var data = {index: index+1, backendUrl: backendUrl};
        $.reIndexOrderProductSelect();
        $.request('onProduct',
            {
                data: data,
                update: {productpartial: '@#order-products'}
            }
        );
    });

    jQuery.displayMsg = function(msg, type, time) {
        $.oc.flashMsg({
            'text': msg,
            'class': type,
            'interval': time
        });
    };

    $(document).on('click', '.icon-delete-order-product', function() {
        var params = {thisDiv: $(this)};
        $.alertable.confirm(msgObj.delete_order_product, params).then(function() {
            params.thisDiv.parent().parent().remove();
        });

    });


});