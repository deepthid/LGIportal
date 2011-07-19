{*****************************************************************************************
 *	The template for listing all jobs. It extends the homs_base.tpl. 
 * 	The heading, menus, footer are same as base template. Only the content is changed. 
 *****************************************************************************************}

{extends "home_base.tpl"}
{block "content"}
			<h2>
				Welcome to LGI portal !
			</h2>
			
				<form action="listjobs.php" method="post" class="cmxform">				
				<fieldset>
				<ol>					
        			 	<li>					
        					<label for="server">Server Url:</label> <input type="text" name="server" id="server" /> <br/>
        			 	</li>        			 	
        			 	<li>
        			 		<input type="submit" value="Show all Jobs" />
        			 		<input type="hidden" value="request" name="submitrequest"/>
        			 	</li>
        			 </ol>
				</fieldset>
				</form>
			
			
{/block}

