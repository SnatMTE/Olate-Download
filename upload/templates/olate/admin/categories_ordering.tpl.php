<h1>{lang:admin:categories_ordering}</h1>
{if: isset($success)}
<p>{lang:admin:categories_ordering_done}</p>
{else}
{if: !isset($specified)}
<p>{lang:admin:categories_ordering_desc}</p>
    <form action="admin.php?cmd=categories_ordering" method="post">
		    {block:cats}
		    <h2>{$category[name]} <input name="sort_{$category[id]}" type="text" id="sort_{$category[id]}" value="{$category[sort]}" size="1" style="text-align:center" /></h2>
    		<ul class="children">
			{block:cat_child}
			<li class="nolink"><a href="admin.php?cmd=categories_ordering&amp;cat={$child[id]}">{$child[name]}</a>
			<input name="sort_{$child[id]}" type="text" id="sort_{$child[id]}" value="{$child[sort]}" size="1" style="text-align:center" /></li>
			{/block:cat_child}
			</ul>
			{/block:cats} 
			<p><input name="submit" type="submit" id="submit" value="{lang:admin:update}" />		
		    <input name="submit" type="hidden" value="1" /></p>
</form>
{else}
{if: isset($none)}
<p>{lang:admin:categories_ordering_none}</p>
{else}
<p>{lang:admin:categories_ordering_select}</p>
    <form action="admin.php?cmd=categories_ordering" method="post">
		<h2>{lang:admin:category_children}</h2>
		<ul class="children">
		    {block:cats_specified}
			<li class="nolink"><a href="admin.php?cmd=categories_ordering&amp;cat={$category[id]}">{$category[name]}</a>
			<input name="sort_{$category[id]}" type="text" id="sort_{$category[id]}" value="{$category[sort]}" size="1" style="text-align:center" /></li>
			{/block:cats_specified} 
        </ul>	
			<p><input name="submit" type="submit" id="submit" value="{lang:admin:update}" />		
		    <input name="submit" type="hidden" value="1" /></p>
</form>
{endif}
{endif}
{endif}