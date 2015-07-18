@extends('master')

<?php $selected_menu_item = ''?>

@section('title', 'Site')

@section('content')


	<div class="row">
		<div class="col-sm-6">
			<pagetitle>Website</pagetitle>
			<form class="form" action="/sites/{{$site->id}}" method="post">
				<input type="hidden" name="_method" value="PUT"/>
				@include('site/form')
			</form>
		</div>
		@include('infocost/table')
	</div>

@endsection

@include('infocost/form')
