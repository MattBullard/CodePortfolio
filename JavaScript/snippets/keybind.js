META = 8;
SHIFT = 4;
CTRL = 2;
ALT = 1;
NONE = 0;

var keybinds = {down: {}, up: {}, keypress: {}};

function keyhook(e)
{
	var e = e || window.event;
	var modifiers = NONE;
	var ondown = e.type == "keydown" ? "down" : (e. type == "keypress" ? "keypress" : "up");
	var key;
	if (ondown == "keypress")
		key = e.charCode || e.keyCode;
	else
		key = e.keyCode;
	var didsomething = true;

	if (e.altKey)
		modifiers |= ALT;
	if (e.ctrlKey)
		modifiers |= CTRL;
	if (e.shiftKey)
		modifiers |= SHIFT;
	if (e.metaKey && e.metaKey != undefined)
		modifiers |= META;

	var index = bindIndex(key, modifiers);

	if (keybinds[ondown][index] == undefined)
		return true;

	with (keybinds[ondown][index])
	{
		if (stack.length > 0)
		{
			var func = popBind(key, modifiers, ondown);
			if (func)
				func(key, modifiers);
			else
				didsomething = false;
		}
		else if (default_ != null)
			default_(key, modifiers);
	}

	if (didsomething)
	{
		e.cancelBubble = true;
		if (e.stopPropogation)
			e.stopPropogation();

		return false;
	}
	return true;
}

function bindIndex(key, modifiers)
{
	return "key" + key + "_" + modifiers;
}

function defaultBind(key, modifiers, func, ondown)
{
	var index = bindIndex(key, modifiers);
	ondown = typeof ondown == "undefined" ? "up" : (ondown == 2 ? "keypress" : "down");
	setupBind(key, modifiers, ondown);

	keybinds[ondown][index].default_ = func;
}

function pushBind(key, modifiers, func, ondown)
{
	var index = bindIndex(key, modifiers);
	ondown = typeof ondown == "undefined" ? "up" : (ondown == 2 ? "keypress" : "down");
	setupBind(key, modifiers, ondown);

	keybinds[ondown][index].stack.push(func);
}
function popBind(key, modifiers, ondown)
{
	var index = bindIndex(key, modifiers);
	ondown = typeof ondown == "undefined" ? "up" : (ondown == 2 ? "keypress" : "down");

	if (keybinds[ondown][index] != undefined)
		return keybinds[ondown][index].stack.pop();
	return null;
}

function removeBind(key, modifiers, func, ondown)
{
	var index = bindIndex(key, modifiers);
	var i;
	ondown = typeof ondown == "undefined" ? "up" : (ondown == 2 ? "keypress" : "down");
	
	if (keybinds[ondown][index] != undefined)
		with (keybinds[ondown][index])
			for (i in stack)
			{
				if (stack[i] == func)
				{
					stack.splice(i, 1);
					return;
				}
			}
}

function setupBind(key, modifiers, ondown)
{
	var index = bindIndex(key, modifiers);
	if (typeof keybinds[ondown][index] == "undefined")
		keybinds[ondown][index] = {default_: null, stack: []};
}

if (typeof addEvt != "function")
{
	function addEvt(obj,evt,fn) {
		if (obj.addEventListener)
			obj.addEventListener(evt,fn,false);
		else if (obj.attachEvent)
			obj.attachEvent('on'+evt,fn);
	}

	function delEvt(obj,evt,fn) {
		if (obj.removeEventListener)
			obj.removeEventListener(evt,fn,false);
		else if (obj.detachEvent)
			obj.detachEvent('on'+evt,fn);
	}
}

addEvt(document, "keydown", keyhook);
addEvt(document, "keyup", keyhook);
addEvt(document, "keypress", keyhook);
