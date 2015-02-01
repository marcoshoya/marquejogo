/**
 * Custom js library
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */

jQuery(function () {
    try {
        library.init();
    } catch (e) {
        //console.log(e);
    }
});

library = {
    init: function () {
        library.gallery();
    },
    gallery: function () {
        if (jQuery("#camera_wrap_2").size()) {
            jQuery('#camera_wrap_2').camera({
                height: '500px',
                fx: 'simpleFade',
                pagination: false,
                thumbnails: true,
                autoAdvance: false,
                playPause: false,
                rows: 1
            });
        }
    },
    stateChange: function (element, path) {
        var city = jQuery(":input:eq(" + ($(":input").index(element) + 1) + ")");
        city.html("carregando");
        jQuery.ajax({
            type: "POST",
            data: "data=" + element.value,
            url: "/location",
            success: function (result) {
                if (result != '') {
                    city.html(result);
                } else {
                    city.html('Nenhum resultado');
                }
            }
        });
    }
}