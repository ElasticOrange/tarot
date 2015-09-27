@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Email box')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Email box</pagetitle>
			<form 	class="form"
					action="/emailboxes/{{$emailbox->id}}"
					method="post"
					data-ajax="true"
					success-message="Email box updated successfully!"
					error-message="Email box update error"
			>
				<input type="hidden" name="_method" value="PUT"/>
				@include('emailbox/form')
				<div class="form-group">
					<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a 	class="btn-danger btn"
						href="/emailboxes/{{$emailbox->id}}/delete"
						data-confirm="Are you sure you want to delete this email box?"
					><span class="glyphicon glyphicon-remove"></span> Delete</a>
					<a href="/emailboxes" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Back</a>
				</div>

			</form>
		</div>

	</div>

@endsection
