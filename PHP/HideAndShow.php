function hideIt($val) {
	$val = base64_encode($val);
	$val = urlencode($val);
	return $val;
}

function showIt($val) {
	$val = urldecode($val);
	$val = base64_decode($val);
	return $val;
}
