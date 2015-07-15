@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Site')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Website</pagetitle>
			<form class="form">
				<div class="form-group">
					<label>Name</label>
					<input 	type="text"
							class="form-control"
							name="name"
							value=""
							placeholder="Ex: John Doe"
					/>
				</div>
				<div class="form-group">
					<label>URL</label>
					<input 	type="text"
							class="form-control"
							name="url"
							value=""
							placeholder="Ex: www.sitename.com"
					/>
				</div>
				<div class="form-group">
					<label>Sender name</label>
					<input 	type="text"
							class="form-control"
							name="sender"
							value=""
							placeholder="Ex: John Doe"
					/>
				</div>
				<div class="form-group">
					<label>Sender email</label>
					<input 	type="email"
							class="form-control"
							name="email"
							value=""
							placeholder="Ex: www.sitename.com"
					/>
				</div>
				<div class="form-group">
					<label>Mail subject</label>
					<input 	type="text"
							class="form-control"
							name="subject"
							value=""
							placeholder="Ex: subject of mail"
					/>
				</div>
				<div class="form-group">
					<label>Signature</label>
					<textarea
							class="form-control"
							name="signature"
							rows="5"
					></textarea>
				</div>
				<div class="form-group">
					<button class="btn btn-primary">Save</button>
					<a href="/sites" class="btn btn-default">Back</a>
				</div>
			</form>
		</div>

		<div class="col-sm-6">
			<pagetitle>Countries - Phones - Info Cost</pagetitle>
			<p>
				<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</button>
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
					<tr>
						<td><input type="radio"/></td>
						<td>England</td>
						<td>999 999 999</td>
						<td>$4.5/minute</td>
						<td><span class="glyphicon glyphicon-ok"></span></td>
					</tr>
					<tr>
						<td><input type="radio"/></td>
						<td>England</td>
						<td>999 999 999</td>
						<td>$4.5/minute</td>
						<td><span class="glyphicon glyphicon-ok"></span></td>
					</tr>
					<tr>
						<td><input type="radio"/></td>
						<td>England</td>
						<td>999 999 999</td>
						<td>$4.5/minute</td>
						<td><span class="glyphicon glyphicon-ok"></span></td>
					</tr>
					<tr>
						<td><input type="radio"/></td>
						<td>England</td>
						<td>999 999 999</td>
						<td>$4.5/minute</td>
						<td><span class="glyphicon glyphicon-ok"></span></td>
					</tr>
					<tr>
						<td><input type="radio"/></td>
						<td>England</td>
						<td>999 999 999</td>
						<td>$4.5/minute</td>
						<td><span class="glyphicon glyphicon-ok"></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

@endsection

@section('boxes')
<div id="infocost-edit" class="floating hidden">
	<div class="row">
		<div class="col-sm-offset-3 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<pagetitle>Infocost</pagetitle>
					<form class="form">
						<div class="form-group">
							<label>Country</label>
							<input	type="text"
									name="country"
									class="form-control"
									placeholder="ex: England"
							/>
						</div>
						<div class="form-group">
							<label>Telephone</label>
							<input	type="text"
									name="telephone"
									class="form-control"
									placeholder="ex: 021 22 33 44"
							/>
						</div>
						<div class="form-group">
							<label>Infocost</label>
							<textarea 	name="infocost"
										rows="3"
										class="form-control"
							></textarea>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" />Active</label>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" />Default</label>
						</div>
						<div class="form-group">
							<button class="btn btn-danger" type="submit">Delete</button>
							<button class="btn btn-primary" type="submit">Save</button>
							<button class="btn btn-default" type="button">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
