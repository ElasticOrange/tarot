@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'User edit')

@section('content')

	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#client-info" aria-controls="client-info" role="tab" data-toggle="tab">Client information</a></li>
 	</ul>

 	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="client-info">
			<div class="row">
				<div class="col-sm-6">
					<h3>Client information</h3>
					<form 	class="form form-horizontal"
							action="/sites/{{ $site->id }}/clients"
							method="post"
							data-ajax="true"
							success-message="Client created successfully"
							success-url="/sites/{{ $site->id }}/clients/{subscriberid}"
							error-message="Error saving client"
					>
						@include('client.form')

						<div class="form-group">
							<div class="col-sm-12">
								<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
								<a href="/sites/{{ $site->id }}/clients" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Back</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

 	</div>

@endsection
