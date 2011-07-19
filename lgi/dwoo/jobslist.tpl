{extends "home_base.tpl"}
{block "content"}
			<table border="1">
                         <tr>
                         <th>Job ID</th>
                         <th>Application</th>
                         <th>State</th>
                          <th>Owners</th>
                          <th>Target Resources</th>
                          </tr>
                         {loop $jobs}
    						
    						<tr>
    						<td> {$jobId} </td>    						
    						<td> {$application} </td>
    						<td> {$jobStatus} </td>
    						<td> {$jobOwner} </td>
    						<td> {$target} </td>
    						</tr>
						{/loop}
                         </table>
			
{/block}
