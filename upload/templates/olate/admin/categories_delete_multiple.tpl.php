{if:isset($success)}
<h1>{lang:admin:categories_deleted}.</h1>
<p>{lang:admin:categories_deleted_multi_desc}</p>

{else}

<h1>{lang:admin:categories_delete_multi}</h1>

{if: isset($error) && $error == 3}
<p>{lang:admin:categories_not_deleted_plural}</p>
{else}
<p>{lang:admin:categories_delete_multi_desc}</p>
<h2>{lang:admin:categories}</h2>

{if:isset($error)&&$error==1}
<p>{lang:admin:cannot_delete}</p>
{elseif:isset($error)&&$error==2}
<p>{lang:admin:categories_didnt_select}</p>
{endif}

<form action="admin.php?cmd=categories_delete_multiple" method="post">

{$category_list}

<h2>{lang:admin:categories_move_to}</h2>

<p><select name="move">
<option value="0">{lang:admin:none}</option>
{block:category_list}
<option value="{$category[id]}">{$category[name]}</option>
{/block:category_list}
</select></p>

<p><input type="submit" name="submit" value="{lang:admin:delete}" /></p>

</form>
{endif}
{endif}