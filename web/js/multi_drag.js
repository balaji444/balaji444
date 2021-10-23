$(".ULheadingSort").on('click', 'li', function (e) {
    if (e.ctrlKey || e.metaKey) {
        $(this).toggleClass("selected");
    } else {
        $(this).addClass("selected").siblings().removeClass('selected');
    }
}).sortable({
    connectWith: "ul",
    delay: 150, //Needed to prevent accidental drag when trying to select
    revert: 0,
    helper: function (e, item) {
        var helper = $('<li/>');
        if (!item.hasClass('selected')) {
            item.addClass('selected').siblings().removeClass('selected');
        }
        var elements = item.parent().children('.selected').clone();
        item.data('multidrag', elements).siblings('.selected').remove();
        return helper.append(elements);
    },
    stop: function (e, info) {
        info.item.after(info.item.data('multidrag')).remove();
    }

});

/*$( function() {
    $( "#sortableBobILI" ).sortable();
    $( "#sortableBobILI" ).disableSelection();
  } );*/
  
  
   $(function () {
    $(".grid").sortable({
        tolerance: 'pointer',
        revert: 'invalid',
        placeholder: 'span2 well placeholder tile',
        forceHelperSize: true
    });
});
