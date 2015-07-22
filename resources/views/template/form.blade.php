
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			{!! csrf_field() !!}
			<label>Name</label>
			<input 	type="text"
					class="form-control"
					name="name"
					value="{{ $template->name }}"
					placeholder="Ex: Template name"
			/>
		</div>
		<div class="form-group">
			<input 	type="hidden"
					name="category"
					value="{{ $template->category }}"
			/>
			<label>Type</label>
			<input 	type="text"
					class="form-control"
					name="type"
					value="{{ $template->type }}"
					placeholder="Ex: Template type"
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
							<li><a href="#">First name</a></li>
							<li><a href="#">Last name</a></li>
							<li><a href="#">Partner name</a></li>
							<li><a href="#">Gender</a></li>
							<li><a href="#">Interest</a></li>
							<li><a href="#">Age</a></li>
							<li><a href="#">Date of birth</a></li>
							<li><a href="#">Info cost</a></li>
							<li><a href="#">Country</a></li>
							<li><a href="#">Site name</a></li>
							<li><a href="#">Site URL</a></li>
							<li><a href="#">Sender name</a></li>
						</ul>
					</div>
				</div>
			</div>
			<textarea 	name="content"
						class="form-control"
						rows="20"
						id="rich-editor"
			></textarea>
		</div>
	</div>
</div>

