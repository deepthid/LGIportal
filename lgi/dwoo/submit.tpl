<html>
<link rel="stylesheet" href="../css/layout.css" type="text/css" media="screen" />

	<body>
		

	<div id="container">
	<div id="header">
		<h1>
			LGI Portal
		</h1>
	</div>
	<div id="navigation">
		<ul>
			<li><a href="submit.php">Submit Job</a></li>
			<li><a href="logout.php">Logout</a></li>
			
		</ul>		
		
	</div>
	<div id="content-container">
		<div id="content">
			<h2>
				Welcome to LGI portal !
			</h2>
			<p>
				<form action="php/submitjob.php" method="post" class="cmxform">				
				<fieldset>
				<ol>
					<li>					
        					<label for="application">Application:</label> <input type="text" name="application" id="applicaton" /> <br/>
        			 	</li>
        			 	<li>
        			 		<label for="readaccess">Extra read access:</label> <input type="text" name="readaccess" id="readaccess" /> <br/>
        			 	</li>
        			 	<li>
        			 		<label for="writeaccess">Extra write access:</label> <input type="text" name="writeaccess" id="writeaccess" /> <br/>
        			 	</li>
        			 	<li>
        			 		<label for="target">Target Resources:</label> <input type="text" name="target" id="target" /> <br/>
        			 	</li>
        			 	<li>
        			 		<label for="jobspecifics">Job Specifics:</label> <input type="text" name="jobspecifics" id="jobspecifics" /> <br/>
        			 	</li>
        			 	<li>
        			 		<label for="file">File Upload</label> <input type="file" name="file" id="file" /> <br/>
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