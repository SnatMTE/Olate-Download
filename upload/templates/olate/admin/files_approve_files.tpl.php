<h1>{lang:admin:files_approve}</h1>
{if: isset($success) && $success === true}
<p>{lang:admin:files_approve_successful}</p>
{elseif: isset($success) && $success === false}
<p>{lang:admin:files_approve_failed}</p>
{else}

{if: !empty($has_files) || $has_files !== false}
<p>{lang:admin:files_approve_desc}</p>
<form action="admin.php?cmd=files_approve_files" method="post">
{$file_list}
<p>
	<input type="button" name="check_uncheck_all" value="{lang:admin:check_uncheck_all}" onclick="toggle_checks(this.form)" /> 
	<input type="submit" name="submit" value="{lang:admin:files_approve}" />
</p>
</form>
{else}
<p>{lang:admin:files_approve_none}</p>
{endif}
{endif}