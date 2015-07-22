@extends('master')

<?php $selected_menu_item = ''?>

@section('title', ucfirst($template->category) . ' answer template')

@section('content')


	<div class="row">
		<div class="col-sm-12">
			<pagetitle>{{ ucfirst($template->category) }} answer template</pagetitle>
			<form 	class="form"
					action="/sites/{{ $site->id }}/templates"
					method="post"
					data-ajax="true"
					success-message="Template created successfuly"
					error-message="Error creating template"
					success-url="/sites/{{ $site->id }}/templates/{id}"
			>

				@include('template.form')
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
							<a class="btn btn-default" href="/sites/{{$site->id}}/templates/{{$template->category}}">
								<span class="glyphicon glyphicon-list"></span> Back
							</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>


@endsection
