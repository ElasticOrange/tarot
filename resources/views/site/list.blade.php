@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Sites list')

@section('content')

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<pagetitle>Sites</pagetitle>
		</div>
		<div class="col-sm-3">
			<form class="form">
				<div class="form-group">
					<input 	type="text"
							class="form-control"
							name="table-search"
							placeholder="Search in table"
					/>
				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<a href="/sites/create" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add </a>
		</div>
	</div>


	<p>
		@if (count($sites) > 0)
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th class="hidden-xs">URL</th>
						<th>Active</th>
					</tr>
				</thead>
				<tbody>
					<?php $rowIndex = 1 ?>
					@foreach ($sites as $site)
						<tr href="/sites/{{ $site->id }}">
							<td>{{$rowIndex++}}</td>
							<td>{{ $site->name }}</td>
							<td class="hidden-xs">{{ $site->url }}</td>
							<td> {!! $site->active ? '<span class="glyphicon glyphicon-ok"></span>' : '' !!}</td>
						</tr>
					@endforeach
				</tbody>

			</table>
		@else
			<div class="alert alert-info">
				<span class="content-placeholder">There are no sites for the moment. Please <strong>add</strong> one!</span>
			</div>
		@endif
	</p>

@endsection
