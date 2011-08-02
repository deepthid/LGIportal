{*****************************************************************************************
 *	The template for submiting a job. It extends the homs_base.tpl. 
 * 	The heading, menus, footer are same as base template. Only the content is changed. 
 *****************************************************************************************}

{extends "home_base.tpl"}
{block "content"}
			<h2>
				Welcome to LGI portal !
			</h2>
			
				<form action="submit.php" method="post" class="cmxform">				
				<fieldset>
				<ol>
					<li>					
        					<label for="application">Application:</label> <input type="text" name="application" id="applicaton" /> <br/>
        			 	</li>
        			 	<li>					
        					<label for="server">Server Url:</label> <input type="text" name="server" id="server" /> <br/>
        			 	</li>
        			 	<li>					
        					<label for="project">Project:</label> <input type="text" name="project" id="project" /> <br/>
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
        			 	{*** <li>
        			 		<label for="file">File Upload</label> <input type="file" name="file" id="file" /> <br/>
        			 	</li> (temporarily removed) **}
        			 	
        			 	<li>
        			 		<input type="submit" value="Submit Job" />
        			 		<input type="hidden" value="request" name="submitrequest"/>
        			 		<input type="hidden" value={$nonce} name="nonce"/>
        			 	</li>
        			 </ol>
				</fieldset>
				</form>
			
			
{/block}

