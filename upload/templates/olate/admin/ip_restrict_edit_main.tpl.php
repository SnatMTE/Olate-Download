<h1>{lang:admin:ip_restrict_edit}</h1>
<p>
{if: !empty($message)}
{$message}
{else}
{lang:admin:ip_restrict_edit_desc}
{endif}
</p>

{if: empty($hide_form) || $hide_form === false}
<form action="admin.php?cmd=ip_restrict_main&amp;submit_edit=1" method="post">

{block:entry}
{if: $entry[type] == 1}
<div class="box" id="entry_{$entry[id]}" style="padding-left: 52px; background: url(templates/olate/images/mycomputer.png) no-repeat 10px center;">
{elseif: $entry[type] == 2}
<div class="box" id="entry_{$entry[id]}" style="padding-left: 52px; background: url(templates/olate/images/range.png) no-repeat 10px center;">
{else}
<div class="box" id="entry_{$entry[id]}" style="padding-left: 52px; background: url(templates/olate/images/network.png) no-repeat 10px center;">
{endif}
<input type="hidden" name="ip_check_{$entry[id]}" value="1" />
<input type="hidden" name="entries[{$entry[id]}][id]" value="{$entry[id]}" />

{if: !empty($error_fields[$entry[id]])}
<div class="box">
<h2>{lang:admin:ip_restrict_errors_with_entry}</h2>
<p style="border-bottom: 0px;">{lang:admin:errors_highlighted_red}</p>
</div>
{endif}

<table class="form" width="99%">
	<tr>
		<td width="40%" class="formleft">{lang:admin:ip_restrict_type_of}</td>
		<td>
			<select name="entries[{$entry[id]}][type]">
{if: $entry[type] == 1}
			<option value="{$entry[type]}">{lang:admin:ip_restrict_ip_address}</option>
{elseif: $entry[type] == 2}
			<option value="{$entry[type]}">{lang:admin:ip_restrict_ip_range}</option>
{elseif: $entry[type] == 3}
			<option value="{$entry[type]}">{lang:admin:ip_restrict_network_dotted}</option>
{elseif: $entry[type] == 4}
			<option value="{$entry[type]}">{lang:admin:ip_restrict_network_cidr}</option>
{endif}
			<option value="{$entry[type]}">- - - - - -</option>
			<option value="1">{lang:admin:ip_restrict_ip_address}</option>
			<option value="2">{lang:admin:ip_restrict_ip_range}</option>
			<option value="3">{lang:admin:ip_restrict_network_dotted}</option>
			<option value="4">{lang:admin:ip_restrict_network_cidr}</option>
			</select> 
			<input type="submit" name="submit_types" value="{lang:admin:ip_restrict_change_type}" />
		</td>
	</tr>
{$form_for_type}
	<tr>
		<td>
			{lang:admin:enabled_question}
		</td>
		<td>
			<input type="checkbox" name="entries[{$entry[id]}][active]" value="1" {if: $entry[active] == 1}
checked="checked"{endif} />
		</td>
	</tr>
</table>

</div>
{/block:entry}

<p>
	<input type="submit" name="submit_revert" value="{lang:admin:ip_restrict_changes_undo}" />
	<input type="submit" name="submit_submit" value="{lang:admin:ip_restrict_changes_submit}" />
</p>
</form>
{endif}