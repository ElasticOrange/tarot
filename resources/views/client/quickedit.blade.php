				<div class="col-sm-12">
					<div class="row">
						<div class="col-md-12">
							<form 	class="form form-horizontal"
									action="/sites/{{ $site->id }}/clients/{{ $client->id }}"
									method="post"
									data-ajax="true"
									success-message="Client updated successfully"
									error-message="Error saving client"
									id="form-client-informations"
							>
								{!! csrf_field() !!}
								<input type="hidden" name="_method" value="PUT"/>
								<h3>
									Client
									@if($subscribtionsCount > 1)
										<span class="glyphicon glyphicon-duplicate" title="Client is registered on more than one site"></span>
									@endif

									@if($client && ($client->problem))
										<span class="glyphicon glyphicon-exclamation-sign" title="Client is flagged as problematic"></span>
									@endif
								</h3>
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<input
												type="text"
												name="email"
												synchronize="email"
												class="form-control"
												value="{{ $client->email }}"
												placeholder="email"
												title="E-mail address"
											/>
										</div>
										<div class="col-md-2">
											<input
												type="text"
												name="firstName"
												synchronize="firstName"
												class="form-control"
												value="{{ $client->firstName }}"
												placeholder="first name"
												title="Name"

											/>
										</div>
										<div class="col-md-1">
											<select name="gender"
													class="form-control"
													placeholder="gender"
													synchronize="gender"
													title="Gender"
											>
												<option value="Female">F</option>
												<option value="Male" {{ ($client->gender == 'Male' ? 'selected="selected"' : '') }}>M</option>
											</select>
										</div>
										<div class="col-md-2">
											<input 	type="date"
													class="form-control"
													name="birthDate"
													synchronize="birthDate"
													value="{{ $client->birthDate }}"
													title="Birthdate"
											/>
										</div>

										<div class="col-md-2">
											<?php
												$combobox = [
													'attributes' => 'class="form-control combobox" name="country" placeholder="country" synchronize="country" title="Country"',
													'options' => $countries,
													'selected' => ($client->country ? $client->country : $site->country)
												];
											?>
											@include('_combobox', $combobox)
										</div>

										<div class="col-md-2">
											<input 	type="text"
													class="form-control"
													value="{{ $client->confirmdate->format('d-m-Y h:m') }}"
													title="Register date/time"
													readonly="readonly"
											/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-md-3">
											<input 	type="text"
													class="form-control"
													name="partnerName"
													synchronize="partnerName"
													value="{{ $client->partnerName }}"
													placeholder="partner name"
													title="Partner name"
											/>
										</div>
										<div class="col-md-2">
											<input
												type="text"
												name="interest"
												synchronize="interest"
												class="form-control"
												value="{{ $client->interest }}"
												placeholder="interest"
												title="Interest"
											/>
										</div>
										<div class="col-md-5">
											<textarea class="form-control">{{ $client->question }}</textarea>
										</div>

										<div class="col-md-1">
											<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
