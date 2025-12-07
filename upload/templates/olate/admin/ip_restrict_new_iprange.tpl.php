<h1>{lang:admin:ip_restrict_add_iprange}</h1>

{if: empty($success) || $success !== true}

<form action="admin.php?cmd=ip_restrict_main&amp;act=new_iprange" method="post">
<p>{lang:admin:ip_restrict_add_desc}</p>

{if: !empty($error)}
<div class="box">
<h2>{lang:admin:errors_with_submission}</h2>
<p>{$error}</p>
</div>
{endif}

{if: empty($hide_form) || $hide_form === false}
<h2>{lang:admin:ip_restrict_ip_addresses}:</h2>
<table class="form">
<tr>
<td class="formleft">{lang:admin:ip_restrict_range_start}</td>
<td><input type="text" name="ip_address_start" value="{if: !empty($submitted[ip_address_start])}
{$submitted[ip_address_start]}
{else}
xxx.xxx.xxx.xxx{endif}" onfocus="if(this.value=='xxx.xxx.xxx.xxx'){this.value='';}" /></td>
</tr>
<tr>
<td class="formleft">{lang:admin:ip_restrict_range_end}</td>
<td><input type="text" name="ip_address_end" value="{if: !empty($submitted[ip_address_end])}
{$submitted[ip_address_end]}
{else}
xxx.xxx.xxx.xxx{endif}" onfocus="if(this.value=='xxx.xxx.xxx.xxx'){this.value='';}" />
</td>
</tr>
</table>
<h2>{lang:admin:enabled_question}</h2>
<p>{lang:admin:ip_restrict_enabled_desc}</p>
<p>{lang:admin:ip_restrict_enable_entry} 
<input type="checkbox" name="filter_active" value="1" {if: !empty($submitted[filter_active]) && $submitted[filter_active] == 1}
checked="checked"{endif} /><br style="line-height:24px;" />
<input type="submit" name="submit" value="{lang:admin:ip_restrict_add_iprange}" /></p>
{endif}

{if: !empty($need_confirmation)}
<input type="hidden" name="ip_address_start" value="{$submitted[ip_address_start]}" />
<input type="hidden" name="ip_address_end" value="{$submitted[ip_address_end]}" />
<input type="hidden" name="filter_active" value="{$submitted[filter_active]}" />
<h2>{lang:admin:you_want_to_continue}</h2>
<p><input type="submit" name="submit_yes" value="{lang:admin:yes}" /> 
<input type="submit" name="submit_no" value="{lang:admin:no}" /></p>
{endif}
</form>

{else}
<p>{lang:admin:ip_restrict_entry_added}</p>
{endif}