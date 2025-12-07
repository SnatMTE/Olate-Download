<tr {if: !empty($error_fields[$entry[id]]['start'])}
style="background-color: #FFCECE;"{endif}>
	<td>{lang:admin:ip_restrict_ip_address}</td>
	<td>
		<input type="text" name="entries[{$entry[id]}][start]" value="{$entry[start]}" />
	</td>
</tr>