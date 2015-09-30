@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'User edit')
@section('content')

	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
    	<li role="presentation"><a href="#client-info" aria-controls="client-info" role="tab" data-toggle="tab">Client information</a></li>
 	</ul>

 	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="messages">
			@if($client->id)
				<div class="row">
					<div class="col-md-12">
						<form 	class="form form-horizontal"
								action="/sites/{{ $site->id }}/clients/{{ $client->id }}"
								method="post"
								data-ajax="true"
								success-message="Client updated successfully"
								error-message="Error saving client"
						>
							{!! csrf_field() !!}
							<input type="hidden" name="_method" value="PUT"/>
							<h3>
								Client
								@if($subscribtionsCount && ($subscribtionsCount > 1))
									<span class="glyphicon glyphicon-duplicate" title="Client is registered on more than one site"></span>
								@endif

								@if($client && ($client->problem))
									<span class="glyphicon glyphicon-exclamation-sign" title="Client is flagged as problematic"></span>
								@endif
							</h3>
							<div class="row">
								<div class="form-group">
									<div class="col-md-3">
										<input
											type="text"
											name="email"
											synchronize="email"
											class="form-control"
											value="{{ $client->email }}"
											placeholder="email"
										/>
									</div>
									<div class="col-md-2">
										<input
											type="text"
											name="firstName"
											synchronize="firstName"
											class="form-control"
											value="{{ $client->firstName }}"
											placeholder="first name"
										/>
									</div>
									<div class="col-md-2">
										<input
											type="text"
											name="lastName"
											synchronize="lastName"
											class="form-control"
											value="{{ $client->lastName }}"
											placeholder="last name"
										/>
									</div>
									<div class="col-md-1">
										<select name="gender"
												class="form-control"
												placeholder="gender"
												synchronize="gender"
										>
											<option value="Female">F</option>
											<option value="Male" {{ ($client->gender == 'Male' ? 'selected="selected"' : '') }}>M</option>
										</select>
									</div>
									<div class="col-md-2">
										<input 	type="date"
												class="form-control"
												name="birthDate"
												synchronize="birthDate"
												value="{{ $client->birthDate }}"
										/>
									</div>

									<div class="col-md-2">
										<?php
											$combobox = [
												'attributes' => 'class="form-control combobox" name="country" placeholder="country" synchronize="country"',
												'options' => ['Australia', 'England', 'USA'],
												'selected' => $client->country
											];
										?>
										@include('_combobox', $combobox)
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="col-md-3">
										<input 	type="text"
												class="form-control"
												name="partnerName"
												synchronize="partnerName"
												value="{{ $client->partnerName }}"
												placeholder="partner name"
										/>
									</div>
									<div class="col-md-7">
										<textarea class="form-control">{{ $client->question }}</textarea>
									</div>

									<div class="col-md-1">
										<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			@endif
			@include('client.emails')
			@include('client.respond')
		</div>

		<div role="tabpanel" class="tab-pane" id="client-info">
			<div class="row">
				<div class="col-sm-6">
					<h3>Client information</h3>
					<form 	class="form form-horizontal"
							action="/sites/{{ $site->id }}/clients/{{ $client->id }}"
							method="post"
							data-ajax="true"
							success-message="Client updated successfully"
							error-message="Error saving client"
					>
						<input type="hidden" name="_method" value="PUT"/>
						@include('client.form')

						<div class="form-group">
							<div class="col-sm-12">
								<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
								<a href="/sites/{{ $site->id }}/clients" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Back</a>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6">
				@include('client.sites')
				</div>
			</div>
		</div>
 	</div>

 	@if($alertClientOpenedToSoon)
 		<div id="alert-client-opened-too-soon"></div>
 	@endif

@endsection
