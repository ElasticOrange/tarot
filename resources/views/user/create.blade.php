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

				<div class="form-group">
					<button class="btn btn-primary">Save</button>
				</div>
			</form>

			@include('user/sitelist')
		</div>
	</div>


@endsection
