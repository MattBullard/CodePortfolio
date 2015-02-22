function copyit(theField) 
{
	var selectedText = document.selection;
	if (selectedText.type == 'Text') {
		var newRange = selectedText.createRange();
		theField.focus();
		theField.value = newRange.text;
	} else {
		alert('select a text in the page and then press this button');
	}
}
