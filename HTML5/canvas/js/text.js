	function writeText(m, x, y, t, c) {
		setFontType(t);
		setFontColor(c);
		context.fillText(m, x, y);
	}

	function textWidth(txt) { return context.measureText(txt).width; }

	function setFontType(val) {
		switch(val) {
			case 0:
				context.font = '10pt Calibri';
				break;
			case 1:
				context.font = '8pt Calibri';
				break;
		}
	}

	function setFontColor(val) {
		switch(val) {
			case 0:
				context.fillStyle = '#000000';
				break;
		}
	}
