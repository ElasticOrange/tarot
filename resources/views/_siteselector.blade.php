@if(count($loggedUserSites))
	<form class="form" action="/sites/change" method="post">
		<div class="form-group">
			{!! csrf_field() !!}
			<select class="form-control" name="siteId" data-submit-on-change="true">
				@foreach($loggedUserSites as $site)
					<option value="{{ $site->id }}" {!! $site->id == $currentSiteId ? 'selected="selected"' : '' !!}>{{ $site->name }}</option>
				@endforeach
			</select>
		</div>
	</form>
@endif
