<h1>{lang:admin:users_select}</h1>
<p>{lang:admin:users_select_desc}</p>
<h2>{lang:admin:users_all}</h2>
<ul class="children">
{block:user}
<li><a href="admin.php?cmd=users_edit_user&amp;id={$user[id]}">{$user[username]} 
- {$user[firstname]} {$user[lastname]}</a></li>
{/block:user}
</ul>