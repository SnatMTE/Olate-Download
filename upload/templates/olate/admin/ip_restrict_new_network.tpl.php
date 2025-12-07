<h1>{lang:admin:ip_restrict_add_network}</h1>

{if: empty($success) || $success !== true}

<form action="admin.php?cmd=ip_restrict_main&amp;act=new_network" method="post">
<p>{lang:admin:ip_restrict_add_desc}</p>

{if: !empty($error)}
<div class="box">
	<h2>{lang:admin:errors_with_submission}</h2>
	<p>{$error}</p>
</div>
{endif}

{if: empty($hide_form) || $hide_form === false}
<h2>{lang:admin:ip_restrict_network_address}:</h2>
<p>
	{lang:admin:ip_restrict_network_address}: 
	<input type="text" name="network_address" value="{if: !empty($submitted[network_address])}
	{$submitted[network_address]}
	{else}
	xxx.xxx.xxx.xxx{endif}" onfocus="if(this.value=='xxx.xxx.xxx.xxx'){this.value='';}" />
</p>
<h2>{lang:admin:ip_restrict_subnet_mask}</h2>
<table>
	<tr>
		<td>
			<input type="radio" name="filter_type" value="3" {if: (!empty($submitted[filter_type]) && $submitted[filter_type] != 3) || empty($submitted[filter_type])}
			checked="checked"{endif} id="filter_type_3" /> 
			<label for="filter_type_3">{lang:admin:ip_restrict_dotted_subnet}</label>
		</td>
		<td>
			<input type="text" name="dotted_netmask" value="{if: !empty($submitted[dotted_netmask])}
			{$submitted[dotted_netmask]}
			{else}
			xxx.xxx.xxx.xxx{endif}" onfocus="if(this.value=='xxx.xxx.xxx.xxx'){this.value='';}" />
		</td>
		<td>
			({lang:admin:for_example}: 255.255.255.0)
		</td>
	</tr>
	<tr>
		<td>
			<input type="radio" name="filter_type" value="4" {if: !empty($submitted[filter_type]) && $submitted[filter_type] == 4}
			checked="checked"{endif} id="filter_type_4" /> 
			<label for="filter_type_4">{lang:admin:ip_restrict_cidr_netmask}</label>
		</td>
		<td>
			<select name="cidr_netmask">
				{if: !empty($submitted[cidr_netmask])}
				<option value="{$submitted[cidr_netmask]}">/{$submitted[cidr_netmask]}</option>
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
				<option value="24"{if: empty($submitted[cidr_netmask])}
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
		<td>
			({lang:admin:for_example}: /24)
		</td>
	</tr>
</table>

<h2>{lang:admin:enabled_question}</h2>
<p>{lang:admin:ip_restrict_enabled_desc}</p>
<p>
	{lang:admin:ip_restrict_enable_entry} 
	<input type="checkbox" name="filter_active" value="1" {if: !empty($submitted[filter_active]) && $submitted[filter_active] == 1}
	checked="checked"{endif} /><br style="line-height:24px;" />
	<input type="submit" name="submit" value="{lang:admin:ip_restrict_add_iprange}" />
</p>
{endif}

{if: !empty($need_confirmation)}
<input type="hidden" name="filter_type" value="{$submitted[filter_type]}" />
<input type="hidden" name="network_address" value="{$submitted[network_address]}" />
<input type="hidden" name="dotted_netmask" value="{$submitted[dotted_netmask]}" />
<input type="hidden" name="cidr_netmask" value="{$submitted[cidr_netmask]}" />
<input type="hidden" name="filter_active" value="{$submitted[filter_active]}" />
<h2>{lang:admin:you_want_to_continue}</h2>
<p>
	<input type="submit" name="submit_yes" value="{lang:admin:yes}" /> 
	<input type="submit" name="submit_no" value="{lang:admin:no}" />
</p>
{endif}
</form>

{else}
<p>{lang:admin:ip_restrict_entry_added}</p>
{endif}