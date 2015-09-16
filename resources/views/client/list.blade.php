@extends('master')

<?php $selected_menu_item = 'Clients'?>

@section('title', 'Clients list')

@section('content')

	<div class="row">
		<div class="col-sm-3">
			@include('_siteselector')
		</div>
		<div class="col-sm-6">
			<pagetitle>Clients</pagetitle>
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

	@if($clients->count())

		<table class="table table-striped">
			<thead>
				<tr href="/client">
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th class="hidden-xs">Gender</th>
					<th>Country</th>
					<th class="hidden-xs">Interests</th>
					<th class="hidden-xs">Last email</th>
					<th class="hidden-xs">Last response</th>
					<th class="hidden-xs"># Emails</th>
					<th class="hidden-xs">Comments</th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;?>
				@foreach($clients as $client)
					<?php $row++;?>
					<tr href="/sites/{{ $site->id }}/clients/{{ $client->id }}">
						<td>{{ $row }}</td>
						<td>{{ $client->firstName }} {{$client->lastName }}</td>
						<td>{{ $client->emailaddress }}</td>
						<td class="hidden-xs">{{ $client->gender }}</td>
						<td>{{ $client->country }}</td>
						<td class="hidden-xs">{{ $client->interest }}</td>
						<td class="hidden-xs">
							@if($client->emails->count())
								{{ date('d-m-Y', $client->emails->first()->sent_at->timestamp) }}
							@endif
						</td>
						<td class="hidden-xs">
							@if($client->sentEmails->count())
								{{ date('d-m-Y', $client->sentEmails->first()->sent_at->timestamp) }}
							@endif</td>
						<td class="hidden-xs">
							@if($client->emails->count())
								@if ($client->emails->count() > 20)
									more than 20
								@else
									{{ $client->emails->count() }}
								@endif
							@endif
						</td>
						<td class="hidden-xs">{{$client->comment }}</td>
					</tr>
				@endforeach

			</tbody>

		</table>
		@include('_pagination')

		@else
			<p>
				<div class="alert alert-info">
					<span class="content-placeholder">There are no clients for the moment. Please <strong>add</strong> one!</span>
				</div>
			</p>
		@endif

@endsection
