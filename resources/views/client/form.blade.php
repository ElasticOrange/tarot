{!! csrf_field() !!}
<div class="form-group">
	<label class="col-xs-2 control-label">Registered time</label>
	<div class="col-xs-10">
		<input 	type="text"
				class="form-control"
				value="{{ $client->confirmdate ? $client->confirmdate->format('d-m-Y h:m') : '' }}"
				readonly="readonly"
		/>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Email</label>
	<div class="col-xs-10">
		<input 	type="email"
				class="form-control"
				name="email"
				value="{{ $client->email }}"
				synchronize="email"
				placeholder="Ex: johndoe@example.com"
		/>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Name</label>
	<div class="col-xs-10">
		<input 	type="text"
				class="form-control"
				name="firstName"
				synchronize="firstName"
				value="{{ $client->firstName }}"
				placeholder="Ex: John"
		/>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Partner name</label>
	<div class="col-xs-10">
		<input 	type="text"
				class="form-control"
				name="partnerName"
				synchronize="partnerName"
				value="{{ $client->partnerName }}"
				placeholder="Ex: Jane Doe"
		/>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Birth date</label>
	<div class="col-xs-10">
		<input 	type="date"
				class="form-control"
				name="birthDate"
				synchronize="birthDate"
				value="{{ $client->birthDate }}"
		/>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Gender</label>
	<div class="col-xs-10">

		<select name="gender"
				class="form-control"
				synchronize="gender"
		>
			<option value="Female">Female</option>
			<option value="Male" {{ ($client->gender == 'Male' ? 'selected="selected"' : '') }}>Male</option>
		</select>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Interest</label>
	<div class="col-xs-10">
		<input 	type="text"
				class="form-control"
				name="interest"
				synchronize="interest"
				value="{{ $client->interest }}"
				placeholder="Ex: love"
		/>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">Country</label>
	<div class="col-xs-10">
		<?php
			$combobox = [
				'attributes' => 'class="form-control combobox" name="country" synchronize="country"',
				'options' => $countries,
				'selected' => ($client->country ? $client->country : $site->country)
			];
		?>
		@include('_combobox', $combobox)
	</div>
</div>
<div class="checkbox">
	<input type="hidden" name="ignore" value="0" />
	<label>
		<input 	type="checkbox"
				name="ignore"
				value="1"
				{{ ($client->ignore ? 'checked="checked"' : '') }}
		/>Ignore client
	</label>
</div>
<div class="checkbox">
	<label>
		<input 	type="checkbox"
				name="problem"
				value="1"
				{{ ($client->problem ? 'checked="checked"' : '') }}
		/>Problem client
	</label>
</div>
<div class="form-group">
		<label class="col-sm-2 control-label">Comment</label>
		<div class="col-sm-12">
			<textarea 	name="comment"
						class="form-control"
						rows="4"
			>{{$client->comment}}</textarea>
		</div>
</div>
