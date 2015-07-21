@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'User edit')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>User profile</pagetitle>
			<form 	class="form"
					action="/users/{{$user->id}}"
					method="post"
					data-ajax="true"
					success-message="User informations updated!"
					error-message="User update error"
			>
				<input type="hidden" name="_method" value="PUT">
				@include('user/form')


				<div class="form-group">
					<a 	class="btn-danger btn"
						href="/users/{{$user->id}}/delete"
						data-confirm="Are you sure you want to delete this user?"
					><span class="glyphicon glyphicon-remove"></span> Delete user</a>
				</div>

				<div class="form-group">
					<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a class="btn-default btn" href="/users"><span class="glyphicon glyphicon-list"></span> Back</a>
				</div>

				<div class="form-group">
					@include('user/sitelist')

				</div>

				<div class="form-group">
					<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a class="btn-default btn" href="/users"><span class="glyphicon glyphicon-list"></span> Back</a>
				</div>
			</form>
		</div>
	</div>


@endsection
