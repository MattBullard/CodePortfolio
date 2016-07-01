(function ($) {
	  $.each(['show', 'hide'], function (i, ev) {
	    var el = $.fn[ev];
	    $.fn[ev] = function () {
	      this.trigger(ev);
	      return el.apply(this, arguments);
	    };
	  });
	})(jQuery);
	
$('#foo').on('show', function() {
      console.log('#foo is now visible');
});

$('#foo').on('hide', function() {
      console.log('#foo is hidden');
});
