@extends('master')

<?php $selected_menu_item = 'Questions'?>

@section('title', 'Questions list')

@section('content')

	<div class="row">
		<div class="col-sm-3">
			@include('_siteselector')
		</div>
		<div class="col-sm-6">
			<pagetitle>Questions</pagetitle>
		</div>
		<div class="col-sm-3">
			<form class="form">
				<div class="form-group">
					<input 	type="text"
							class="form-control"
							name="table-search"
							placeholder="Search in table"
					/>
				</div>
			</form>
		</div>
	</div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Email</th>
				<th class="hidden-xs">Received</th>
			</tr>
		</thead>
		<tbody>
			<tr class="undread-email" href="/client">
				<td>1</td>
				<td>Mark Wahlberg</td>
				<td>markiemark@gmail.com</td>
				<td class="hidden-xs">13:30</td>
			</tr>
			<tr class="undread-email" href="/client">
				<td>2</td>
				<td>Mark Wahlberg</td>
				<td>markiemark@gmail.com</td>
				<td class="hidden-xs">13:30</td>
			</tr>
			<tr href="/client">
				<td>3</td>
				<td>Mark Wahlberg</td>
				<td>markiemark@gmail.com</td>
				<td class="hidden-xs">13:30</td>
			</tr>
			<tr href="/client">
				<td>4</td>
				<td>Mark Wahlberg</td>
				<td>markiemark@gmail.com</td>
				<td class="hidden-xs">13-feb-2013</td>
			</tr>
		</tbody>

	</table>

	<div class="row">
		<div class="col-sm-6">
			<nav>
			  <ul class="pagination no-margins">
			    <li>
			      <a href="#" aria-label="Previous">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
			    </li>
			    <li><a href="#">1</a></li>
			    <li><a href="#">2</a></li>
			    <li><a href="#">3</a></li>
			    <li><a href="#">4</a></li>
			    <li><span href="#" class="">...</span></li>
			    <li><a href="#">55</a></li>
			    <li>
			      <a href="#" aria-label="Next">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
			    </li>
			  </ul>
			</nav>
		</div>
		<div class="col-sm-offset-3 col-sm-3">
			<form class="form form-inline">
				<div class="form-group">
					<label class="">Items per page</label>
					<select class="form-control">
						<option>10</option>
						<option>25</option>
						<option>50</option>
						<option>100</option>
						<option>300</option>
					</select>
				</div>
			</form>
		</div>
	</div>

@endsection
