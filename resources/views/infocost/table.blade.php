
<div class="col-sm-6">
	<pagetitle>Info Cost</pagetitle>
	<p>
		<a class="btn btn-primary" href="/sites/{{$site->id}}/infocosts/create"><span class="glyphicon glyphicon-plus"></span> Add</a>
	</p>
	<table class="table table-stiped">
		<thead>
			<tr>
				<th>Default</th>
				<th>Country</th>
				<th>Telephone</th>
				<th>Info cost</th>
				<th>Active</th>
			</tr>
		</thead>
		<tbody>
		@foreach($infocosts as $infocost)
			<tr  href="/sites/{{ $site->id }}/infocosts/{{ $infocost->id }}">
				<td class="prevent-href">
					@if($infocost->active)
						<input type="radio" {!! $infocost->default ? 'checked="checked"' : '' !!} name="default[{{$infocost->country}}]" value="{{$infocost->id}}"/>
					@endif
				</td>
				<td>{{$infocost->country}}</td>
				<td>{{$infocost->telephone}}</td>
				<td>{{$infocost->infocost}}</td>
				<td>{!!$infocost->active ? '<span class="glyphicon glyphicon-ok"></span>' : ''!!}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
