<?php
	function isLoggedIn() {
		global $settings, $player_info;
		if (isset($_COOKIE[$settings['login_cookie']])) {
			$query  = "SELECT * FROM " . $settings['dbase_preword'] . "users ";
			$query .= "WHERE usercookie = '" . $_COOKIE[$settings['login_cookie']] . "'";
			$results = mysql_query($query); // echo $query . "<br>";
			if ($row = mysql_fetch_array($results)) {
				$player_info = $row;
				return 1;
			}
		}
		return 0;	
	}
	
	function cleanString($str) {
	  // used to clean incoming GET data	
		$str = str_replace("'", "", $str);
		$str = str_replace('"', "", $str);
		$str = str_replace('<', "", $str);
		$str = str_replace('>', "", $str);
		$str = str_replace('script', "", $str);
		return $str;
	}
	
	function makeNumber($num, $min, $max) { 
		if (!is_numeric($num)) { $num = $min; } // if not a number, than make it a number
		$num = floor($num);	// round the number
		if ($num < $min) { $num = $min;	} // must be at least as big as the the minimum number
		if ($num > $max) { $num = $min;	} // must not be more than the largest number
		return $num;
	}
	function makeInt($num) {
		if (!is_numeric($num)) { $num = 0; } 
		$num = floor($num);	
		if ($num < 0) { $num = 0; }
		return $num;
	}
	
?>
