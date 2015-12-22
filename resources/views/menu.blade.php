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
			<a class="navbar-brand" href="/">Tarot</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li <?=$selected_menu_item == 'Questions' ? 'class="active"' : ''?>><a href="/questions">Questions</a></li>
				<li <?=$selected_menu_item == 'Emails' ? 'class="active"' : ''?>><a href="/emails">Emails</a></li>
				<li <?=$selected_menu_item == 'Clients' ? 'class="active"' : ''?>><a href="/clients">Clients</a></li>
				<li <?=$selected_menu_item == 'My account' ? 'class="dropdown active"' : 'class="dropdown" ' ?>>
					<a href="#"
						class="dropdown-toggle"
						data-toggle="dropdown"
						role="button"
						aria-haspopup="true"
						aria-expanded="true"
					>
						<span class="glyphicon glyphicon-user"></span> {{ $user->name }} <span class="caret">
					</a>

					<ul class="dropdown-menu">
						<li><a href="/profile">Edit profile</a><li>
						<li><a href="/auth/logout">Logout</a></li>
					</ul>
				</li>
			</ul>
			@if(\Auth::user()->type == \App\User::ADMINISTRATOR)
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/emailboxes">Email boxes</a><li>
						<li><a href="/sites">Sites</a><li>
						<li><a href="/users">Users</a></li>
						<li><a href="/templates/question">Question templates</a></li>
						<li><a href="/templates/email">Email templates</a></li>
					</ul>
				</li>
			</ul>
			@endif
		</div><!--/.nav-collapse -->
	</div>
</nav>
