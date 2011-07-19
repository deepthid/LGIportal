{extends "home_base.tpl"}
{block "content"}
			<table border="1">
                         <tr>
                         <th>Job ID</th>
                         <td>{$jobId}</td>
                         </tr>
                         <tr>
                         <th>Application</th>
                         <td>{$application}</td>
                         </tr>
                         <tr>
                         <th>State</th>
                         <td>{$jobStatus}</td>
                         </tr>
                         <tr>
                         <th>Owners</th>
                         <td>{$jobOwner}</td>
                         </tr>
                         <tr>
                         <th>Read Access</th>
                         <td>{$readAccess}</td>
                         </tr>
                          <tr>
                         <th>Target Resources</th>
                         <td>{$target}</td>
                         </tr>
                         </table>
			
{/block}