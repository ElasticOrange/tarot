@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Email box')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Email boxes</pagetitle>
			<form 	class="form"
					action="/emailboxes"
					method="post"
					data-ajax="true"
					success-message="Email box has been created"
					success-url="/emailboxes/{id}"
					error-message="Error creating emai box"
			>
				@include('emailbox/form')
				<div class="form-group">
					<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a href="/emailboxes" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Back</a>
				</div>
			</form>
		</div>
	</div>

@endsection

