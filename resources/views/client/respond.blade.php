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
				@foreach($templates as $template)
					<li><a href="#" template-id="{{ $template->id }}"> {{ $template->type }} {{ $template->name }}</a></li>
				@endforeach
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-2 visible-lg-block">
		<div class="btn-group-vertical template-button-group">

			<?php $index = 0; ?>
			@foreach($templates as $template)
				<button class="btn btn-default" template-id="{{ $template->id }}"> {{ $template->type }} {{ $template->name }} </button>

				<?php $index++ ?>

				@if ($index >= (count($templates) / 2))
		</div>
	</div>
	<div class="col-sm-2 visible-lg-block">
		<div class="btn-group-vertical template-button-group">
				@endif
			@endforeach
		</div>
	</div>

	<div class="col-md-3 visible-md-block">
		<div class="btn-group-vertical template-button-group">
			@foreach($templates as $template)
				<button class="btn btn-default" template-id="{{ $template->id }}"> {{ $template->type }} {{ $template->name }} </button>
			@endforeach
		</div>
	</div>

	<div class="col-sm-12 col-lg-8 col-md-9">
		<form 	class="form"
				action="/sites/{{$site->id}}/sendmail"
				method="post"
				data-ajax="true"
				success-message="Client updated successfully"
				error-message="Error saving client"
		>
			<div class="row">
				{!! csrf_field() !!}
				<input type="hidden" name ="email" synchronize="email" value="{{ $client->email }}" />
				<div class="col-md-2">
					<label class="control-labels">Sender</label>
				</div>
				<div class="col-md-10">
					<input class="form-control" type="text" name="sender"/>
				</div>
				<div class="col-md-2">
					<label class="control-labels">Subject</label>
				</div>
				<div class="col-md-10">
					<input class="form-control" type="text" name="subject"/>
				</div>
			</div>

			<div class="form-group">
			 	<textarea 	name="content"
			 				class="form-control"
			 				id="rich_editor"
			 	></textarea>
			</div>

			<div class="">
				<button class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span> Send response</button>
				<a class="btn btn-info" href="/sites/{{ $site->id }}/nextquestion/{{ $client->id }}"><span class="glyphicon glyphicon-forward"></span> Next email</a>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="send_after_template_fill">
						Automaticly send email
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="mark_as_responded">
						Mark as responded
					</label>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var currentSite = {!! $site !!}

	var infocosts = {!! $infocosts !!};

</script>
