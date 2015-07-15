@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Users list')

@section('content')

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<pagetitle>Users</pagetitle>
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
				<th class="hidden-xs">Email</th>
				<th>Type</th>
				<th class="hidden-xs">Last login</th>
				<th>Active</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>User Name</td>
				<td class="hidden-xs">username@example.com</td>
				<td>Administrator</td>
				<td>21-03-2014 21:20</td>
				<td><span class="glyphicon glyphicon-ok"></span></td>
			</tr>
			<tr>
				<td>1</td>
				<td>User Name</td>
				<td class="hidden-xs">username@example.com</td>
				<td>Administrator</td>
				<td>21-03-2014 21:20</td>
				<td><span class="glyphicon glyphicon-ok"></span></td>
			</tr>
			<tr>
				<td>1</td>
				<td>User Name</td>
				<td class="hidden-xs">username@example.com</td>
				<td>Administrator</td>
				<td>21-03-2014 21:20</td>
				<td><span class="glyphicon glyphicon-ok"></span></td>
			</tr>
			<tr>
				<td>1</td>
				<td>User Name</td>
				<td class="hidden-xs">username@example.com</td>
				<td>Administrator</td>
				<td>21-03-2014 21:20</td>
				<td><span class="glyphicon glyphicon-ok"></span></td>
			</tr>
			<tr>
				<td>1</td>
				<td>User Name</td>
				<td class="hidden-xs">username@example.com</td>
				<td>Administrator</td>
				<td>21-03-2014 21:20</td>
				<td><span class="glyphicon glyphicon-ok"></span></td>
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