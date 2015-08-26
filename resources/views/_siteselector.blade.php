@if(count($loggedUserSites))
	<form class="form" action="/sites/change" method="post">
		<div class="form-group">
			{!! csrf_field() !!}
			<select class="form-control" name="siteId" data-submit-on-change="true">
				@foreach($loggedUserSites as $id=>$site)
					<option value="{{ $id }}" {!! $id == $currentSiteId ? 'selected="selected"' : '' !!}>{{ $site }}</option>
				@endforeach
			</select>
		</div>
	</form>
@endif
