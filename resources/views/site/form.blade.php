	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	<div class="form-group">
		<label>Name</label>
		<input 	type="text"
				class="form-control"
				name="name"
				value="{{ $site->name }}"
				placeholder="Ex: Sitename"
		/>
	</div>
	<div class="form-group">
		<label>URL</label>
		<input 	type="text"
				class="form-control"
				name="url"
				value="{{ $site->url }}"
				placeholder="Ex: http://sitename.com/..."
		/>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<input type="hidden" name="active" value="0"/>
			<label><input 	type="checkbox"
							name="active"
							value="1"
							{{ $site->active ? 'checked="checked"' : '' }}
			/> Active</label>
		</div>
	</div>
	<div class="form-group">
		<label>Sender name</label>
		<input 	type="text"
				class="form-control"
				name="sender"
				value="{{ $site->sender }}"
				placeholder="Ex: John Doe"
		/>
	</div>
	<div class="form-group">
		<label>Sender email</label>
		<input 	type="email"
				class="form-control"
				name="email"
				value="{{ $site->email }}"
				placeholder="Ex: admin@sitename.com"
		/>
	</div>
	<div class="form-group">
		<label>Mail subject</label>
		<input 	type="text"
				class="form-control"
				name="subject"
				value="{{ $site->subject }}"
				placeholder="Ex: subject of mail"
		/>
	</div>
	<div class="form-group">
		<label>Signature</label>
		<textarea
				class="form-control"
				name="signature"
				rows="5"
		>{{ $site->signature }}</textarea>
	</div>
