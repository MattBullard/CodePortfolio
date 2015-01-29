var parent_positions = {};
$(document).ready(function () {
    set_menu_positions();

    for(var i = 0; i<menu_parents.length; i++) {
        $('#menu_item_' + menu_parents[i] + ' a').on('mouseover', {'menu_parent':menu_parents[i]}, function (event) {
            if(document.getElementById('menu_child_' + event.data.menu_parent).style.display == 'block') { return true; }
            if($(event.target).parent().attr('id') != 'menu_item_' + event.data.menu_parent) { return false; }
            $('#menu_child_' + event.data.menu_parent).show().animate({'top': parent_positions[event.data.menu_parent] - 20, 'opacity': 1}, 100);
        });
    }
    
    var close_timer = 0;

    // Loop through the menu menu items
    $('.topmenu_element, .menu').on('mouseenter', function() {
        clearTimeout(close_timer);
        if($(this).hasClass('menu_edit_icon') || $(this).hasClass('search_menu_container') || $(this).attr('id') == undefined) { return; }
        
        // Get the current child/item ID
        var child_id = parseInt($(this).attr('id').replace('menu_item_', ''));
    
        // Find all the parents for this child
        var dont_hide_parents = {};
        dont_hide_parents[child_id] = true;
        while(child_parents[child_id] != undefined) {
            dont_hide_parents[child_parents[child_id]] = true;
            child_id = child_parents[child_id];
        }
        // Hide all parents, except the ones we want to keep.
        $('.topmenu_submenu').each(function () {
             var parent = parseInt($(this).attr('id').replace('menu_child_', ''));
             if(dont_hide_parents[parent] != true) {
                if($(this).css('display') == 'none') { return true; }
                $(this).animate({'top': parseInt($(this).css('top')) + 20, 'opacity': 0}, 100, function () {
                    $(this).hide();
                });
             }
        });
    });
    
    $('.topmenu_element, .menu').on('mouseleave', function() {
        close_timer = setTimeout(function () {
            $('.topmenu_submenu').each(function() {
                if($(this).css('display') == 'none') { return true; }
                $(this).animate({'top': parseInt($(this).css('top')) + 20, 'opacity': 0}, 100, function () {
                    $(this).hide();
                });
            });
            setTimeout(function () { set_menu_positions(); }, 100);
        }, 2000); 
    });
    
    $('body').on('click', function (event) {
        if($(event.target).attr('class') != 'menu' && $(event.target).attr('class') != 'menu_a') {
            clearTimeout(close_timer);
            $('.topmenu_submenu').each(function() {
                if($(this).css('display') == 'none') { return true; }
                $(this).animate({'top': parseInt($(this).css('top')) + 20, 'opacity': 0}, 100, function () {
                    $(this).hide();
                });
            });
            setTimeout(function () { set_menu_positions(); }, 100);
        }
    });
});

function set_menu_positions () {
    $('.topmenu_submenu').show().css('opacity', 0);
    for(var i = 0; i<menu_parents.length; i++) {
        if($('#menu_item_' + menu_parents[i]).attr('class') == 'topmenu_element') {
            $('#menu_child_' + menu_parents[i]).css({
                'top': $('#menu_item_' + menu_parents[i]).position().top + $('#menu_item_' + menu_parents[i]).outerHeight() + 20 + 'px',
                'left': $('#menu_item_' + menu_parents[i]).position().left + 'px'
            });
            parent_positions[menu_parents[i]] = $('#menu_item_' + menu_parents[i]).position().top + $('#menu_item_' + menu_parents[i]).outerHeight() + 20;
        } else if($('#menu_item_' + menu_parents[i]).attr('class') == 'menu') {
            $('#menu_child_' + menu_parents[i]).css({
                'top': $('#menu_item_' + menu_parents[i]).parent().position().top + $('#menu_item_' + menu_parents[i]).position().top + 'px',
                'left': $('#menu_item_' + menu_parents[i]).parent().position().left + $('#menu_item_' + menu_parents[i]).parent().outerWidth() + 5 + 'px'
            });
            parent_positions[menu_parents[i]] = $('#menu_item_' + menu_parents[i]).parent().position().top + $('#menu_item_' + menu_parents[i]).position().top;
            $('#menu_item_' + menu_parents[i] + ' .menu_right_arrow').show();
        }
    }
    $('.topmenu_submenu').hide().css('opacity', 1); 
}
