console.log('Js loaded');

var initActiveElements = function() {

	// Put toggle icon on .colapser elements
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
}

$(function(){
	if ($('#rich-editor').length) {
		CKEDITOR.replace('rich-editor');
	}

	$('.combobox').combobox();
	console.log('Done', $('.combobox').length);

	$(document).on('click', '[data-confirm]', function(ev) {
		var $this = $(this);

		if (!confirm($this.attr('data-confirm'))) {
			ev.preventDefault();
		}

	})


	// When clicking on .email-title switch expand state of toggle icon
	$(document).on('click', '.email-title', function() {
		var $this = $(this);
		if ($this.attr("aria-expanded") == "true") {
			$this.find('.glyphicon').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
		}
		else {
			$this.find('.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
		}
	});

	// When clicking on tr that gas href attr go to that address
	$(document).on('click', 'table.table tbody td', function(ev) {
		var $this = $(this);

		if ($this.hasClass('prevent-href')) {
			return;
		}

		var href = $this.parents('tr').attr('href');

		if (href) {
			window.document.location = href;
		}
	})

	// When hovering td transform td content into link
	$(document).on('mouseenter', 'table.table td', function(ev) {
		var $this = $(this);

		if ($this.hasClass('prevent-href')) {
			return;
		}

		if ($this.attr('has-link') == 'true') {
			return;
		}

		var html = $this.html();
		var url = $this.parent('tr').attr('href');

		if (!url) {
			return;
		}

		$this.html('<a href="' + url + '" class="table-link">' + html + '</a>');
	});

	// When hovering out put back original data in td
	$(document).on('mouseleave', 'table.table td', function(ev) {
		var $this = $(this);
		var $a = $this.find('a.table-link')

		if (! $a.length)  {
			return;
		}

		$this.html($a.html());
	});

	initActiveElements();
});
