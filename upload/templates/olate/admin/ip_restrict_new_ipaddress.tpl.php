<h1>{lang:admin:ip_restrict_add_ipaddress}</h1>

{if: empty($success) || $success !== true}

<form action="admin.php?cmd=ip_restrict_main&amp;act=new_ipaddress" method="post">
<p>{lang:admin:ip_restrict_add_desc}</p>

{if: !empty($error)}
<div class="box">
<h2>{lang:admin:errors_with_submission}</h2>
<p>{$error}</p>
</div>
{endif}

{if: empty($hide_form) || $hide_form === false}
<h2>{lang:admin:ip_restrict_ip_address}:</h2>
<p><input type="text" name="ip_address" value="{if: !empty($submitted[ip_address])}
{$submitted[ip_address]}
{else}
xxx.xxx.xxx.xxx{endif}" onfocus="if(this.value=='xxx.xxx.xxx.xxx'){this.value='';}" /></p>
<h2>{lang:admin:enabled_question}</h2>
<p>{lang:admin:ip_restrict_enabled_desc}</p>
<p>{lang:admin:ip_restrict_enable_entry} 
<input type="checkbox" name="filter_active" value="1" {if: !empty($submitted[filter_active]) && $submitted[filter_active] == 1}
checked="checked"{endif} /><br style="line-height:24px;" />
<input type="submit" name="submit" value="{lang:admin:ip_restrict_add_ipaddress}" /></p>
{endif}

{if: !empty($need_confirmation)}
<input type="hidden" name="ip_address" value="{$submitted[ip_address]}" />
<input type="hidden" name="filter_active" value="{$submitted[filter_active]}" />
<h2>{lang:admin:you_want_to_continue}</h2>
<p><input type="submit" name="submit_yes" value="{lang:admin:yes}" /> 
<input type="submit" name="submit_no" value="{lang:admin:no}" /></p>
{endif}
</form>

{else}
<p>{lang:admin:ip_restrict_entry_added}</p>
{endif}