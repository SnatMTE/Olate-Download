<h1>{lang:admin:custom_fields_delete}</h1>
{if: !isset($success)}
<p>{lang:admin:custom_fields_delete_desc}</p>
    <ul class="children">
	{block:customfields}
	<li>
		<a href="admin.php?cmd=customfields_delete&amp;id={$customfield[id]}">{$customfield[label]}</a>
	</li>
	{/block:customfields}
	</ul>
{elseif: $success !== true}
<p>{lang:admin:custom_fields_not_deleted}</p>
{else}
<p>{lang:admin:custom_fields_deleted}</p>
{endif}