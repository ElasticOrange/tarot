@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Sites summary')

@section('content')

	<div class="row">
		<div class="col-sm-offset-3 col-sm-6">
			<pagetitle>Sites summary</pagetitle>
		</div>
	</div>
	<p></p>
	@if(!count($sites))
		<div class="alert alert-warning">
			<span class="content-placeholder">There are no sites available for the moment. </span>
		</div>
	@else
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Site</th>
					<th>Questions</th>
					<th>Emails</th>
				</tr>
			</thead>
			<tbody>
				<?php $rowIndex = 0 ?>
				@foreach($sites as $site)
					<tr>
						<td>{{ ++$rowIndex }}</td>
						<td>
							<a href="/sites/{{ $site->id }}/questions">
								<strong>{{ $site->name }}</strong></td>
							</a>
						<td>
							@if($site->clients->count())
								<a href="/sites/{{ $site->id }}/questions">
									<span class="badge">{{ $site->clients->count() }}</span>
								</a>
							@endif
						</td>
						<td>
							@if($site->emails->count())
								<a href="/sites/{{ $site->id }}/emails">
									<span class="badge">{{ $site->emails->count() }}</span>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>

		</table>
	@endif
@endsection
