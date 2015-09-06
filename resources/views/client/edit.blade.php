@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'User edit')

@section('content')

	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#client-info" aria-controls="client-info" role="tab" data-toggle="tab">Client information</a></li>
	    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
 	</ul>

 	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="client-info">
			<div class="row">
				<div class="col-sm-6">
					<h3>Client information</h3>
					<form 	class="form form-horizontal"
							action="/sites/{{ $site->id }}/clients/{{ $client->id }}"
							method="post"
							data-ajax="true"
							success-message="Client updated successfully"
							error-message="Error saving client"
					>
						<input type="hidden" name="_method" value="PUT"/>
						@include('client.form')

						<div class="form-group">
							<div class="col-sm-12">
								<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6">
				@include('client.sites')
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="messages">
			<div class="row">
				<div class="col-sm-12">
					<h3>Emails</h3>
					<form class="form form-inline">
						<label>View last</label>
						<select name="emailcount"
								class="form-control"
						>
							<option>5</option>
							<option>10</option>
							<option>25</option>
							<option>50</option>
						</select>
						<label>/20 (Total)</label>
					</form>

					<div class="col-sm-12 emails-container well well-sm">
						<div class="email-container">
							<a class="email-title colapser" data-toggle="collapse" href="#email-1" aria-expanded="true" aria-controls="email-1">
								On 15 mar 2015 client wrote
							</a>
							<div class="email-body collapse in" id="email-1">
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
							</div>
						</div>

						<div class="email-container">
							<a class="email-title colapser" data-toggle="collapse" href="#email-2" aria-expanded="false" aria-controls="email-2">
								On 15 mar 2015 Tarot wrote
							</a>
							<div class="email-body collapse" id="email-2">
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
							</div>
						</div>

						<div class="email-container">
							<a class="email-title colapser" data-toggle="collapse" href="#email-3" aria-expanded="false" aria-controls="email-3">
								On 15 mar 2015 client wrote
							</a>
							<div class="email-body collapse" id="email-3">
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<h3>Respond</h3>
				</div>
			</div>

			<div class="row visible-sm-block visible-xs-block">
				<div class="col-xs-12">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							Templates
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li><a href="#">Love male template 1</a></li>
							<li><a href="#">Love male template 2</a></li>
							<li><a href="#">Love male template 3</a></li>
							<li><a href="#">Love male template 4</a></li>
							<li><a href="#">Money template 1</a></li>
							<li><a href="#">Money template 2</a></li>
							<li><a href="#">Money template 3</a></li>
							<li><a href="#">Money template 4</a></li>
							<li><a href="#">Love female template 1</a></li>
							<li><a href="#">Love female template 2</a></li>
							<li><a href="#">Love female template 3</a></li>
							<li><a href="#">Love female template 4</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-2 visible-lg-block">
					<div class="btn-group-vertical">
						<button class="btn btn-default">Love male template 1</button>
						<button class="btn btn-default">Love male template 2</button>
						<button class="btn btn-default">Love male template 3</button>
						<button class="btn btn-default">Love male template 4</button>
						<button class="btn btn-default">Money template 1</button>
						<button class="btn btn-default">Money template 2</button>
						<button class="btn btn-default">Money template 3</button>
					</div>
				</div>
				<div class="col-sm-2 visible-lg-block">
					<div class="btn-group-vertical">
						<button class="btn btn-default">Love female template 1</button>
						<button class="btn btn-default">Love female template 2</button>
						<button class="btn btn-default">Love female template 3</button>
						<button class="btn btn-default">Love female template 4</button>
					</div>
				</div>

				<div class="col-md-3 visible-md-block">
					<div class="btn-group-vertical">
						<button class="btn btn-default">Love male template 1</button>
						<button class="btn btn-default">Love male template 2</button>
						<button class="btn btn-default">Love male template 3</button>
						<button class="btn btn-default">Love male template 4</button>
						<button class="btn btn-default">Money template 1</button>
						<button class="btn btn-default">Money template 2</button>
						<button class="btn btn-default">Money template 3</button>
						<button class="btn btn-default">Love female template 1</button>
						<button class="btn btn-default">Love female template 2</button>
						<button class="btn btn-default">Love female template 3</button>
						<button class="btn btn-default">Love female template 4</button>
					</div>
				</div>

				<div class="col-sm-12 col-lg-8 col-md-9">
					<form class="form">
						<div class="form-group">
						 	<textarea 	name="response"
						 				class="form-control"
						 				id="rich-editor"
						 	></textarea>
						</div>
						<div class="">
							<button class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span> Send response</button>
							<button class="btn btn-info"><span class="glyphicon glyphicon-forward"></span> Next email</button>
						</div>
					</form>
				</div>
			</div>
		</div>
 	</div>

@endsection
