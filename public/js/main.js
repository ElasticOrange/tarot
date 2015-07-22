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

function insertAtCaret(areaId,text) {
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") {
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);
    var back = (txtarea.value).substring(strPos,txtarea.value.length);
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") {
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}

function redirect(url, timeout) {
	if (!timeout) {
		timeout = 1;
	}

	setTimeout(function() {
		window.document.location = url;
	} , timeout);
}

function hideSuccessMessage() {
	$successBox.addClass('hidden');
	clearTimeout(successBoxTimeout);
}

function showSuccessMessage(caption) {
	hideErrorMessage();
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
	hideSuccessMessage();
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

function getKeyFromPlaceholder(placeholder) {
	if (!_.isString(placeholder)) {
		console.error('getKeyFromPlaceholder(): placeholder is not string', placeholder);
		return;
	}

	if ((placeholder[0] !== '{') || (placeholder.substr(-1) !== '}')) {
		console.error('getKeyFromPlaceholder(): placeholder is not of form {key}', placeholder);
		return placeholder;
	}

	return placeholder.replace(/[{}]/g, '');
};

function fillPlaceholdersInString(string, data) {
	if(!_.isString(string)) {
		console.error('fillPlaceholdersInString(): string parameter should be of string type', string);
		return;
	}

	if (!_.isPlainObject(data)) {
		console.error('fillPlaceholdersInString(): data should be an object', data);
		return string;
	}

	var placeholders = string.match(/\{([a-z0-9\-_]+)\}/gi);
	var resultString = string;

	if (!placeholders.length) {
		return resultString;
	}

	_.forEach(placeholders, function(placeholder) {
		var regex = new RegExp(placeholder, 'g');
		var key = getKeyFromPlaceholder(placeholder);
		if (placeholder && data[key]) {
			resultString = resultString.replace(regex, data[key]);
		}
	});

	return resultString;
}

function submitGenericAjaxForm(form) {
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

function submitAjaxForm(form) {
	var request = submitGenericAjaxForm(form);

	var $form = $(form);

	request.done(function(data) {
		if (!_.isPlainObject(data)) {
			console.error('submitAjaxForm(): ajax create did not receive the created item', data);
			return;
		}

		var successMessage = $form.attr('success-message');
		if (successMessage) {
 			showSuccessMessage(successMessage);
		}

		var successUrl = $form.attr('success-url');

		if (successUrl) {
			redirect(fillPlaceholdersInString(successUrl, data), 1000);
		}
	});

	request.fail(function(error) {
		var title = '<strong>' + ($(form).attr('error-message') || 'Error:')  + '</strong><br/>'
		var message = title + generateLaravelErrorList(error.responseJSON);

		showErrorMessage(message);
	});
}


$(function(){
	$successBox = $('.message-box.success');
	$errorBox = $('.message-box.error');

	if ($('#rich_editor').length) {
		CKEDITOR.replace('rich_editor');
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
			redirect(href);
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

	$(document).on('submit', 'form[data-ajax=true]', function(ev) {
		ev.preventDefault();
		submitAjaxForm(this);
	});

	$(document).on('change', '[data-submit-on-change=true]', function() {
		$this = $(this);
		$this.parents('form').submit();
	});

	$(document).on('click', '[data-insert-text]', function(ev) {
		$this = $(this);
		CKEDITOR.instances.rich_editor.insertText($this.attr('data-insert-text'));
	})

	initActiveElements();
});
