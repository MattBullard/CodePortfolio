$(window).load(function(){
	
	var cnt = 0;
	
	$(window).scroll(function() {
	  if($(this).scrollTop() != 0) {
		$('#to-top').fadeIn(); 
	  } else {
		$('#to-top').fadeOut();
	  }
	});
	$('#to-top').click(function() {
		$('body,html').animate({scrollTop:0},"fast");
	});
	$('#matl_add').click(function() {  
		return !$('#select1 option:selected').remove().appendTo('#select2');  
	});  
	$('#select1').dblclick(function() {  
		return !$('#select1 option:selected').remove().appendTo('#select2');  
	});  
	$('#matl_remove').click(function() {  
		return !$('#select2 option:selected').remove().appendTo('#select1');  
	});
	$('#select2').dblclick(function() {  
		return !$('#select2 option:selected').remove().appendTo('#select1');  
	});
	$('#researchform').submit(function() {  
		$('#select2 option').each(function(i) {  
			$(this).attr("selected", "selected");  
		});  
	});
	
});
