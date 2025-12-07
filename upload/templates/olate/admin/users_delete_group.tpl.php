<h1>{lang:admin:groups_delete}</h1>
{if: $result == 1}
<p>{lang:admin:groups_delete_done}</p>
{elseif: $result == 2}
<p>{lang:admin:groups_delete_users}</p>
{elseif: $result == 3}
<p>{lang:admin:groups_deleted_not}</p>
{else}
<p>{lang:admin:groups_delete_desc}</p>
<h2>{lang:admin:groups_all}</h2>
<ul class="children">
{block:group}
<li><a href="admin.php?cmd=users_delete_group&amp;id={$group[id]}">{$group[name]}</a></li>
{/block:group}
</ul>
{endif}