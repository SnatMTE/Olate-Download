<tr {if: !empty($error_fields[$entry[id]]['start'])}
style="background-color: #FFCECE;"{endif}>
	<td>{lang:admin:ip_restrict_range_start}</td>
	<td>
		<input type="text" name="entries[{$entry[id]}][start]" value="{$entry[start]}" />
	</td>
</tr>
<tr {if: !empty($error_fields[$entry[id]]['end'])}
style="background-color: #FFCECE;"{endif}>
	<td>{lang:admin:ip_restrict_range_end}</td>
	<td>
		<input type="text" name="entries[{$entry[id]}][end]" value="{$entry[end]}" />
	</td>
</tr>