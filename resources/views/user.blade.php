@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Settings')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Settings</pagetitle>
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
					<button class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>


@endsection
