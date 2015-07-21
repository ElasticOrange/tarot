@extends('master')

<?php $selected_menu_item = 'My account'?>

@section('title', 'Clients list')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>My account</pagetitle>
			<form 	class="form"
					action=""
					method="post"
					data-ajax="true"
					success-message="Profile updated successfuly!"
					error-message="Error updating profile"
			>
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
					<label>Current password</label>
					<input 	type="password"
							class="form-control"
							name="password"
							value=""
					/>
				</div>
				<div class="form-group">
					<label>New password</label>
					<input 	type="password"
							class="form-control"
							name="newpassword"
							value=""
					/>
				</div>
				<div class="form-group">
					<label>Repeat new password</label>
					<input 	type="password"
							class="form-control"
							name="reppassword"
							value=""
					/>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a class="btn btn-warning" href="/auth/logout" data-confirm="Are you sure you want to Logout?"><span class="glyphicon glyphicon-off"></span> Logout</a>
				</div>
			</form>
		</div>
	</div>


@endsection
