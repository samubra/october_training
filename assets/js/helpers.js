var baseUrl = window.location.origin;
function openMediaFinder(elem, fieldAssignValue) {
    new $.oc.mediaManager.popup({alias: 'ocmediamanager', onInsert: function(items) {
        if(items.length != 1) {
            alert('Choose only one file.');
            return;
        }
        var imagePath = items[0].path;
        var imageSrc = baseUrl+'/storage/app/media'+imagePath;
        var divImage = '<img src="'+imageSrc+'" />';
        $(elem).html('').append(divImage);
        $('#'+fieldAssignValue).val(imagePath);
        this.hide();
    } })
}

function openFileFinder(elem) {
    new $.oc.mediaManager.popup({alias: 'ocmediamanager', onInsert: function(items) {
        if(items.length != 1) {
            alert('Choose only one file.');
            return;
        }
        var itemPath = items[0].path;
        $(elem).val(itemPath);
        this.hide();
    } })
}

$(document).ready(function() {

    /*PRODUCT GALLERY*/
    $(document).on('click', '.img-delete', function() {//remove image
        $(this).parent().remove();
    });
    $(document).on('click', '.pickmedia', function() {
        console.log(galleryDiv);
        var galleryDivName = $(this).attr('attr-div-gallery');
        var galleryDiv = $('#'+galleryDivName);
        var nameHidden = $(this).attr('attr-name-hidden');
        new $.oc.mediaManager.popup({alias: 'ocmediamanager', onInsert: function(items) {
            //console.log(items);
            $.each(items, function(index, value) {
                var imageDiv = '<li class="image-outer">'+
                    '<input type="hidden" name="'+nameHidden+'[]" value="'+value.path+'" />' +
                    '<img class="image-inner" src="'+baseUrl+'/storage/app/media'+value.path+'"/>' +
                    '<img class="img-delete" src="'+baseUrl+'/plugins/ideas/cart/assets/img/x.png'+'" /> ' +
                    '<div class="gallery-image">'+value.path+'</div>' +
                    '</li>';
                galleryDiv.append(imageDiv);
            });
            this.hide();
        }});
        galleryDiv.sortable('enable');
    });

    $('#gallery').sortable('enable');

    jQuery.select2AutoComplete = function(backendUrl, selectDiv, url) {
        $(selectDiv).select2({
            ajax: {
                url: backendUrl + url,
                method: "post",
                dataType: 'json',
                delay: 0,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function(obj) {
                            return { id: obj.id, text: obj.text};
                        })
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 2
        });
    };
});
