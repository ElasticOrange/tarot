@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Infocost edit')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Infocost</pagetitle>
			<form 	class="form"
					action="/sites/{{$site->id}}/infocosts/{{$infocost->id}}"
					method="post"
					data-ajax="true"
					success-message="Infocost informations updated!"
					error-message="Infocost update error"
			>
				<input type="hidden" name="_method" value="PUT"/>
				@include('infocost/form')
				<div class="form-group">
					<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button class="btn-danger btn"
							type="submit"
							name="_method"
							value="DELETE"
							data-confirm="Are you sure you want to delete this infocost?"
					><span class="glyphicon glyphicon-remove"></span> Delete</button>
					<a href="/sites/{{$site->id}}" class="btn btn-default"><span class="glyphicon glyphicon-list"></span>  Back</a>
				</div>

			</form>
		</div>
	</div>
@endsection
