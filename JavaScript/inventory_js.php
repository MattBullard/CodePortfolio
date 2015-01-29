<?php

	session_start();
	$inventory_code = $_SESSION['inventory_code'];

	header("content-type: application/javascript");

?>

$(function(){
	"use strict";
	
	var cnt = 0;
	
	jQuery.fn.extend({
		addRemoveItems: function(targetCount) {
			return this.each(function() {
				var $children = $(this).children();
				var rowCountDifference = targetCount - $children.length;
			   
				if(rowCountDifference > 0)
				{
					// Add items
					for(var i = 0; i < rowCountDifference; i++)
					{
						$children.last().clone().appendTo(this);
					}
				}
				else if(rowCountDifference < 0)
				{
					// remove items
					$children.slice(rowCountDifference).remove();
				}
			});
		},
		parentToAnimate: function(newParent, duration) {
			duration = duration || 'slow';
			
			var $element = $(this);
			if($element.length > 0)
			{
				
				newParent = $(newParent); 
				var oldOffset = $element.offset();
				$(this).appendTo(newParent);
				var newOffset = $element.offset();
	
				var temp = $element.clone().appendTo('body');
				
				temp.css({
					'position': 'absolute',
					'left': oldOffset.left,
					'top': oldOffset.top,
					'zIndex': 1000
				});
				
				$element.hide();
					
				temp.animate({
					'top': newOffset.top,
					'left': newOffset.left
				}, duration, function() {
					$element.show();
					temp.remove();
				});
	
			}
		}
	});
	
	refreshSortableInventoryList();
	
	function refreshSortableInventoryList()
	{
		var ic = '<?php echo $inventory_code; ?>';
		$('.inventory-cell').sortable({
			connectWith: '.inventory-cell',
			placeholder: 'inventory-item-sortable-placeholder',
			receive: function( event, ui ) {
				$(this).children().not(ui.item).parentToAnimate($(ui.sender), 200);
				var bb = $(ui.sender).attr('data-slot-count');  // from
				var aa = $(this).attr('data-slot-count'); 		// to
				$.ajax({ url:"invmove.php", data: { v1:aa, v2:bb, v3:ic }, type: 'POST' });
			}
		}).each(function() {
			// Setup some nice attributes for everything making it easier to update the backend
			$(this).attr('data-slot-position-x', $(this).prevAll('.inventory-cell').length);
			$(this).attr('data-slot-position-y', $(this).closest('.inventory-row').prevAll('.inventory-row').length);
			$(this).attr('data-slot-count', cnt ); cnt++;
		}).disableSelection();
	}
});
