$(document).ready(function() {

    var msgJsJson = $('#msg_js').text();
    var msgJs = JSON.parse(msgJsJson);
    var fail = $('#result-fail').val();

    $('#star_rating').rating();

    //display modal review
    $('.modal-review').click(function() {
        var productId = $(this).attr('attr-product-id');
        $.request('onCaptcha', {
            data: {},
            success: function(res) {
                var imagePath = '/storage/app/media/captcha/'+res.image;
                $('#captcha-image').attr('src', imagePath);
                sessionStorage.setItem('captcha_code', res.code);
                $('#review-product-id').val(productId);
                $('#modalReview').modal();
            }
        });
    });

    //submit review
    $('#submit-review').click(function(e) {
        e.preventDefault();
        var captcha = $('#captcha').val();
        var captchaSession = sessionStorage.getItem('captcha_code');
        if (captcha == captchaSession) {
            var formReview = $('#form-review-product').serializeArray();
            $.request('onSubmitReview', {
                data: formReview,
                success: function(res) {
                    if (res.rs == fail) {
                        $.notify(res.msg);
                    } else {
                        $('#modalReview').modal('hide');
                        location.reload();
                    }
                }
            });
        } else {
            $.notify(msgJs.captcha_is_not_correct);
        }
    });

    /**
     * Show list reviews
     */
    if (document.getElementById('div-list-reviews') !== null) {
        var productId = $('#div-list-reviews').attr('attr-product-id');
        $.request('onListReview', {
            data: {productId:productId}
        });
    }
});