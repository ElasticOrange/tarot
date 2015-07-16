console.log('Js loaded');

$(function(){
	CKEDITOR.replace('rich-editor');

	$('.combobox').combobox();
	console.log('Done', $('.combobox').length);
});
