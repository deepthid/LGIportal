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
			<li><a href="home.php">Home</a></li>
			<li><a href="submit.php">Submit Job</a></li>
			<li><a href="delete.php">Delete Job</a></li>
			<li><a href="viewjob.php">View Job</a></li>
			<li><a href="listjobs.php">List all Jobs</a> </li>
			<li><a href="listresources.php">List all Resources</a> </li>				
		</ul>
		<div id="user">
		<li><a href="logout.php">Logout {$user}</a></li>
		</div>
		{/block}
	</div>
	<div id="content-container">
		<div id="error">
		<p> {$errormessage} </p>
		</div>
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
