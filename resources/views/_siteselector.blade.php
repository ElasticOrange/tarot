@if(count($loggedUserSites))
	<form class="form">
		<div class="form-group">
			<select class="form-control" data-submit-on-change="true">
				@foreach($loggedUserSites as $id=>$site)
					<option value="{{ $id }}">{{ $site }}</option>
				@endforeach
			</select>
		</div>
	</form>
@endif
