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
					<div class="checkbox">
						<label>
							<input 	type="checkbox"
									name=""
									value=""
							/>{{$site->name}}
						</label>
					</div>
				</td>
			</tr>
		@endforeach

	</tbody>
</table>
