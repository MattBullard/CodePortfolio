
	function drawCanvas() {
		var x, y;
		
		context.clearRect(0, 0, cw, ch);
	
		context.fillStyle = "#dddddd";
		context.fillRect(0, 0, cw, ch);

		drawLayout();

		drawStatusBar();
	}

	function drawStatusBar() {
		var barTop = ch - sbHeight;
		context.fillStyle = "#bbbbbb";
		context.fillRect(0, barTop, cw, ch);

		x = cw - textWidth(mousePosText) - 10; y = barTop + 15;
		writeText(mousePosText, x, y, 0, 0);
	}

	function drawLayout() {
		if (playh < 300) { return; }
		if (playw < 600) { return; }
		drawLeftMargin();
		drawRightMargin();
		var x, y, start;
		// just draw an outline where wach card is suppose to go.
		start = parseFloat(centerX - (cardw / 2) - (2 * cardw));
		x = start;
		y = cardSpacing;

		for (a=0; a<5; a++) {
			for (b=0; b<5; b++) {
				logIt(x + ' ' + y);
				drawCardOutline(x, y);
				x += parseFloat(cardw) + parseFloat(cardSpacing);
			}
			y += parseFloat(cardh) + parseFloat(cardSpacing);
			x = start;
		}

	}

	function drawCardOutline(x, y) {
		var cornerRadius = 10;

		var x0 = parseInt(x);
		var x1 = parseInt(x) + parseInt(cornerRadius);
		var x2 = parseInt(x) + parseInt(cardw) - parseInt(cornerRadius);
		var x3 = parseInt(x) + parseInt(cardw);

		var y0 = parseInt(y);
		var y1 = parseInt(y) + parseInt(cornerRadius);
		var y2 = parseInt(y) + parseInt(cardh) - parseInt(cornerRadius);
		var y3 = parseInt(y) + parseInt(cardh);

		context.beginPath();
		context.moveTo(x1, y0);
		context.lineTo(x2, y0);
		context.arcTo(x3, y0, x3, y1, cornerRadius); // NE
		context.lineTo(x3, y2);
		context.arcTo(x3, y3, x2, y3, cornerRadius); // SE
		context.lineTo(x1, y3);
		context.arcTo(x0, y3, x0, y2, cornerRadius); // SW
		context.lineTo(x0, y1);
		context.arcTo(x0, y0, x1, y0, cornerRadius); // NW
		
		context.lineWidth = 1;
		context.strokeStyle = '#000000';
		context.stroke();
	}


	function drawLeftMargin() {
		var oldFill = context.fillStyle;
		context.fillStyle = "#cccccc";
		context.fillRect(0, 0, lmargin, ch);
		context.fillStyle = oldFill;
	}

	function drawRightMargin() {
		var oldFill = context.fillStyle;
		context.fillStyle = "#cccccc";
		context.fillRect(cw - rmargin, 0, cw, ch);
		context.fillStyle = oldFill;
	}		
