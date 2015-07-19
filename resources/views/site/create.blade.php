@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Site')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Website</pagetitle>
			<form 	class="form" action="/sites"
					method="post"
					data-ajax="true"
					success-message="Site has been created"
					success-url="/sites/{id}"
					error-message="Error creating site"
			>
				@include('site/form')
				<div class="form-group">
					<button class="btn btn-primary">Save</button>
					<a href="/sites" class="btn btn-default">Back</a>
				</div>
			</form>
		</div>
	</div>

@endsection

