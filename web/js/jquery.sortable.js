// JavaScript Document
(function($) {
var dragging, placeholders = $();
$.fn.sortable = function(options) {
	var method = String(options);
	options = $.extend({
		connectWith: false
	}, options);
	return this.each(function() {
		if (/^enable|disable|destroy$/.test(method)) {
			var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
			if (method == 'destroy') {
				items.add(this).removeData('connectWith items')
					.off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
			}
			return;
		}
		var isHandle, index, items = $(this).children(options.items);
		var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable_dj-placeholder">');
		items.find(options.handle).mousedown(function() {
			isHandle = true;
		}).mouseup(function() {
			isHandle = false;
		});
		$(this).data('items', options.items)
		placeholders = placeholders.add(placeholder);
		if (options.connectWith) {
			$(options.connectWith).add(this).data('connectWith', options.connectWith);
		}
		items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
			if (options.handle && !isHandle) {
				return false;
			}
			isHandle = false;
			var dt = e.originalEvent.dataTransfer;
			dt.effectAllowed = 'move';
			//dt.setData('Text', 'dummy');
			dt.setData('Text', 'https://aw.myq360.com');
			index = (dragging = $(this)).addClass('sortable_dj-dragging').index();
		}).on('dragend.h5s', function() {
			if (!dragging) {
				return;
			}
			dragging.removeClass('sortable_dj-dragging').show();
			placeholders.detach();
			if (index != dragging.index()) {
				dragging.parent().trigger('sortupdate', {item: dragging});
			}
			dragging = null;
		}).not('a[href], img').on('selectstart.h5s', function() {
			this.dragDrop && this.dragDrop();
			return false;
		}).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
			if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
				return true;
			}
			if (e.type == 'drop') {
				e.stopPropagation();
				placeholders.filter(':visible').after(dragging);
				dragging.trigger('dragend.h5s');
				return false;
			}
			e.preventDefault();
			e.originalEvent.dataTransfer.dropEffect = 'move';
			if (items.is(this)) {
				if (options.forcePlaceholderSize) {
					placeholder.height(dragging.outerHeight());
				}
				dragging.hide();
				$(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
				placeholders.not(placeholder).detach();
			} else if (!placeholders.is(this) && !$(this).children(options.items).length) {
				placeholders.detach();
				$(this).append(placeholder);
			}
			return false;
		});
	});
};
})(jQuery);



function callSortJS(divId) {
 if(is_page_from=='medical_condition_config_tool') { 
  if($.trim(divId)=='') {
	  $('.sortable_dj').sortable().bind('sortupdate', function() {
	  updateDisplayOrder_in_AWV_MEAT_Config_Tool(this.id);
	  });
  }else {
	  $('#'+divId).sortable().bind('sortupdate', function() {
		  updateDisplayOrder_in_AWV_Config_Tool(this.id);
	  });
  }
 }
 if(is_page_from=='ros_config_tool_system_options') { 
 	 $('.sortable_dj').sortable().bind('sortupdate', function() {
	  updateDisplayOrder_in_ROS_System_Option_AWV_Config_Tool(this.id);
	  });
 }
 if(is_page_from=='ros_config_tool_system') {
	   $('.sortable_dj').sortable().bind('sortupdate', function() {
	  	updateDisplayOrder_in_ROS_System_AWV_Config_Tool(this.id);
	  });
 }
 if(is_page_from=='configure_display_order_meat') {
	   $('.sortable_dj').sortable().bind('sortupdate', function() {
	  	updateDisplayOrderInConfigRuleMEAT(this.id);
	  });
 }
}

function updateDisplayOrder_in_ROS_System_AWV_Config_Tool(order_ID) {
	console.log(order_ID);
	totalVals='';
	$("#"+order_ID+" li ").each(function() {
			  totalVals+=$(this).attr('id')+"#";
	 });
	 totalVals+=order_ID;
	 console.log(totalVals);
	 $.ajax({
        type: "POST", dataType: "json", url: "/"+pgControllerName+"/save-ros-system-display-order",
        data: {
			totalVals:totalVals,
			_csrf : csrfToken,
		},
        success: function(response) {
			ss=0;
        }
        });
}

function updateDisplayOrder_in_ROS_System_Option_AWV_Config_Tool(order_ID) {
	console.log(order_ID);
	totalVals='';
	$("#"+order_ID+" li ").each(function() {
			  totalVals+=$(this).attr('id')+"#";
	 });
	 totalVals+=order_ID;
	 console.log(totalVals);
	 $.ajax({
        type: "POST", dataType: "json", url: "/"+pgControllerName+"/save-ros-system-options-display-order",
        data: {
			totalVals:totalVals,
			_csrf : csrfToken,
		},
        success: function(response) {
			ss=0;
        }
        });
}
function updateDisplayOrder_in_AWV_MEAT_Config_Tool(order_ICD_ID) {
	    totalVals='';
	    $("#"+order_ICD_ID+" li ").each(function() {
			  totalVals+=$(this).attr('id')+"#";
		  });
	   totalVals+=order_ICD_ID;
	   $.ajax({
        type: "POST", dataType: "json", url: "/"+pgControllerName+"/save-meat-options-display-order",
        data: {
			totalVals:totalVals,
			_csrf : csrfToken,
		},
        success: function(response) {
			ss=0;
        }
        });
}
$( document ).ready(function() {
   callSortJS('');
});