<h1>{lang:admin:categories_delete}</h1>
{if: $result == 1}
<p>{lang:admin:categories_delete_desc}</p>
{elseif: $result == 2}
<p>{lang:admin:categories_delete_still}</p>
<form action="admin.php?cmd=categories_delete" method="post">
<h2>{lang:admin:categories_move}</h2>
<p>{lang:admin:categories_destination} 
<select name="move">
{block:move_categories}
<option value="{$category[id]}">{$category[name]}</option>
{/block:move_categories} 
</select>
</p>
<p><input type="submit" value="{lang:admin:categories_move_category}" />
<input type="hidden" name="current" value="{$current}" /></p>
</form>
{elseif: $result == 3}
<p>{lang:admin:categories_not_deleted}</p>
{else}
<p>{lang:admin:categories_delete_select}</p>
<h2>{lang:admin:category}</h2>
<ul class="children">
{block:category}
<li><a href="admin.php?cmd=categories_delete&amp;action=select&amp;id={$category[id]}">{$category[name]}</a></li>
{/block:category}
</ul>
{endif}