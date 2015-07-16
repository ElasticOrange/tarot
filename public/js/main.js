console.log('Js loaded');

var initActiveElements = function() {

	$('.colapser').each(function() {
		var icon = '<span class="glyphicon"></span>';
		var $this = $(this);
		var currentContent = $this.html();
		$this.html(icon + currentContent);

		if ($this.attr('aria-expanded') == "true") {
			$this.find('.glyphicon').addClass('glyphicon-triangle-bottom');
		}
		else {
			$this.find('.glyphicon').addClass('glyphicon-triangle-right');
		}

		$this.removeClass('colapser');
	});

	$('.tab').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})

}

$(function(){
	if ($('#rich-editor').length) {
		CKEDITOR.replace('rich-editor');
	}

	$('.combobox').combobox();
	console.log('Done', $('.combobox').length);


	$(document).on('click', '.email-title', function() {
		var $this = $(this);
		if ($this.attr("aria-expanded") == "true") {
			$this.find('.glyphicon').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
		}
		else {
			$this.find('.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
		}
	});

	initActiveElements();
});
