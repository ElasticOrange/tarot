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
				@include('client.quickedit')
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

 	@if($subscribtionsCount > 1)
 		<div id="alert-client-many-registrations"></div>
 	@endif
@endsection
