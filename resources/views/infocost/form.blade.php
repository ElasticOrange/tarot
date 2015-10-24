	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
	<div class="form-group">
		<label>Country</label>

		<select class="form-control"
				name="country"
		>
			<option value=""></option>
			@foreach ($countries as $country)
				<option value="{{$country}}" {{$infocost->country == $country ? 'selected' : ''}}>{{$country}}</option>
			@endforeach
		</select>
	</div>
	<div class="form-group">
		<label>Telephone</label>
		<input	type="text"
				name="telephone"
				class="form-control"
				placeholder="ex: 021 22 33 44"
				value="{{$infocost->telephone}}"
		/>
	</div>
	<div class="form-group">
		<label>Infocost</label>
		<textarea 	name="infocost"
					rows="3"
					class="form-control"
					id="rich_editor"
		>{{$infocost->infocost}}</textarea>
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
					{{ $infocost->active ? 'checked="checked"' : '' }}
			/>Active
		</label>
	</div>
	<div class="checkbox">
		<input 	type="hidden"
				name="default"
				value="0"
		/>
		<label>
			<input  type="checkbox"
					name="default"
					value="1"
					{{ $infocost->default ? 'checked="checked"' : '' }}
			/>Default
		</label>
	</div>
