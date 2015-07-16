@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Question answer template')

@section('content')


	<div class="row">
		<div class="col-sm-12">
			<pagetitle>Question answer templated</pagetitle>
			<form class="form">

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label>Name</label>
							<input 	type="text"
									class="form-control"
									name="name"
									value=""
									placeholder="Ex: Template name"
							/>
						</div>
						<div class="form-group">
							<label>Type</label>
							<select name="type"
									class="form-control combobox"
							>
								<option value=""></option>
								<option>one</option>
								<option>two</option>
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label>Content</label>
							<div class="row">
								<div class="col-sm-12">
									<div class="btn-group">
										<button 	type="button"
													class="btn btn-default dropdown-toggle"
													data-toggle="dropdown"
													aria-haspopup="true"
													aria-expanded="false"
										>Placeholders <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="#">First name</a></li>
											<li><a href="#">Last name</a></li>
											<li><a href="#">Partner name</a></li>
											<li><a href="#">Gender</a></li>
											<li><a href="#">Interest</a></li>
											<li><a href="#">Age</a></li>
											<li><a href="#">Date of birth</a></li>
											<li><a href="#">Info cost</a></li>
											<li><a href="#">Country</a></li>
											<li><a href="#">Site name</a></li>
											<li><a href="#">Site URL</a></li>
											<li><a href="#">Sender name</a></li>
										</ul>
									</div>
								</div>
							</div>
							<textarea 	name="content"
										class="form-control"
										rows="20"
										id="rich-editor"
							></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<button class="btn btn-primary">Save</button>
							<button class="btn btn-danger">Delete</button>
							<button class="btn btn-default">Back</button>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>


@endsection
