$(document).ready(function() {
    // Handler for .ready() called.
    $('form ul ul ul li > button.remove').click(function () {
        $(this).parent().parent().parent().parent().remove();
    });

    $('form ul ul li > button.add').click(function () {
        var _template = $('#fieldset-products ul li:first-child').eq(0);
        var newElm = $(_template).clone();
        makeUnique(newElm);
        $(_template).parent().find('> li:last').before(newElm);
        newElm.children('fieldset').eq(0).show();
    });
});

function removeProduct(button) {
	$(button).parent().parent().parent().parent().remove();
}


// replaces __template__ name part with unique timestamp
function makeUnique(elm)
{
    var unique = new Date().getTime();
    var fieldset = $(elm).children('fieldset').eq(0);
    $(fieldset).attr('id', $(fieldset).attr('id').replace('__template__', unique));
    
    $(elm).find('input').each(function(idx) {
        $(this).attr('id', $(this).attr('id').replace('__template__', unique));
        $(this).attr('name', $(this).attr('name').replace('__template__', unique));
    });

    $(elm).find('label').each(function(idx) {
        $(this).attr('for', $(this).attr('for').replace('__template__', unique));
    });
}