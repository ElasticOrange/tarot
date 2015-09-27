	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	<div class="hidden">
		<input type="password">
	</div>


	<div class="form-group">
		<label>Name</label>
		<input 	type="text"
				class="form-control"
				name="name"
				value="{{ $emailbox->name }}"
				placeholder="Ex: Site email box"
		/>
	</div>

	<div class="form-group">
		<h3>Smtp settings</h3>
	</div>

	<div class="form-group">
		<label>Server address</label>
		<input 	type="text"
				class="form-control"
				name="smtpServer"
				value="{{ $emailbox->smtpServer }}"
				placeholder="Ex: smtp.server.com"
		/>
	</div>

	<div class="form-group">
		<label>Server port</label>
		<input 	type="text"
				class="form-control"
				name="smtpPort"
				value="{{ $emailbox->smtpPort }}"
				placeholder="Ex: 465"
		/>
	</div>

	<div class="form-group">
		<label>Encryption</label>

		<select class="form-control"
				name="smtpEncryption"
		>
			<option value="">None</option>
			<option value="ssl" {{ ($emailbox->smtpEncryption == 'ssl' ? 'selected="selected"' : '') }}>SSL</option>
			<option value="tls" {{ ($emailbox->smtpEncryption == 'tsl' ? 'selected="selected"' : '') }}>TLS</option>
		</select>
	</div>


	<div class="form-group">
		<label>Username</label>
		<input 	type="text"
				class="form-control"
				name="smtpUsername"
				value="{{ $emailbox->smtpUsername }}"
				placeholder="Ex: name@server.com"
		/>
	</div>


	<div class="form-group">
		<label>Password</label>
		<input 	type="password"
				class="form-control"
				name="smtpPassword"
				placeholder=""
		/>
	</div>

	<div class="form-group">
		<h3>Imap/pop settings</h3>
	</div>

	<div class="form-group">
		<label>Protocol</label>

		<select	name="imapProtocol"
				class="form-control"
		>
			<option value="pop">POP</option>
			<option value="imap" {{ ($emailbox->imapProtocol == 'imap' ? 'selected="selected"' : '') }}>IMAP</option>
		</select>
	</div>

	<div class="form-group">
		<label>Server address</label>
		<input 	type="text"
				class="form-control"
				name="imapServer"
				value="{{ $emailbox->imapServer }}"
				placeholder="Ex: imap.server.com"
		/>
	</div>

	<div class="form-group">
		<label>Server port</label>
		<input 	type="text"
				class="form-control"
				name="imapPort"
				value="{{ $emailbox->imapPort }}"
				placeholder="Ex: 993"
		/>
	</div>

	<div class="form-group">
		<label>Encryption</label>

		<select name="imapEncryption"
				class="form-control"
		>
			<option value="">None</option>
			<option value="ssl" {{ ($emailbox->imapEncryption == 'ssl' ? 'selected="selected"' : '') }}>SSL</option>
			<option value="tls" {{ ($emailbox->imapEncryption == 'tsl' ? 'selected="selected"' : '') }}>TLS</option>
		</select>
	</div>

	<div class="form-group">
		<label>Folder</label>
		<input 	type="text"
				class="form-control"
				name="imapFolder"
				value="{{ $emailbox->imapFolder or 'INBOX' }}"
				placeholder="Ex: INBOX"
		/>
	</div>


	<div class="form-group">
		<label>Username</label>
		<input 	type="text"
				class="form-control"
				name="imapUsername"
				value="{{ $emailbox->imapUsername }}"
				placeholder="Ex: name@server.com"
		/>
	</div>


	<div class="form-group">
		<label>Password</label>
		<input 	type="password"
				class="form-control"
				name="imapPassword"
				placeholder=""
		/>
	</div>




	<div class="form-group">
		<label>Comment</label>
		<textarea
				class="form-control"
				name="comment"
				rows="5"
		>{{ $emailbox->comment }}</textarea>
	</div>
