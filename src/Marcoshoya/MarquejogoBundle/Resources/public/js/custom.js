/**
 * Custom js library
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */

library = {
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