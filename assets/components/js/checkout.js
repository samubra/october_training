//find closest number
function closest (num, arr) {
    var mid;
    var lo = 0;
    var hi = arr.length - 1;
    while (hi - lo > 1) {
        mid = Math.floor ((lo + hi) / 2);
        if (arr[mid] < num) {
            lo = mid;
        } else {
            hi = mid;
        }
    }
    if (num - arr[lo] <= arr[hi] - num) {
        return arr[lo];
    }
    return arr[hi];
}

$(document).ready(function() {

    var fail = document.getElementById('result-fail').value;
    var constJson = $('#const').text();
    var constArray = JSON.parse(constJson);
    var TYPE_PRICE = constArray.ship_type_price;
    var TYPE_GEO = constArray.ship_type_geo;
    var TYPE_WEIGHT_BASED = constArray.ship_type_weight_based;
    var TYPE_PER_ITEM = constArray.ship_type_per_item;
    var TYPE_GEO_WEIGHT_BASED = constArray.ship_type_geo_weight_based;
    var WEIGHT_TYPE_FIXED = constArray.weight_type_fixed;
    var WEIGHT_TYPE_RATE = constArray.weight_type_rate;
    var totalPriceSpanDiv = 'total-price-span';
    var totalPriceHiddenDiv = 'total-price-hidden';
    var totalFinal = 'total-final';
    var shipMoneyDiv = 'ship-money';

    var baseUrl = window.location.origin;

    /**
     * Display currency
     */
    jQuery.displayCurrency = function(price) {
        var currencyJson = $('#currency-data').text();
        var currencyArray = JSON.parse(currencyJson);
        var symbol = currencyArray.symbol;
        var symbolPosition = currencyArray.symbol_position;
        var symbolPositionBefore = constArray.symbol_position_before;
        var text = '';
        if (symbolPosition == symbolPositionBefore) {
            text = symbol + ' ' + price;
        } else {
            text = price + ' ' + symbol;
        }
        return text;
    };

    jQuery.getTotalPriceFinal = function() {
        var totalPrice = $('#'+totalPriceHiddenDiv).val();
        var priceShip = parseFloat($('#'+shipMoneyDiv).text());
        return parseFloat(totalPrice) + priceShip;
    };

    jQuery.assignTotalPriceSpanDiv = function() {
        var total = $.getTotalPriceFinal();
        $('#'+totalFinal).val(total);
        $('#'+totalPriceSpanDiv).text($.displayCurrency(total));
    };

    jQuery.calculateShipPrice = function(thisDiv) {
        var type = thisDiv.attr('attr-type');
        var cost = thisDiv.attr('attr-cost');
        var priceShip = 0;
        if (type == TYPE_PRICE || type == TYPE_GEO) {
            priceShip = parseFloat(cost);
        }
        if (type == TYPE_PER_ITEM) {
            var qtyTotal = $('#qty-total-hidden').val();
            priceShip = parseFloat(cost) * qtyTotal;
        }
        if (type == TYPE_WEIGHT_BASED || type == TYPE_GEO_WEIGHT_BASED) {
            var weightTotal = $('#weight-total').text();
            var weightBased = thisDiv.attr('attr-weight-base');
            var weightType = thisDiv.attr('attr-weight-type');
            var weightTotalCeil = parseInt(Math.ceil(weightTotal));
            if (weightType == WEIGHT_TYPE_FIXED) {//fixed
                var weightRateFix = weightBased.split(':');
                var eachUnit = parseInt(weightRateFix[0]);
                var unitPrice = parseFloat(weightRateFix[1]);
                priceShip = (weightTotalCeil / eachUnit) * unitPrice;
            } else {//rate
                var weightRateRate = weightBased.split(',');
                var weightRateArray = [];
                var weightRateArrayIndex = [];
                for (var i=0; i<weightRateRate.length; i++) {
                    var weightRateSplit = weightRateRate[i].split(':');
                    weightRateArray[weightRateSplit[0]] = weightRateSplit[1];
                    weightRateArrayIndex.push(weightRateSplit[0]);
                }
                //find closet lowest in array
                var indexClosest = closest(weightTotalCeil, weightRateArrayIndex);
                priceShip = weightRateArray[indexClosest];
            }
        }
        $('#'+shipMoneyDiv).text(priceShip);
        var textShippingCost = $.displayCurrency(priceShip);
        $('#shipping-cost').text(textShippingCost);
        $.assignTotalPriceSpanDiv();
    };



    //find #checkout-cart-div to fill data for cart
    var cartRs = sessionStorage.getItem('cart');
    var cartRsObj = JSON.parse(cartRs);
    $.request('onAjaxCheckoutCart',
        {
            data: cartRsObj,
            update: {'Checkout::onAjaxCheckoutCart' : '#checkout-cart-div'},
            complete: function() {
                //initial ship
                $('.shipping-method-type').each(function() {
                    if ($(this).is(':checked')) {
                        $.calculateShipPrice($(this));
                    }
                });
            }
        }
    );

    /**
     * Choose shipping method
     */
    $('.shipping-method-type').click(function() {
        $.calculateShipPrice($(this))
    });



    $('#checkout').click(function() {
        var params = {};
        params.formData = $('#form-checkout').serializeArray();
        params.cart = $('#cart-json').text();
        params.total = $('#'+totalFinal).val();
        params.shipping_cost = $('#'+shipMoneyDiv).text();
        $.request('onSaveOrder',{
            data: params,
            beforeSend: function() {
               $('#checkout-div').waitMe({color: '#559ef1'});
            },
            success: function(res) {
                $('#checkout-div').waitMe('hide');
                if (res.rs == fail) {
                    $.oc.flashMsg({
                        'text': res.msg,
                        'class': 'error',
                        'interval': 3
                    })
                } else {
                    sessionStorage.setItem('cart', '');
                    window.location.href = baseUrl+'/order-success'
                }
            }
        })
    });
});