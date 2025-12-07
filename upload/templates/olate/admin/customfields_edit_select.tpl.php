<h1>{lang:admin:custom_fields_edit}</h1>
{if: !isset($success)}
<p>{lang:admin:custom_fields_edit_desc}</p>
    <ul class="children">
	{block:customfields}
	<li>
		<a href="admin.php?cmd=customfields_edit&amp;action=select&amp;id={$customfield[id]}">{$customfield[label]}</a>
	</li>
	{/block:customfields}
	</ul>
{else}
<p>{lang:admin:custom_fields_added}</p>
{endif}