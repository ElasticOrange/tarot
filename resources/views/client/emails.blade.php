<div class="row">
	<div class="col-sm-12">
		<h3>Emails</h3>
		<form class="form form-inline">
			<label>View last</label>
			<select name="emailcount"
					class="form-control"
					id="email-count-selector"
			>
				<option>5</option>
				<option>10</option>
				<option>25</option>
				<option>50</option>
			</select>
			<label> emails </label>
		</form>

		<script type="text/template" id="email-container-template">
			<div class="email-container" email-id="<%= id %>">
				<a 	class="email-title colapser"
					data-toggle="collapse"
					href="#email-<%= id %>"
					aria-expanded="false"
					aria-controls="email-<%= id %>"
				>On <%= date %> <%=sender %> wrote</a>
				<div class="email-body collapse"
					 id="email-<%= id %>"
				>
					<%= email %>
					<div class="attachments">
					<%
						if (attachments) {
							_.forEach(attachments, function(attachment) {
					%>
								<a href="/attachments/<%= attachment.file %>" target="_window"><span class="glyphicon glyphicon-file"></span><%= attachment.file %></a>
					<%
							});
						}
					%>
					</div>
				</div>
			</div>
		</script>

		<div class="col-sm-12 emails-container well well-sm" id="user-emails" href="{{ action("EmailsController@lastEmails", [$site, $client->email, 'emailCount']) }}">
		</div>
	</div>
</div>
