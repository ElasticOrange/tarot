<h3>Client subscriptions</h3>
<form action="/sites/{{ $site->id }}/clients/{{ $client->id }}/subscribe" method="post">
	{!! csrf_field() !!}
	@include('client.siteslist')
	<div class="form-group">
		<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
	</div>
</form>
