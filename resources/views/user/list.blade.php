@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Users list')

@section('content')

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<pagetitle>Users</pagetitle>
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
			<a href="/users/create/" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</a>
		</div>
	</div>

	@if (count($users) > 0)

		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th class="hidden-xs">Email</th>
					<th>Type</th>
					<th class="hidden-xs">Last login</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
				<?php $rowIndex = 1;?>
				@foreach($users as $user)
					<tr href="/users/{{$user->id}}">
						<td>{{$rowIndex++}}</td>
						<td>{{$user->name}}</td>
						<td class="hidden-xs">{{$user->email}}</td>
						<td>{{$userTypes[$user->type]}}</td>
						<td>21-03-2014 21:20 !!!</td>
						<td>{!! $user->active ? '<span class="glyphicon glyphicon-ok">' : '' !!}</span></td>
					</tr>
				@endforeach
			</tbody>

		</table>

		@include('_pagination')
	@else
		<p>
			<div class="alert alert-warning">
				<span class="content-placeholder">There are no users for the moment. Please <strong>add</strong> one!</span>
			</div>
		</p>
	@endif


@endsection
