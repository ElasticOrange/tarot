<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Tarot</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li <?=$selected_menu_item == 'Questions' ? 'class="active"' : ''?>><a href="/questions">Questions</a></li>
				<li <?=$selected_menu_item == 'Emails' ? 'class="active"' : ''?>><a href="/emails">Emails</a></li>
				<li <?=$selected_menu_item == 'Clients' ? 'class="active"' : ''?>><a href="/clients">Clients</a></li>
				<li <?=$selected_menu_item == 'My account' ? 'class="active"' : ''?>><a href="/profile">My account</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/sites">Sites</a></li>
						<li><a href="/users">Users</a></li>
						<li><a href="/questiontemplates">Question templates</a></li>
						<li><a href="/emailtemplates">Email templates</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="/settings">Settings</a></li>
					</ul>
				</li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>