@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Site')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Website</pagetitle>
			<form 	class="form"
					action="/sites/{{$site->id}}"
					method="post"
					data-ajax="true"
					success-message="Site informations updated!"
					error-message="Site update error"
			>
				<input type="hidden" name="_method" value="PUT"/>
				@include('site/form')
				<div class="form-group">
					<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button class="btn-danger btn"
							type="submit"
							name="_method"
							value="DELETE"
							data-confirm="Are you sure you want to delete the current site?"
					><span class="glyphicon glyphicon-remove"></span> Delete</button>
					<a href="/sites" class="btn btn-default"><span class="glyphicon glyphicon-list"></span>  Back</a>
				</div>

			</form>
		</div>

		@include('infocost/table')
	</div>

@endsection

@include('infocost/form')
