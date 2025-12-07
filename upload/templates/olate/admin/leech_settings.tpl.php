<h1>{lang:admin:leech_settings}</h1>
{if: empty($message)}
<p>{lang:admin:leech_settings_desc}</p>
{else}
<p>{$message}</p>
{endif}
{if: empty($hide_form) || $hide_form !== true}
<h2>{lang:admin:settings}</h2>
<table cellspacing="0" cellpadding="0" width="90%">
	<tr>
		<td>
			<div class="box" style="text-align: center;">
			<h2>{lang:admin:always_allow}</h2>
			<form action="admin.php?cmd=leech_settings" method="post">
			<table class="form" width="90%" style="margin-left: auto; margin-right: auto;">
				<tr valign="middle">
					<td style="width: 100%;">
						<select name="allow_list[]" multiple="multiple" size="15" style="width: 100%;">
							{block:allow_list}
							<option value="{$entry[id]}">{$entry[domain]}</option>
							{/block:allow_list}
							{if: isset($allow_list_empty) && $allow_list_empty === true}
							<option value="">{lang:admin:no_entries}</option>
							{endif}
						</select>
					</td>
					<td>
						<input type="submit" name="submit_move_allow_deny" value="&gt;&gt;" title="{lang:admin:move_to_selected_list}" /><br /><br />
						<input type="image" name="submit_delete_allow" src="templates/olate/images/editdelete.png" style="border: 0;" title="{lang:admin:delete_selected_entries}" />
					</td>
				</tr>
			</table>
			</form>
			<form action="admin.php?cmd=leech_settings" method="post">
				<p>
					{lang:admin:add_domain}: <input type="text" name="new_domain_allow" /> 
					<input type="submit" name="submit_add_allow" value="{lang:admin:add}" />
				</p>
			</form>
			</div>
		</td>
		<td>
			<div class="box" style="text-align: center;">
			<h2>{lang:admin:always_deny}</h2>
			<form action="admin.php?cmd=leech_settings" method="post">
			<table class="form" width="90%" style="margin-left: auto; margin-right: auto;">
				<tr valign="middle">
					<td>
						<input type="submit" name="submit_move_deny_allow" value="&lt;&lt;" title="{lang:admin:move_to_selected_list}" /><br /><br />
						<input type="image" name="submit_delete_deny" src="templates/olate/images/editdelete.png" style="border: 0;" title="{lang:admin:delete_selected_entries}" />
					</td>
					<td style="width: 100%;">
						<select name="deny_list[]" multiple="multiple" size="15" style="width: 100%;">
							{block:deny_list}
							<option value="{$entry[id]}">{$entry[domain]}</option>
							{/block:deny_list}
							{if: isset($deny_list_empty) && $deny_list_empty === true}
							<option value="">{lang:admin:no_entries}</option>
							{endif}
						</select>
					</td>
				</tr>
			</table>
			</form>
			
			<form action="admin.php?cmd=leech_settings" method="post">
				<p>
					{lang:admin:add_domain}: <input type="text" name="new_domain_deny" /> 
					<input type="submit" name="submit_add_deny" value="{lang:admin:add}" />
				</p>
			</form>
			</div>
		</td>
	</tr>
</table>

<p>{lang:admin:domain_name_characters}</p>
{endif}