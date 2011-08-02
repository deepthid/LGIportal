{*****************************************************************************************
 *	The template for deleting a job. It extends the homs_base.tpl. 
 * 	The heading, menus, footer are same as base template. Only the content is changed. 
 *****************************************************************************************}

{extends "home_base.tpl"}
{block "content"}
			<h2>
				Welcome to LGI portal !
			</h2>
			
				<form action="delete.php" method="post" class="cmxform">				
				<fieldset>
				<ol>
					
					<li>					
        					<label for="jobid">Job ID:</label> <input type="text" name="jobid" id="jobid" /> <br/>
        			 	</li>
        			 	<li>					
        					<label for="server">Server Url:</label> <input type="text" name="server" id="server" /> <br/>
        			 	</li>
        			 	<li>					
        					<label for="project">Project:</label> <input type="text" name="project" id="project" /> <br/>
        			 	</li>
        			 	
        			 	<li>
        			 		<input type="submit" value="Delete Job" />
        			 		<input type="hidden" value="request" name="submitrequest"/>
        			 		<input type="hidden" value={$nonce} name="nonce"/>
        			 	</li>
        			 </ol>
				</fieldset>
				</form>
			
			
{/block}

