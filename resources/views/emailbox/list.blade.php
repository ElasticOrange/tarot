@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Email boxes list')

@section('content')

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<pagetitle>Email boxes</pagetitle>
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
			<a href="/emailboxes/create" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add </a>
		</div>
	</div>

	<p>
		@if (count($emailboxes) > 0)
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th class="hidden-xs">Sites</th>
						<th>Comment</th>
					</tr>
				</thead>
				<tbody>
					<?php $rowIndex = 1 ?>
					@foreach ($emailboxes as $emailbox)
						<tr href="/emailboxes/{{ $emailbox->id }}">
							<td>{{$rowIndex++}}</td>
							<td>{{ $emailbox->name }}</td>
							<td class="hidden-xs">
								@if(!$emailbox->sites->isEmpty())
									@foreach($emailbox->sites as $site)
										{{ $site->name }}<br/>
									@endforeach
								@endif
							</td>
							<td> {{ $emailbox->comment }}</td>
						</tr>
					@endforeach
				</tbody>

			</table>
		@else
			<div class="alert alert-info">
				<span class="content-placeholder">There are no email boxes for the moment. Please <strong>add</strong> one!</span>
			</div>
		@endif
	</p>

@endsection
