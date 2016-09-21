	function resizeCanvas() {
		canvas.width = window.innerWidth;
		canvas.height = window.innerHeight;
		
		cw = parseFloat(canvas.width);
		ch = parseFloat(canvas.height);

		lmargin = parseFloat( cw * 0.10 ).toFixed(2);
		rmargin = parseFloat( cw * 0.10 ).toFixed(2);

		playw = cw - lmargin - rmargin;
		playh = ch - sbHeight;

		centerX = parseFloat(cw / 2);
		centerY = parseFloat(ch / 2);

		cardh = parseFloat( playh / 5 ).toFixed(2) - parseFloat(cardSpacing) - 3;
		cardw = parseFloat( cardh / 1.4 ).toFixed(2);

		drawCanvas();	
	}


	function logIt(m) { console.log(m); }
	function returnFalse() { return false; }
