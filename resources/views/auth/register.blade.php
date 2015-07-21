@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'User edit')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>User profile</pagetitle>
			<form 	class="form"
					action="/auth/register"
					method="post"
			>
				<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
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
					<label>Email</label>
					<input 	type="email"
							class="form-control"
							name="email"
							value=""
							placeholder="Ex: johndoe@example.com"
					/>
				</div>
				<div class="form-group">
					<label>User type</label>
					<select name="type"
							class="form-control"
					>
						<option>Administrator</option>
						<option>Editor</option>
						<option>Responder</option>
						<option>Guest</option>
					</select>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="active" value="1"/>User active</label>
				</div>
				<div class="form-group">
					<a class="btn-danger btn" href="/users/delete/" data-confirm="Are you sure you want to delete this user?"><span class="glyphicon glyphicon-remove"></span> Delete user</a>
				</div>
				<div class="form-group">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>User can access website</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="checkbox">
										<label>
											<input 	type="checkbox"
													name=""
													value=""
											/>Site1
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="checkbox">
										<label>
											<input 	type="checkbox"
													name=""
													value=""
											/>Site1
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="checkbox">
										<label>
											<input 	type="checkbox"
													name=""
													value=""
											/>Site1
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="checkbox">
										<label>
											<input 	type="checkbox"
													name=""
													value=""
											/>Site1
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="checkbox">
										<label>
											<input 	type="checkbox"
													name=""
													value=""
											/>Site1
										</label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="form-group">
					<button class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>


@endsection