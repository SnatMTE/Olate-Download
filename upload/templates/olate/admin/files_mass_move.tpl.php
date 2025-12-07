<h1>{lang:admin:file_mass_move}</h1>
{if: empty($success) || $success !== true}
<p>{lang:admin:file_mass_move_desc}</p>
{if: !empty($error)}
<div class="box">
	<h2>{lang:frontend:error}</h2>
	<p>{$error}</p>
</div>
{endif}

<form action="admin.php?cmd=files_mass_move" method="post">
<p>
	{lang:admin:category_source} 
	<select name="source_id">
		<option value="{$source[id]}">{$source[name]}</option>
		<option value="--">- - -</option>
{block:source_id}
		<option value="{$category[id]}">{$category[name]}</option>
{/block:source_id}
	</select>
</p>
<p>
	{lang:admin:category_dest} 
	<select name="dest_id">
		<option value="{$dest[id]}">{$dest[name]}</option>
		<option value="--">- - -</option>
{block:dest_id}
		<option value="{$category[id]}">{$category[name]}</option>
{/block:dest_id}
	</select>
</p>
<p>
	<input type="submit" name="submit" value="{lang:admin:move_files}" />
</p>
</form>
{else}
<p>{$message}</p>
{endif}