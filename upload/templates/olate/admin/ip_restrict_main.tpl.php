<h1>{lang:admin:ip_restriction}</h1>
{if: empty($message)}
<table>
	<tr valign="top">
		<td>
			<div class="box">
			<h2>{lang:admin:ip_restrict_type_of}:</h2>
			<form action="admin.php?cmd=ip_restrict_main" method="post">
				<table class="filter_mode">
					<tr><td><input type="radio" name="ip_restrict_mode" value="0" id="filter_deny" {if: $site_config[ip_restrict_mode] == 0}
checked="checked" {endif} /> <label for="filter_deny">{lang:admin:ip_restrict_deny_addy}</label></td></tr>
					<tr><td><input type="radio" name="ip_restrict_mode" value="1" id="filter_allow" {if: $site_config[ip_restrict_mode] == 1}
checked="checked" {endif} /> <label for="filter_allow">{lang:admin:ip_restrict_allow_addy}</label></td></tr>
					<tr><td><input type="submit" name="update_action" value="{lang:admin:update}" /></td></tr>
				</table>
			</form>
			</div>
		</td>
		<td>
			<div class="box">
			<form action="admin.php?cmd=ip_restrict_main" method="post">
				<h2>{lang:admin:ip_restrict_test_ip}</h2>
				<div style="padding: 5px;">
					<input type="text" name="ip_address" maxlength="15" value="xxx.xxx.xxx.xxx" onfocus="if(this.value=='xxx.xxx.xxx.xxx'){this.value='';}"/> 
					<input type="submit" name="test_ip" value="{lang:admin:ip_restrict_test_addy}" />
				</div>
			</form>
			</div>
		</td>
	</tr>
</table>

{if: $show_stats}
<h2>{lang:admin:statistics}</h2>
<p>{lang:admin:ip_restrict_denials}: {$denial_count}</p>
<h2>Most recent denial</h2>
<table>
	<tr>
		<td>
		{lang:admin:ip_restrict_ip_address}:
		</td>
		<td>
			{$denial_data[ip_address]} {if: $has_hostname === true}
			({$denial_data[hostname]}){endif}
		</td>
	</tr>
	<tr>
		<td>
			{lang:admin:date}:
		</td>
		<td>
			{$denial_data[formatted_date]} {$denial_data[formatted_time]}
		</td>
	</tr>
	<tr>
		<td>
			{lang:admin:ip_restrict_request}:
		</td>
		<td>
			{$denial_data[request_uri]}
		</td>
	</tr>
</table>
{endif}

<h2>{lang:admin:ip_restrict_new_entry}</h2>
<table cellspacing="0" cellpadding="0" class="new_entry">
	<tr>
		<td>
			<a href="admin.php?cmd=ip_restrict_main&amp;act=new_ipaddress" title="{lang:admin:ip_restrict_add_ipaddress}">
				<img src="templates/olate/images/new_mycomputer.png" alt="{lang:admin:ip_restrict_add_ipaddress}" /><br />
				{lang:admin:ip_restrict_add_ipaddress}
			</a>
		</td>
		<td>
			<a href="admin.php?cmd=ip_restrict_main&amp;act=new_iprange" title="{lang:admin:ip_restrict_add_iprange}">
				<img src="templates/olate/images/new_range.png" alt="{lang:admin:ip_restrict_add_iprange}" /><br />
				{lang:admin:ip_restrict_add_iprange}
			</a>
		</td>
		<td>
			<a href="admin.php?cmd=ip_restrict_main&amp;act=new_network" title="{lang:admin:ip_restrict_add_network}">
				<img src="templates/olate/images/new_network.png" alt="{lang:admin:ip_restrict_add_network}" /><br />
				{lang:admin:ip_restrict_add_network}
			</a>
		</td>
	</tr>
</table>
{else}
<p>{$message}</p>
{endif}