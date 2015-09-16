@extends('master')

<?php $selected_menu_item = 'Questions'?>

@section('title', 'Questions list')

@section('content')

	<div class="row">
		<div class="col-sm-3">
			@include('_siteselector')
		</div>
		<div class="col-sm-6">
			<pagetitle>Questions</pagetitle>
		</div>
	</div>

	@if(!$clients or $clients->isEmpty())
		<div class="alert alert-success">
			<span class="content-placeholder">There are no unanswered question for this site at this moment. </span>
		</div>
	@else
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th class="hidden-xs" style="min-width: 100px">Received</th>
					<th class="hidden-xs">Question</th>

				</tr>
			</thead>
			<tbody>
				<?php $rowIndex = 0; ?>
				@foreach($clients as $client)
					<tr href="/sites/{{ $site->id }}/clients/{{ $client->id }}">
						<td>{{ ++$rowIndex }}</td>
						<td>{{ $client->fullName }}</td>
						<td>{{ $client->email }}</td>
						<td>
							@if ($client->confirmdate->isToday())
								{{ date('H:i', $client->confirmdate->timestamp) }}
							@else
								{{ date('d-m-Y', $client->confirmdate->timestamp) }}
							@endif
						</td>
						<td class="hidden-xs">{{ $client->question }}</td>
					</tr>
				@endforeach
			</tbody>

		</table>
	@endif
@endsection
