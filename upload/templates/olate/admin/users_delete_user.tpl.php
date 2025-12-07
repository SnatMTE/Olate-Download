{if: !isset($success)}
<h1>Select User</h1>
<p>Click the name of the user you wish to delete:</p>
<h2>All Users:</h2>
<ul class="children">
{block:user}
<li><a href="admin.php?cmd=users_delete_user&amp;id={$user[id]}">{$user[username]} 
- {$user[firstname]} {$user[lastname]}</a></li>
{/block:user}
</ul>
{elseif: $success !== true}
<h1>User not deleted</h1>
<p>The user you selected has not been deleted</p>
{else}
<h1>User Deleted</h1>
<p>The selected user has been deleted.</p>
{endif}