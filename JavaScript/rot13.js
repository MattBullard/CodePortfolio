function rot( t, u, v ) {
	return String.fromCharCode( ( ( t - u + v ) % ( v * 2 ) ) + u );
}

function rot13(s) {
	var b = [], c, i = s.length,
	a = 'a'.charCodeAt(), z = a + 26,
	A = 'A'.charCodeAt(), Z = A + 26;
	while(i--) {
		c = s.charCodeAt( i );
		if( c>=a && c<z ) { b[i] = rot( c, a, 13 ); }
		else if( c>=A && c<Z ) { b[i] = rot( c, A, 13 ); }
		else { b[i] = s.charAt( i ); }
	}
	return b.join( '' );
}

var misc = "<vzt fep='vzt/43_cynvaf.tvs' nyg='' />";

$(document).ready(function() {
	$('#misc').html(rot13(misc));
	$('#img-spell').dblclick(function(){
		$('#images, #images-container').slideUp(500 ,"easeInOutQuad");
		$('#misc').delay(750).slideDown(1200, "easeInOutQuad");
	});
});
