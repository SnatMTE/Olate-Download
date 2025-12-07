<h1>{lang:admin:file_mass_delete}</h1>
{if: empty($success) || $success !== true}
<p>{lang:admin:file_mass_delete_desc}</p>
{if: !empty($error)}
<div class="box">
	<h2>{lang:frontend:error}</h2>
	<p>{$error}</p>
</div>
{endif}

<form action="admin.php?cmd=files_mass_delete" method="post">
<p>
	{lang:admin:category}: 
	<select name="cat_id">
		<option value="{$cat[id]}">{$cat[name]}</option>
		<option value="--">- - -</option>
{block:cats}
		<option value="{$category[id]}">{$category[name]}</option>
{/block:cats}
	</select>
</p>
<p>
	<input type="submit" name="submit" value="{lang:admin:delete_files}" />
</p>
</form>
{else}
<p>{$message}</p>
{endif}