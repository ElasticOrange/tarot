<table class="table table-striped">
	<thead>
		<tr>
			<th>User can access website</th>
		</tr>
	</thead>
	<tbody>
		@foreach($sites as $site)
			<tr>
				<td>
					<div class="checkbox" >
						<label>
							<input 	type="checkbox"
									name="sites[]"
									value="{{$site->id}}"
									{!! in_array($site->id, $userSiteIds) ? 'checked="checked"' : '' !!}
							/>{{$site->name}}
						</label>
					</div>
				</td>
			</tr>
		@endforeach

	</tbody>
</table>
