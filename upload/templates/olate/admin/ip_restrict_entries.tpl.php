<h2>{lang:admin:ip_restrict_entries}</h2>
{if: empty($disable_sort) || $disable_sort === false}
<p class="small">
	{lang:frontend:sort_by} 
{if: $sort_field == 'id' && $sort_direc == 'asc'}
		<a href="admin.php?cmd=ip_restrict_main&amp;sort_field=id&amp;sort_direc=desc" title="{lang:frontend:sort_by} ID {lang:frontend:descending}">ID</a> 
		<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" />, 
{else}
		<a href="admin.php?cmd=ip_restrict_main&amp;sort_field=id&amp;sort_direc=asc" title="{lang:frontend:sort_by} ID {lang:frontend:ascending}">ID</a> 
		<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" />, 
{endif}
	
{if: $sort_field == 'type' && $sort_direc == 'asc'}
		<a href="admin.php?cmd=ip_restrict_main&amp;sort_field=type&amp;sort_direc=desc" title="{lang:frontend:sort_by} {lang:admin:ip_restrict_type} {lang:frontend:descending}">{lang:admin:ip_restrict_type}</a> 
		<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" />, 
{else}
		<a href="admin.php?cmd=ip_restrict_main&amp;sort_field=type&amp;sort_direc=asc" title="{lang:frontend:sort_by} {lang:admin:ip_restrict_type} {lang:frontend:ascending}">{lang:admin:ip_restrict_type}</a> 
		<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" />, 
{endif}
	
{if: $sort_field == 'active' && $sort_direc == 'asc'}
		<a href="admin.php?cmd=ip_restrict_main&amp;sort_field=active&amp;sort_direc=desc" title="{lang:frontend:sort_by} {lang:admin:status} {lang:frontend:descending}">{lang:admin:status}</a> 
		<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" />
{else}
		<a href="admin.php?cmd=ip_restrict_main&amp;sort_field=active&amp;sort_direc=asc" title="{lang:frontend:sort_by} {lang:admin:status} {lang:frontend:ascending}">{lang:admin:status}</a> 
		<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" />
{endif}
</p>
{endif}
<form action="admin.php?cmd=ip_restrict_main" method="post">
	<table border="0" cellspacing="0" class="ip_list">
		<tr>
			<th></th>
			<th></th>
			<th>{lang:admin:enabled}</th>
			<th>{lang:admin:details}</th>
			<th></th>
		</tr>
{block:filter_row}
		<tr class="ip_restrict_row" id="ip_list_{$filter_row[id]}" onclick="check('ip_check_{$filter_row[id]}')" onmouseover="this.className='ip_restrict_row_hover';" onmouseout="this.className='ip_restrict_row';">
			<td>
				<input type="checkbox" name="ip_check_{$filter_row[id]}" id="ip_check_{$filter_row[id]}" value="1" onclick="check('ip_check_{$filter_row[id]}')"/>
			</td>
			<td valign="middle">
{if: $filter_row[type] == 1}
				<img src="templates/olate/images/mycomputer.png" alt="{lang:admin:ip_restrict_ip_address}" />
{elseif: $filter_row[type] == 2}
				<img src="templates/olate/images/range.png" alt="{lang:admin:ip_restrict_ip_range}" />
{else}
				<img src="templates/olate/images/network.png" alt="{lang:admin:ip_restrict_network}" />
{endif}
			</td>
			<td valign="middle" align="center">
{if: $filter_row[active] == 1}
				<input type="checkbox" name="filter_{$filter_row[id]}_enabled" checked="checked" value="1" disabled="disabled" />
{else}
				<input type="checkbox" name="filter_{$filter_row[id]}_enabled" value="0" disabled="disabled" />
{endif}
			</td>
			<td valign="middle">
{if: $filter_row[type] == 1}
					<strong>{lang:admin:ip_restrict_ip_address}:</strong> {$filter_row[start]}
{elseif: $filter_row[type] == 2}
					<strong>{lang:admin:ip_restrict_ip_range}:</strong> {$filter_row[start]} - {$filter_row[end]}
{else}
					<strong>{lang:admin:ip_restrict_network}:</strong> {$filter_row[start]}/{$filter_row[mask]}
{endif}
			</td>
			<td valign="middle" class="controls">
				<a href="admin.php?cmd=ip_restrict_main&amp;submit_edit=1&amp;ip_check_{$filter_row[id]}=1"><img src="templates/olate/images/edit.png" alt="edit" /></a> 
				<a href="admin.php?cmd=ip_restrict_main&amp;submit_delete=1&amp;ip_check_{$filter_row[id]}=1"><img src="templates/olate/images/editdelete.png" alt="delete" /></a>
			</td>
		</tr>
{/block:filter_row}
		<tr>
			<td colspan="5">
				<input type="submit" value="{lang:admin:ip_restrict_enable_selected}" name="submit_enable" /> 
				<input type="submit" value="{lang:admin:ip_restrict_disable_selected}" name="submit_disable" />
				<input type="submit" value="{lang:admin:ip_restrict_edit_selected}" name="submit_edit" />
				<input type="submit" value="{lang:admin:ip_restrict_delete_selected}" name="submit_delete" />
			</td>
		</tr>
	</table>
</form>