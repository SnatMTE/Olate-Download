<h1>{lang:admin:groups_select}</h1>
<p>{lang:admin:groups_select_desc}</p>
<h2>{lang:admin:groups_all}</h2>
<ul class="children">
{block:group}
<li><a href="admin.php?cmd=users_edit_group&amp;id={$group[id]}">{$group[name]}</a></li>
{/block:group}
</ul>