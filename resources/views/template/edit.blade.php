@extends('master')

<?php $selected_menu_item = ''?>

@section('title',  ucfirst($template->category) . ' answer template')

@section('content')


	<div class="row">
		<div class="col-sm-12">
			<pagetitle>Question answer template</pagetitle>
			<form 	class="form"
					action="/sites/{{ $site->id }}/templates/{{ $template->id }}"
					method="post"
					data-ajax="true"
					success-message="Template updated successfully"
					error-message="Error saving template"
			>
				<input type="hidden" name="_method" value="PUT"/>
				@include('template.form')
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
							<a 	class="btn-danger btn"
								href="/sites/{{$site->id}}/templates/{{ $template->id }}/delete"
								data-confirm="Are you sure you want to delete this template?"
							><span class="glyphicon glyphicon-remove"></span> Delete</a>
							<a class="btn btn-default" href="/sites/{{ $site->id }}/templates/{{ $template->category }}">
								<span class="glyphicon glyphicon-list"></span> Back
							</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>


@endsection
