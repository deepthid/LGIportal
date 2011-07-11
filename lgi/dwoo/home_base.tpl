{*
	This file is the base template for a page that user sees after logging in. This is template is used by home.php, submit.php, delete.php etc. Replace only the "content" to use this template in other pages.
	Add more options to this file in block "menu"
*}

<html>
<link rel="stylesheet" href="../css/layout.css" type="text/css" media="screen" />

	<body>

	<div id="container">
	<div id="header">
		{block "heading"}
		<h1>
			LGI Portal
		</h1>
		{/block}
	</div>
	<div id="navigation">
		{block "menu"}
		<ul>
			<li><a href="#">Submit Job</a></li>
			<li><a href="#">Delete Job</a></li>
			<li><a href="#">View Job</a></li>			
			<li><a href="logout.php">Logout</a></li>
			
		</ul>
		{/block}
	</div>
	<div id="content-container">
		<div id="content">
		{block "content"}
			<h2>
				Welcome {$user} !
			</h2>
			<p>
				You are now logged into LGI portal.
			</p>
			
		{/block}		
		</div>
		<div id="aside">
		{block "aside"}
		{/block}			
		</div>
		<div id="footer">
			Copyright 2011
		</div>
	</div>
</div>
</html> 
