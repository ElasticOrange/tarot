
<div class="row">
	<div class="col-sm-6">
		{!! csrf_field() !!}
		<input 	type="hidden"
				name="category"
				value="{{ $template->category }}"
		/>
		<div class="form-group">
			<label>Name</label>
			<input 	type="text"
					class="form-control"
					name="name"
					value="{{ $template->name }}"
					placeholder="Ex: Template name"
			/>
		</div>
		<div class="form-group">
			<label>Type</label>
			<?php
				$combobox = [
					'attributes' => 'class="form-control combobox" name="type"',
					'options' => $templateTypes->toArray(),
					'selected' => $template->type
				];
			?>
			@include('_combobox', $combobox)
		</div>
		<div class="form-group">
			<label>Sender name</label>
			<input 	type="text"
					class="form-control"
					name="sender_name"
					value="{{ $template->sender_name }}"
					placeholder="Ex: John Doe"
			/>
		</div>
		<div class="form-group">
			<label>Subject</label>
			<input 	type="text"
					class="form-control"
					name="subject"
					value="{{ $template->subject }}"
					placeholder="Ex: Here is your reading"
			/>
		</div>
		<div class="form-group">
			<div class="checkbox">
				<input type="hidden" name="active" value="0"/>
				<label><input 	type="checkbox"
								name="active"
								value="1"
								{{ $template->active ? 'checked="checked"' : '' }}
				/> Active</label>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<label>Content</label>
			<div class="row">
				<div class="col-sm-12">
					<div class="btn-group">
						<button 	type="button"
									class="btn btn-default dropdown-toggle"
									data-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false"
						>Placeholders <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="#" data-insert-text="%%%client-first-name%%%">First name</a></li>
							<li><a href="#" data-insert-text="%%%client-last-name%%%">Last name</a></li>
							<li><a href="#" data-insert-text="%%%client-partner-name%%%">Partner name</a></li>
							<li><a href="#" data-insert-text="%%%client-gender%%%">Gender</a></li>
							<li><a href="#" data-insert-text="%%%client-interest%%%">Interest</a></li>
							<li><a href="#" data-insert-text="%%%client-age%%%">Age</a></li>
							<li><a href="#" data-insert-text="%%%client-birth-date%%%">Date of birth</a></li>
							<li><a href="#" data-insert-text="%%%client-sign%%%">Client zodiacal sign</a></li>
							<li><a href="#" data-insert-text="%%%client-number%%%">Client destiny number</a></li>
							<li><a href="#" data-insert-text="%%%site-default-infocost%%%">Info cost</a></li>
							<li><a href="#" data-insert-text="%%%site-default-telephone%%%">Telephone</a></li>
							<li><a href="#" data-insert-text="%%%site-default-country%%%">Country</a></li>
							<li><a href="#" data-insert-text="%%%site-name%%%">Site name</a></li>
							<li><a href="#" data-insert-text="%%%site-url%%%">Site URL</a></li>
							<li><a href="#" data-insert-text="%%%site-sender-name%%%">Sender name</a></li>
							<li><a href="#" data-insert-text="%%%site-sender-email%%%">Sender email</a></li>
							<li><a href="#" data-insert-text="%%%site-signature%%%">Site signature</a></li>
							<li><a href="#" data-insert-text="%%%site-unsubscribe%%%">Site unsubscribe</a></li>
							<li><a href="#" data-insert-text="%%%template-sender%%%">Template sender</a></li>
							<li><a href="#" data-insert-text="%%%template-subject%%%">Template subject</a></li>
							<li><a href="#" data-insert-text="%%%current-date%%%">Current date</a></li>
							<li><a href="#" data-insert-text="%%%current-weekday%%%">Current day of week</a></li>

						</ul>
					</div>
				</div>
			</div>
			<textarea 	name="content"
						class="form-control"
						rows="40"
						id="rich_editor"
			>{{ $template->content }}</textarea>
		</div>
	</div>
</div>

