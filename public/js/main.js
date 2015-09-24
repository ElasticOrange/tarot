var $successBox, $errorBox, successBoxTimeout, errorBoxTimeout;
var loaderTimeoutHandle;

var LOADER_DELAY = 200;

function trace() {
	console.error(arguments);
}

function initOnceActiveElements() {

	trace('initOnceActiveElements');

	$(document).on('blur', '[synchronize]',function() {
		var $this =  $(this);
		var self = this;
		var name = $this.attr('synchronize');
		var value = $this.val();
		$('[synchronize="' + name + '"]').each(function() {
			if (self == this) {
				return;
			}

			var $this = $(this);
			$this.val(value);
			if (_.isEmpty($this.attr('name'))) {

				var oldSync = $this.attr('synchronize');
				$this.attr('synchronize', '');
				$this.trigger('blur');
				setTimeout(function() {
					$this.attr('synchronize', oldSync);
				}, 100)
			}
		});
	});

	$('#mark_as_responded').change(onMarkAsResponded);
}

function showLoader() {
	loaderTimeout = setTimeout(function() {
		$('#loader').show();
		loaderTimeout = undefined;
	}, LOADER_DELAY);
}

function hideLoader() {
	clearTimeout(loaderTimeout);
	loaderTimeout = undefined;
	$('#loader').hide();
}

function onMarkAsResponded() {
	var $this = $(this);
	var url = $this.attr('client-id');

	if ($this.prop('checked')) {
		url = $this.attr('mark-url');
	}
	else {
		url = $this.attr('unmark-url');
	}

	showLoader();
	var request = $.ajax({
		url: url,
		method: 'get',
		dataType: 'json'
	});

	request.always(function() {
		hideLoader();
	});
}

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

function initEmailsLoader() {
	var emailsContainer = $('#user-emails');
	if (!emailsContainer.length) {
		return false;
	}

	var emailTemplateContent = $('#email-container-template').html();
	var emailTemplate = _.template(emailTemplateContent);
	var urlTemplateContent = emailsContainer.attr('href').replace('emailCount', '<%= emailCount %>');
	var urlTemplate = _.template(urlTemplateContent);
	var countSelector = $('#email-count-selector');
	var emails = {};
	var validEmailStructure = {
		id: '',
		from_email: '',
		from_name: '',
		to_email: '',
		to_name: '',
		text_content: '',
		html_content: '',
		sent: ''
	}

	var isValidEmail = function(email) {
		if (!hasStructure(email, validEmailStructure)) {
			return false;
		}
		return true;
	}

	var emailExists = function(email) {
		if (!isValidEmail(email)) {
			return withError(['emailExists: email is not valid', email]);
		}

		if (emails[email.id]) {
			return true;
		}

		return false;
	}

	var transformEmailBody = function(content) {
		content = content.replace(/(\n)/g, '<br/>');

		return content;
	}

	var generateEmailElement = function(email) {
		var emailParams = {
			id: email.id,
			date: email.sent_at,
			sender: email.from_name,
			attachments: email.attachments
		}

		if (email.sent == 1) {
			emailParams.email = email.html_content;
		}
		else {
			if (_.isEmpty(email.text_content)) {
				email.text_content = stripTags(email.html_content);
			}
			emailParams.email = transformEmailBody(email.text_content);
		}

		return $(emailTemplate(emailParams));
	}

	var getEmailElement = function(email) {
		return $('[aria-controls=email-' + email.id + ']');
	}

	var insertNewEmail = function(email, afterEmail) {
		if (emailExists(email)) {
			return false;
		}

		emails[email.id] = email;

		if (afterEmail === undefined) {
			emailsContainer.prepend(generateEmailElement(email));
		}
		else {
			var existingEmailElement = getEmailElement(afterEmail);
			existingEmailElement.before(generateEmailElement(email));
		}

		return true;
	}

	var updateEmails = function(newEmails) {
		if (!_.isArray(newEmails)) {
			console.error('updateEmails: Emails is not array',newEmails);
			return false;
		}

		var previousEmail;

		_.forEach(newEmails, function(newEmail) {
			insertNewEmail(newEmail, previousEmail);
			previousEmail = newEmail;
		});
		initActiveElements();
	}

	var retrieveEmails = function() {
			var emailCount = countSelector.val();
			var url = urlTemplate({emailCount: emailCount});
			var request = $.ajax({
				url: url,
				method: 'get',
				dataType: 'json'
			});

			request.done(function(emails) {
				updateEmails(emails);
			});
		}


	setInterval(
		retrieveEmails,
		10 * 1000
	);

	retrieveEmails();

	countSelector.on('change', retrieveEmails);

	return true;
}

function getValuesForTemplate() {
	var birthDateText =  $('input[name=birthDate]').val();
	var birthDate = new Date(birthDateText);
	var age = diffDatesInYears(new Date(), birthDate);

	var country = $('input[name=country]').val();
	var infocostsForCountry = _.filter(infocosts, {country: country});
	var infocost;
	if (!_.isEmpty(infocostsForCountry)) {
		var infocost = infocostsForCountry[0];
	}

	var result = {
		'client-first-name' : $('input[name=firstName]').val(),
		'client-last-name' :  $('input[name=lastName]').val(),
		'client-partner-name' :  $('input[name=partnerName]').val(),
		'client-gender' :  $('select[name=gender]').val(),
		'client-interest' :  $('input[name=interest]').val(),
		'client-age' : age,
		'client-birth-date' : birthDate.getDay() + '-' + birthDate.getMonth() + '-' + birthDate.getFullYear(),
		'site-default-infocost' : (infocost ? infocost.infocost : ''),
		'site-default-country' : '',
		'site-default-name' : currentSite.ownername,
		'site-default-url' : currentSite.url,
		'site-default-sender' : currentSite.owneremail
	}

	return result;
}

function insertValuesInTemplate(content) {
	var values = getValuesForTemplate();

	_.forEach(values, function(value, key) {
		var placeHolder = '%%%' + key + '%%%';
		var regEx = new RegExp(placeHolder, 'g');
		content = content.replace(regEx, value);
	});

	return content;
}

function loadTemplateInEditor(templateId) {
	var requestUrl = '/sites/'+ currentSite.listid + '/templates/' + templateId + '/get';
	var request = $.ajax({
		url: requestUrl,
		method: 'get'
	});

	request.done(function(templateBody) {
		templateBody = insertValuesInTemplate(templateBody);

		CKEDITOR.instances.rich_editor.setData(templateBody);
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

	$(document).on('click', '[template-id]', function() {
		var templateId =$(this).attr('template-id');
		loadTemplateInEditor(templateId);
	});

	initActiveElements();
	initEmailsLoader();
	initOnceActiveElements();
});
