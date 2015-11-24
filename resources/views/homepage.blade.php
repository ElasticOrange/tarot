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
	@if(empty($sitesData))
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
				@foreach($sitesData as $siteData)
					<tr>
						<td>{{ ++$rowIndex }}</td>
						<td>
							<a href="/sites/{{ $siteData['site']->id }}/questions">
								<strong>{{ $siteData['site']->name }}</strong></td>
							</a>
						<td>
							@if($siteData['unrespondedQuestions'])
								<a href="/sites/{{ $siteData['site']->id }}/questions">
									<span class="badge">{{ $siteData['unrespondedQuestions'] }}</span>
								</a>
							@endif
						</td>
						<td>
							@if($siteData['unrespondedEmails'])
								<a href="/sites/{{ $siteData['site']->id }}/emails">
									<span class="badge">{{ $siteData['unrespondedEmails'] }}</span>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>

		</table>
	@endif
@endsection
