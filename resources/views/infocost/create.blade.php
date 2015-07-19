@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Infocost')

@section('content')

	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Infocost</pagetitle>

			<form 	class="form" action="/sites/{{$site->id}}/infocosts"
					method="post"
					data-ajax="true"
					success-message="Infocost has been created"
					success-url="/sites/{{$site->id}}/infocosts/{id}"
					error-message="Error creating infocost"
			>
				@include('infocost/form')
				<div class="form-group">
					<button class="btn btn-primary">Save</button>
					<a href="/sites/{{$site->id}}" class="btn btn-default">Back</a>
				</div>
			</form>
		</div>
	</div>

@endsection

