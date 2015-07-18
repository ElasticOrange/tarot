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
