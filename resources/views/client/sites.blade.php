<h3>Client subscriptions</h3>
<form action="/sites/{{ $site->id }}/clients/{{ $client->id }}/subscribe" method="post">
	{!! csrf_field() !!}
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 20px"></th>
				<th>Website</th>
			</tr>
		</thead>
		<tbody>
			@foreach($sites_with_client as $site_w_client)
				<tr
					@if($site_w_client->hasUserSubscribed)
						href="/sites/{{ $site_w_client->id }}/clients/{{ $site_w_client->clients->first()->id }}"
					@endif
				>
					<td class="prevent-href">
						<input 	type="checkbox"
								name="siteids[]"
								value="{{ $site_w_client->id }}"
								{{ ( $site_w_client->hasUserSubscribed ? 'checked="checked"' : '') }}
						/>
					</td>
					<td
						@if($site->id == $site_w_client->id)
							class="bold"
						@endif
					>
						{{ $site_w_client->name }}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="form-group">
		<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
	</div>
</form>
