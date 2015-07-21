@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'User edit')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>User information</pagetitle>
			<form 	class="form"
					action="/users"
					method="post"
					data-ajax="true"
					success-message="User has been created"
					success-url="/users/{id}"
					error-message="Error creating user"
			>
				@include('user/form')

				@include('user/sitelist')

				<div class="form-group">
					<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a class="btn-default btn" href="/users"><span class="glyphicon glyphicon-list"></span> Back</a>
				</div>
			</form>

		</div>
	</div>


@endsection
