
<html>
<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />

	<body>
		

	<div id="container">
	<div id="header">
		<h1>
			LGI Portal
		</h1>
	</div>
	<div id="navigation">
		<ul>
						
		</ul>
	</div>
	<div id="content-container">
		<div id="content">
			<h2>
				Welcome to LGI portal !
			</h2>
			<div id="error">
				<p> {$errormessage} </p>
			</div>
			<p>
				<form action="php/login.php" method="post" class="cmxform">
				
				<fieldset>
				<ol>
					<li>					
        					<label for="name">Name:</label> <input type="text" name="name" id="name" /> <br/>
        			 	</li>
        			 	<li>
        			 		<label for="password">Password:</label> <input type="password" name="password" id="password" /> <br/>
        			 	</li>
        			 	<li>
        			 		<input type="submit" value="Login" />
        			 	</li>
        			 </ol>
				</fieldset>
				</form>
			</p>
			
				
		</div>
		<div id="aside">			
		</div>
		<div id="footer">
			Copyright 2011
		</div>
	</div>
</div>

</html> 

