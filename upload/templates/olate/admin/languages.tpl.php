<h1>{lang:admin:language_settings}</h1>

{if: empty($success)}
<p>{lang:admin:language_settings_desc}</p>

<h2>{lang:admin:language_settings_howto_title}</h2>
<p>{lang:admin:language_settings_howto_desc}</p>

{if: !empty($message)}
<div class="box">
	<p style="border: none;">{$message}</p>
</div>
{endif}

<h2>{lang:admin:active_languages}</h2>

<form action="admin.php?cmd=languages" method="post">
<table class="form" cellspacing="5" cellpadding="2">
	<tr>
		<th>{lang:admin:language}</th>
		<th>{lang:admin:filename}</th>
		<th>{lang:admin:deactivate}</th>
		<th>{lang:admin:default}</th>
	</tr>
{block:active_lang}
	<tr>
		<td>{$active_lang[name]}</td>
		<td>{$active_lang[filename]}</td>
		<td align="center">
			<input type="checkbox" name="deactivate[{$active_lang[id]}]" value="1" />
		</td>
		<td align="center">
{if: $active_lang[site_default] == 1}
			{lang:admin:default}
{else}
			<input type="submit" name="make_default[{$active_lang[id]}]" value="{lang:admin:make_default}" />
{endif}
		</td>
	</tr>
{/block:active_lang}
</table>
<p>
	<input type="submit" name="deactivate_languages" value="{lang:admin:deactivate_marked}" />
</p>
</form>

<h2>{lang:admin:inactive_languages}</h2>

{if: $inactive_count > 0}
<p>{lang:admin:inactive_languages_desc}</p>
<form action="admin.php?cmd=languages" method="post">
<table class="form" cellspacing="5" cellpadding="2">
	<tr>
		<th>{lang:admin:language}</th>
		<th>{lang:admin:filename}</th>
		<th>{lang:admin:activate}</th>
	</tr>
{block:inactive_lang}
	<tr>
		<td>{$inactive_lang[language]}</td>
		<td>{$inactive_lang[filename]}</td>
		<td align="center">
			<input type="checkbox" name="activate[{$inactive_lang[filename]}]" value="1" />
		</td>
	</tr>
{/block:inactive_lang}
</table>
<p>
	<input type="submit" name="activate_languages" value="{lang:admin:activate_marked}" />
</p>
</form>
{else}
<p>{lang:admin:no_inactive_lang}</p>
{endif}

<h2>{lang:admin:old_languages}</h2>
{if: $old_count > 0}
<p>{lang:admin:old_languages_desc}</p>

<table class="form" cellspacing="5" cellpadding="2">
	<tr>
		<th>{lang:admin:language}</th>
		<th>{lang:admin:filename}</th>
		<th>{lang:admin:od_version}</th>
	</tr>
{block:old_lang}
	<tr>
		<td>{$old_lang[language]}</td>
		<td>{$old_lang[filename]}</td>
		<td align="center">{$old_lang[version]}</td>
	</tr>
{/block:old_lang}
</table>
{else}
<p>{lang:admin:no_old_lang}</p>
{endif}
{else}
<p>{$message}</p>
{endif}
