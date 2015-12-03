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
	</div>

	@if($clients->count())

		<table class="table table-striped" id="clients-table">
			<thead>
				<tr href="/client">
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th class="visible-lg">Gender</th>
					<th class="visible-lg">Country</th>
					<th class="visible-lg">Registered</th>
					<th class="visible-lg">Last email</th>
					<th class="visible-lg">Last response</th>
					<th class="visible-lg"># Emails</th>
					<th class="visible-lg">Comments</th>
				</tr>
			</thead>
			<tbody>
				<?php $row = 0;?>
				@foreach($clients as $client)
					<?php $row++;?>
					<tr href="/sites/{{ $site->id }}/clients/{{ $client->id }}">
						<td>{{ $row }}</td>
						<td>
							{{ $client->firstName }} {{ $client->lastName }} -> {{ $client->fullName }}
							@if($client->problem)
								<span class="glyphicon glyphicon-exclamation-sign" title="Client is flagged as problematic"></span>
							@endif
							@if($client->problem)
								<span class="glyphicon glyphicon-ban-circle" title="Client is flagged as ignored"></span>
							@endif
							@if($client->unsubscribed)
								<span class="glyphicon glyphicon-remove-sign" title="Client is unsubscribed"></span>
							@endif
						</td>
						<td>{{ $client->emailaddress }}</td>
						<td class="visible-lg">{{ $client->gender }}</td>
						<td class="visible-md">{{ $client->country }}</td>
						<td class="visible-md">{{ $client->confirmdate }}</td>
						<td class="visible-lg">
							@if($client->emails->count())
								{{ date('d-m-Y', $client->emails->first()->sent_at->timestamp) }}
							@endif
						</td>
						<td class="visible-lg">
							@if($client->sentEmails->count())
								{{ date('d-m-Y', $client->sentEmails->first()->sent_at->timestamp) }}
							@endif</td>
						<td class="visible-lg">
							@if($client->emails->count())
								@if ($client->emails->count() > 20)
									more than 20
								@else
									{{ $client->emails->count() }}
								@endif
							@endif
						</td>
						<td class="visible-lg">{{$client->comment }}</td>
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
