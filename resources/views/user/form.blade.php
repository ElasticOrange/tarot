	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	<div class="form-group">
		<label>Name</label>
		<input 	type="text"
				class="form-control"
				name="name"
				value="{{$user->name}}"
				placeholder="Ex: John Doe"
		/>
	</div>
	<div class="form-group">
		<label>Email</label>
		<input 	type="email"
				class="form-control"
				name="email"
				value="{{$user->email}}"
				placeholder="Ex: johndoe@example.com"
		/>
	</div>
	<div class="form-group">
		<label>User type</label>
		<select name="type"
				class="form-control"
		>	<option></option>
			@foreach($userTypes as $userTypeValue => $userTypeCaption)
				<option value="{{$userTypeValue}}" {!! $user->type == $userTypeValue ? 'selected="selected"' : '' !!} >{{$userTypeCaption}}</option>
			@endforeach
		</select>
	</div>
	<div class="checkbox">
		<input 	type="hidden"
				name="active"
				value="0"
		/>
		<label>
			<input 	type="checkbox"
					name="active"
					value="1"
					{!! $user->active ? 'checked="checked"' : '' !!}
			/> User active
		</label>
	</div>
