	var canvas, context, centerX, centerY, cw, ch, sbHeight, mousePosText;
	var cardh, cardw, lmargin, rmargin, playw, playh, cardSpacing;

	gameInit();
	
	
	function gameInit() {
		sbHeight = 25;
		cardSpacing = 10;
		mousePosText = "Mouse (0, 0)";

		canvasInit();
	}

	function canvasInit() {
		canvas = document.getElementById('myCanvas');
		context = canvas.getContext('2d');

		// set some intital variable valuse
		context.textAlign = 'left';

		// this will call the redraw function every 20 milli seconds
		// right now the redraw is called when the mouse is moved.  We may 
		// need the screen to update without the mouse moving.
		// setInterval(drawCanvas, 20);

		resizeCanvas();
		addListeners();

	}

	function addListeners() {
		window.onresize = resizeCanvas;
		canvas.onmousedown = mouseDown;
		canvas.onmouseup = mouseUp;
		canvas.onmousemove = mouseMove;
		canvas.ondblclick = mouseDblClick;		
  		canvas.onselectstart = returnFalse;

	}
