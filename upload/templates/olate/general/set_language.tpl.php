<h1>{lang:frontend:change_language}</h1>
{if: empty($success)}
<p>{lang:frontend:change_language_desc}</p>

<form action="language.php" method="post">
<p>
	{lang:frontend:choose_language}
	<select name="language">
		<option value="{$current[id]}">{$current[name]}</option>
		<option value="--">---</option>
{block:languages}

		<option value="{$language[id]}">{$language[name]}</option>
{/block:languages}
	</select>
</p>
<p><input type="submit" name="submit" value="{lang:frontend:change_language}" /></p>
</form>
{else}
<p>{lang:frontend:language_changed}</p>
{endif}