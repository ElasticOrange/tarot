var $successBox, $errorBox, successBoxTimeout, errorBoxTimeout;


function initActiveElements() {

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

function hideSuccessMessage() {
	$successBox.addClass('hidden');
	clearTimeout(successBoxTimeout);
}

function showSuccessMessage(caption) {
	$successBox.find('.caption').html(caption);
	$successBox.removeClass('hidden');

	successBoxTimeout = setTimeout(function() {
		hideSuccessMessage();
	}, 5000);
}

function hideErrorMessage() {
	$errorBox.addClass('hidden');
	clearTimeout(errorBoxTimeout);
}

function showErrorMessage(caption) {
	$errorBox.find('.caption').html(caption);
	$errorBox.removeClass('hidden');

	errorBoxTimeout = setTimeout(function() {
		hideErrorMessage();
	}, 10000);
}

function generateLaravelErrorList(errorList) {
	if(!_.isPlainObject(errorList)) {
		console.error('generateLaravelErrorList(): errorList is invalid', errorList);
		return;
	}

	var resultHtml = '';

	_.forOwn(errorList, function(messageList, formItem) {
		if (!_.isArray(messageList)) {
			console.error('generateLaravelErrorList(): messageList should be array', messageList, errorList, formItem);
			return;
		}

		_.forEach(messageList, function(errorMessage) {
			resultHtml += '<li>' + errorMessage + '</li>';
		})
	});

	return '<ul>' + resultHtml + '</ul>';
}

function submitAjaxForm(form) {
	var $form = $(form);
	var data = $form.serialize();
	var action = $form.attr('action') || window.document.location;
	var method = $form.attr('method') || 'POST';

	var request = $.ajax({
		url: action,
		method: method,
		data: data,
		dataType: 'json'
	});

	request.done(function(data) {
		console.log('Ajax success: ', data);
	});

	request.fail(function(error) {
		console.error('Ajax error: ', error.responseJSON);
	});

	return request;
}

function doAjaxCreate(form) {
	var request = submitAjaxForm(form);

	request.done(function() {
		showSuccessMessage($(form).attr('success-message'));
	});

	request.fail(function(error) {
		showErrorMessage(($(form).attr('error-message') || 'Error:') + '<br/>' + generateLaravelErrorList(error.responseJSON));
	});
}

$(function(){
	$successBox = $('.message-box.success');
	$errorBox = $('.message-box.error');

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

	$(document).on('click', '.message-box.success', function(){
		hideSuccessMessage();
	});

	$(document).on('click', '.message-box.error', function(){
		hideErrorMessage();
	});

	$(document).on('submit', '[data-ajax-create=true]', function(ev) {
		ev.preventDefault();
		doAjaxCreate(this);
	});


	initActiveElements();
});
