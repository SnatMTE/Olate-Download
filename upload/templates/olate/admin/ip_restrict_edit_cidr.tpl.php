<tr {if: !empty($error_fields[$entry[id]]['start'])}
style="background-color: #FFCECE;"{endif}>
	<td>{lang:admin:ip_restrict_network_address}</td>
	<td>
		<input type="text" name="entries[{$entry[id]}][start]" value="{$entry[start]}" />
	</td>
</tr>
<tr {if: !empty($error_fields[$entry[id]]['mask'])}
style="background-color: #FFCECE;"{endif}>
	<td>
		{lang:admin:ip_restrict_subnet_mask}
	</td>
	<td>
		<select name="entries[{$entry[id]}][mask]">
			{if: !empty($entry[mask])}
			<option value="{$entry[mask]}" selected="selected">/{$entry[mask]}</option>
			<option value="---">- - - - - -</option>
			{endif}
			<option value="32">/32 (1 IP - single-host)</option>
			<option value="31">/31 (2 IPs)</option>
			<option value="30">/30 (4 IPs)</option>
			<option value="29">/29 (8 IPs)</option>
			<option value="28">/28 (16 IPs)</option>
			<option value="27">/27 (32 IPs)</option>
			<option value="26">/26 (64 IPs)</option>
			<option value="25">/25 (128 IPs)</option>
			<option value="24"{if: empty($entry[mask])}
			 selected="selected"
			{endif}>/24 (256 IPs - Class C subnet)</option>
			<option value="23">/23</option>
			<option value="22">/22</option>
			<option value="21">/21</option>
			<option value="20">/20</option>
			<option value="19">/19</option>
			<option value="18">/18</option>
			<option value="17">/17</option>
			<option value="16">/16 (Class B subnet)</option>
			<option value="15">/15</option>
			<option value="14">/14</option>
			<option value="13">/13</option>
			<option value="12">/12</option>
			<option value="11">/11</option>
			<option value="10">/10</option>
			<option value="9">/9</option>
			<option value="8">/8</option>
			<option value="7">/7</option>
			<option value="6">/6</option>
			<option value="5">/5</option>
			<option value="4">/4</option>
			<option value="3">/3</option>
			<option value="2">/2</option>
			<option value="1">/1</option>
			<option value="0">/0</option>
		</select>
	</td>
</tr>