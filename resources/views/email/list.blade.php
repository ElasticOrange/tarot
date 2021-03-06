@extends('master')

<?php $selected_menu_item = 'Emails'?>

@section('title', 'Emails list')

@section('content')
	<div class="row">
		<div class="col-sm-3">
			@include('_siteselector')
		</div>
		<div class="col-sm-6">
			<pagetitle>Emails</pagetitle>
		</div>
	</div>

	@if(!count($emails))
		<div class="alert alert-success">
			<span class="content-placeholder">There are no unanswered emails for this site at this moment. </span>
		</div>
	@else
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th class="hidden-xs">Received</th>
					<th># Emails</th>
					<th>Comment</th>
				</tr>
			</thead>
			<tbody>
				<?php $rowIndex = 0 ?>
			@foreach($emails as $email)
@if($email->client and $email->client->ignore)
	<?php continue ?>
@endif
					<tr class="read-email" href="/sites/{{ $site->id }}/clients/{{ $email->from_email }}">
						<td>{{ ++$rowIndex }}</td>
						<td>
								{{ $email->from_name }}
								@if($email->client)
									@if($email->client->problem)
										<span class="glyphicon glyphicon-exclamation-sign" title="Client is flagged as problematic"></span>
									@endif
									@if($email->client->ignore)
										<span class="glyphicon glyphicon-ban-circle" title="Client is flagged as ignored"></span>
									@endif
									@if(! $email->client->isSubscribed())
											<span class="glyphicon glyphicon-remove-sign" title="Client is unsubscribed"></span>
									@endif
								@endif
						</td>
						<td> {{ $email->from_email }}</td>
						<td class="hidden-xs">
							@if ($email->sent_at->isToday())
								{{ date('H:i', $email->sent_at->timestamp) }}
							@else
								{{ date('d-m-Y', $email->sent_at->timestamp) }}
							@endif
						</td>
						<td>{{ $email->email_count }}</td>
						<td>
							@if($email->client)
								{{ $email->client->comment }}
							@else
								<strong>Not subscribed to this site</strong>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>

		</table>
	@endif
@endsection
