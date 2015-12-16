var $successBox, $errorBox, $warningBox, successBoxTimeout, errorBoxTimeout;
var loaderTimeoutHandle;
var interestChanged = false;

var hasLocalStorage = (function() {
  try {
	var mod = '__localStorageTest';
	localStorage.setItem(mod, mod);
	localStorage.removeItem(mod);
	return true;
  } catch (exception) {
	return false;
  }
}());

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

	initAutoSend($('#send_after_template_fill'));

	$('#clients-table').DataTable({
		"columnDefs": [
			{
				name: "index",
				targets: 0,
				orderable: false
			},
			{
				name: "name",
				targets: 1,
				orderable: false
			},
			{
				name: "emailaddress",
				targets: 2,
				orderable: false
			},
			{
				name: "gender",
				targets: 3,
				orderable: false
			},
			{
				name: "lastEmail",
				targets: 4,
				orderable: false
			},
			{
				name: "lastResponse",
				targets: 5,
				orderable: false
			},
			{
				name: "emailCount",
				targets: 6,
				orderable: false
			},
			{
				name: "comments",
				targets: 7,
				orderable: false
			}
		],
		responsive: true,
		stateSave: true,
		serverSide: true,
		ajax: {
		    "url": window.location.href + '/query',
		},
		createdRow: function(row, data, dataIndex) {
//console.log('row', row, data, dataIndex);
			$(row).attr('href',  window.location.href + '/' + data[10]);
			return row;
		}
	});

}

var shouldAutoSend = function() {
	return false;
}

function initAutoSend($autoSendCheckbox) {
	if (!hasLocalStorage) {
		return withError(['initAutoSend(): localStorage is not available']);
	}

	if (!$autoSendCheckbox.length) {
		return withError(['initAutoSend(): could not find auto send checkbox', $autoSendCheckbox]);
	}

	if (localStorage.getItem('autoSendStatus') === 'true') {
		$autoSendCheckbox.prop('checked', true);
	}

	$autoSendCheckbox.change(function() {
		if (shouldAutoSend()) {
			localStorage.setItem('autoSendStatus', 'true');
		}
		else {
			localStorage.setItem('autoSendStatus', 'false');
		}
	});

	window.shouldAutoSend = function() {
		return $autoSendCheckbox.prop('checked');
	}
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

	request.done(function() {
		if (shouldAutoSend()) {
			onEmailSendSuccess();
		}
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
	var expandedFirstEmail = false;
	var validEmailStructure = {
		id: '',
		from_email: '',
		from_name: '',
		to_email: '',
		to_name: '',
		text_content: '',
		html_content: '',
		sent: undefined
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
		return $('[email-id=' + email.id + ']');
	}

	var insertNewEmail = function(email, afterEmail) {
		if (! isValidEmail(email)) {
			return false;
		}

		if (emailExists(email)) {
			return false;
		}

		emails[email.id] = email;

		var emailContent = $(generateEmailElement(email));

		if (afterEmail === undefined) {
			emailsContainer.prepend(emailContent);
		}
		else {
			var existingEmailElement = getEmailElement(afterEmail);
			existingEmailElement.after(emailContent);
		}
		if (!expandedFirstEmail) {
			emailContent.find('.email-title').removeClass('collapsed').attr('aria-expanded', 'true');
			emailContent.find('.email-body').addClass('in').attr('aria-expanded', 'true');
			updateExpandedIndicator(emailContent.find('.email-title'));
			expandedFirstEmail = true;
		}

		return true;
	}

	var removeEmail = function(email) {
		if (!isValidEmail(email)) {
			return withError('removeEmail(): Email is not valid', email);
		}

		if (emails[email.id]) {
			delete emails[email.id];
			getEmailElement(email).remove();
		}
	}

	var updateEmails = function(newEmails) {
		if (!_.isArray(newEmails)) {
			return withError(['updateEmails: Emails is not array',newEmails]);
		}

		// delete emails that are not in the newEmails
		var newEmailIds = _.pluck(newEmails, 'id');
		_.forEach(emails, function(email) {
			if (_.includes(newEmailIds, email.id)) {
				return;
			}
			removeEmail(email);
		});

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

function getZodiacalSignFromDate(date, signs) {
	if (! _.isArray(signs)) {
		signs = [
			"Capricorn" ,
			"Aquarius",
			"Pisces",
			"Aries",
			"Taurus",
			"Gemini",
			"Cancer",
			"Leo",
			"Virgo",
			"Libra",
			"Scorpio",
			"Sagittarius"
		];
	}

	var signStartDaysPerMonth = {
		0: 20,
		1: 19,
		2: 21,
		3: 20,
		4: 21,
		5: 21,
		6: 23,
		7: 23,
		8: 23,
		9 : 23,
		10: 22,
		11: 22
	}

	var day = date.getDate();
	var month = date.getMonth();
	var nextMonth = month + 1;
	if (nextMonth > 11) {
		nextMonth = 0;
	}

	if (day >= signStartDaysPerMonth[month]) {
		return signs[nextMonth];
	}

	return signs[month];
}

function getLuckyNumber(birthDateString) {
	var digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
	var result = 0;
	_.forEach(birthDateString, function(char) {
		if (_.includes(digits, char)) {
			result += 0 + parseInt(char, 10);
		}
	});

	if (_.includes([11, 22], result)) {
		return result;
	}

	if (result > 9) {
		return getLuckyNumber(result.toString());
	}

	return result;
}

function minDigits(value, digitsCount) {
	var valStr = value.toString();

	while (valStr.length < digitsCount) {
		valStr = '0' + valStr;
	}

	return valStr;
}

function formatDate(date) {

	if ( ! date instanceof Date) {
		return withError(['formatDate(): date is not valid', date], '');
	}

	return minDigits(date.getDate(), 2) + '-' + minDigits((date.getMonth() + 1), 2) + '-' + date.getFullYear();
}

function dayOfWeek(date) {
	var dayOfWeek = {
		1: 'monday',
		2: 'tuesday',
		3: 'wednesday',
		4: 'thursday',
		5: 'friday',
		6: 'saturday',
		7: 'sunday'
	};

	return dayOfWeek[date.getDay()];
}

function getValuesForTemplate(template) {

console.error('template', template);
	var birthDateText =  $('input[name=birthDate]').val();
	var birthDate = new Date(birthDateText);
	var age = diffDatesInYears(new Date(), birthDate);
	var birthDateString = formatDate(birthDate);
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
		'client-birth-date' : birthDateString,
		'client-sign': getZodiacalSignFromDate(birthDate),
		'client-number': getLuckyNumber(birthDateString),
		'site-default-infocost' : (infocost ? infocost.infocost : ''),
		'site-default-telephone' : (infocost ? infocost.telephone : ''),
		'site-default-country' : country,
		'site-name': currentSite.name,
		'site-url' : currentSite.url,
		'site-sender-name' : currentSite.ownername,
		'site-sender-email' : currentSite.owneremail,
		'site-signature' : currentSite.signature,
		'site-unsubscribe' : currentSite.unsubscribe,
		'template-sender': template.sender_name,
		'template-subject': template.subject,
		'current-date': formatDate(new Date()),
		'current-weekday': dayOfWeek(new Date())
	}
console.error('Template values', result);
	return result;
}

function insertValuesInTemplate(template) {
	var values = getValuesForTemplate(template);
	var content = template.content;
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
		method: 'get',
		dataType: 'json'
	});

	request
		.done(function(template) {
			if (template.category != 'question' ) {
				return;
			}
			setClientInterest(template.type);
		})
		.done(function(template) {
			templateBody = insertValuesInTemplate(template);
			CKEDITOR.instances.rich_editor.setData(templateBody);
			$('#send-email-form').find('[name=content]').val(templateBody);
			if ( ! _.isEmpty(template.sender_name)) {
				$('input[name=sender]').val(template.sender_name);
			}
			if ( ! _.isEmpty(template.subject)) {
				$('input[name=subject]').val(template.subject);
			}

			if (shouldAutoSend()) {
				$('#send-email-form').submit();
			}
		});
}

function onEmailSendSuccess() {
	var href = $('#next-email').attr('href');
	saveClientInterest().done(function() {
		redirect(href, 1);
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

function hideWarningMessage() {
	$warningBox.addClass('hidden');
}

function showWarningMessage(caption) {
	hideWarningMessage();
	$warningBox.find('.caption').html(caption);
	$warningBox.removeClass('hidden');
}


function generateLaravelErrorList(errorList) {
	if(!_.isPlainObject(errorList)) {
		return withError(['generateLaravelErrorList(): errorList is invalid', errorList]);
	}

	var resultHtml = '';

	_.forOwn(errorList, function(messageList, formItem) {
		if (!_.isArray(messageList)) {
			return withError(['generateLaravelErrorList(): messageList should be array', messageList, errorList, formItem]);
		}

		_.forEach(messageList, function(errorMessage) {
			resultHtml += '<li>' + errorMessage + '</li>';
		})
	});

	return '<ul>' + resultHtml + '</ul>';
}

function getKeyFromPlaceholder(placeholder) {
	if (!_.isString(placeholder)) {
		return withError(['getKeyFromPlaceholder(): placeholder is not string', placeholder]);
	}

	if ((placeholder[0] !== '{') || (placeholder.substr(-1) !== '}')) {
		return withError(['getKeyFromPlaceholder(): placeholder is not of form {key}', placeholder], placeholder);
	}

	return placeholder.replace(/[{}]/g, '');
};

function fillPlaceholdersInString(string, data) {
	if(!_.isString(string)) {
		return withError(['fillPlaceholdersInString(): string parameter should be of string type', string]);
	}

	if (!_.isPlainObject(data)) {
		return withError(['fillPlaceholdersInString(): data should be an object', data], string);
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

	showLoader();
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

	request.always(function() {
		hideLoader();
	});

	return request;
}

function submitAjaxForm(form) {
	var request = submitGenericAjaxForm(form);

	var $form = $(form);

	request.done(function(data) {
		if (!_.isPlainObject(data)) {
			return withError(['submitAjaxForm(): ajax create did not receive the created item', data]);
		}

		var successMessage = $form.attr('success-message');
		if (successMessage) {
			showSuccessMessage(successMessage);
		}

		var successUrl = $form.attr('success-url');

		if (successUrl) {
			redirect(fillPlaceholdersInString(successUrl, data), 1000);
		}
		var successFunctionName = $form.attr('success-function');

		if (successFunctionName) {
			if (_.isFunction(window[successFunctionName])) {
				window[successFunctionName]();
			}
		}
	});

	request.fail(function(error) {
		var title = '<strong>' + ($(form).attr('error-message') || 'Error:')  + '</strong><br/>'
		var message = title + generateLaravelErrorList(error.responseJSON);

		showErrorMessage(message);
	});
}

var updateExpandedIndicator = function(element) {
	$element = $(element);
	if ($element.attr("aria-expanded") == "true") {
		$element.find('.glyphicon').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
	}
	else {
		$element.find('.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
	}
}

var resolvedPromise = function() {
	return $.Deferred().resolve().promise();
}

var setClientInterest = function(interest) {
	var interestInput = $('input[name=interest]');

	if ( ! interestInput.length) {
		con
		return false;
	}

	var oldInterest = interestInput.val();
	if (oldInterest == interest) {
		return true;
	}

	interestInput.val(interest);
	interestChanged = true;
	return true;
}

var saveClientInterest = function() {
	if (!interestChanged) {
		return resolvedPromise();
	}

	return submitGenericAjaxForm($('#form-client-informations'));
}

$(function(){
	$successBox = $('.message-box.success');
	$errorBox = $('.message-box.error');
	$warningBox = $('.message-box.warning');
	$warningBox.click(function() {
		hideWarningMessage();
	});


	if ($('#alert-client-opened-too-soon').length) {
		showWarningMessage('This client has been opened by somebody else less than a minute ago!');
	}

	var ckeditorSettings = {
		allowedContent: true,
        autoParagraph: false
	};

	if ($('#rich_editor').length) {
		CKEDITOR.replace('rich_editor', ckeditorSettings);
	}

	if ($('#rich_editor2').length) {
		CKEDITOR.replace('rich_editor2', ckeditorSettings);
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
		updateExpandedIndicator(this);
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
