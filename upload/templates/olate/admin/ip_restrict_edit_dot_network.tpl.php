<tr {if: !empty($error_fields[$entry[id]]['start'])}
style="background-color: #FFCECE;"{endif}>
	<td>{lang:admin:ip_restrict_network_address}</td>
	<td>
		<input type="text" name="entries[{$entry[id]}][start]" value="{$entry[start]}" />
	</td>
</tr>
<tr {if: !empty($error_fields[$entry[id]]['mask'])}
style="background-color: #FFCECE;"{endif}>
	<td>{lang:admin:ip_restrict_subnet_mask}</td>
	<td>
		<input type="text" name="entries[{$entry[id]}][mask]" value="{$entry[mask]}" />
	</td>
</tr>
