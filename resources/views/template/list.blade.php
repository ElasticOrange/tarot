@extends('master')

<?php $selected_menu_item = ''?>

@section('title', ucfirst($templateCategory) . ' templates list')

@section('content')

	<div class="row">
		<div class="col-sm-3">
			@include('_siteselector')
		</div>
		<div class="col-sm-6">
			<pagetitle>{{ ucfirst($templateCategory) }} answer templates</pagetitle>
		</div>
	</div>
	<form method="post">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-sm-12 form-inline">
				<a class="btn btn-primary" href="/sites/{{$site->id}}/templates/{{$templateCategory}}/create"><span class="glyphicon glyphicon-plus"></span> Add new</a>
				<button class="btn btn-danger" type="submit" data-confirm="Are you sure you want to DELETE the selected templates?" formaction="{{ action('TemplatesController@bulkDelete', [$site]) }}"><span class="glyphicon glyphicon-remove"></span> Remove</button>
				<button class="btn btn-warning" type="submit" data-confirm="Are you sure you want to COPY the selected templates?" formaction="{{ action('TemplatesController@bulkCopy', [$site]) }}"><span class="glyphicon glyphicon-duplicate"></span> Copy to</button>
				<select name="site" class="form-control inline">
					@foreach($loggedUserSites as $site)
						<option value="{{ $site->id }}" {{ $site->id == $currentSiteId ? 'selected="selected"' : '' }}>{{ $site->name }}</option>
					@endforeach
				</select>
			</div>
		</div>

		@if($templates->count())
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th>Name</th>
						<th>Type</th>
						<th>Active</th>
					</tr>
				</thead>
				<tbody>
					<?php $row = 0;?>
					@foreach($templates as $template)
						<?php $row++;?>
						<tr href="/sites/{{$site->id}}/templates/{{ $template->id }}">
							<td>{{ $row }}</td>
							<td class="prevent-href"><input type="checkbox" name="id[]" value="{{ $template->id }}"/></td>
							<td>{{ $template->name }}</td>
							<td>{{ $template->type }}</td>
							<td>{!! $template->active ? '<span class="glyphicon glyphicon-ok"></span>' : '' !!}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			@include('_pagination')
		@else
			<p>
				<div class="alert alert-info">
					<span class="content-placeholder">There are no templates for the moment. Please <strong>add</strong> one!</span>
				</div>
			</p>
		@endif
	</form>

@endsection
