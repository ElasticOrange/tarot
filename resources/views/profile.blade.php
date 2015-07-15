@extends('master')

<?php $selected_menu_item = 'My account'?>

@section('title', 'Clients list')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>My account</pagetitle>
			<form class="form">
				<div class="form-group">
					<label>Name</label>
					<input 	type="text"
							class="form-control"
							name="name"
							value=""
							placeholder="Ex: John Doe"
					/>
				</div>
				<div class="form-group">
					<label>Email</label>
					<input 	type="text"
							class="form-control"
							name="name"
							value=""
							placeholder="Ex: johndoe@example.com"
					/>
				</div>
				<div class="form-group">
					<label>Old password</label>
					<input 	type="password"
							class="form-control"
							name="name"
							value=""
					/>
				</div>
				<div class="form-group">
					<label>New password</label>
					<input 	type="password"
							class="form-control"
							name="name"
							value=""
					/>
				</div>
				<div class="form-group">
					<label>Repeat new password</label>
					<input 	type="password"
							class="form-control"
							name="name"
							value=""
					/>
				</div>
				<div class="form-group">
					<button class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>


@endsection
