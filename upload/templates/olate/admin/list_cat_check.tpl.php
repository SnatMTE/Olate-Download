{if: !empty($title)}
<h1>{$title}</h1>
{endif}
{if: !empty($text)}
<p>{$text}</p>
{endif}
<!-- Normal categories -->
{block:category}
<div class="box">
<h2>
	<input type="checkbox" name="categories[{$category[id]}]" id="categories[{$category[id]}]" value="on" />
	{$cat_label} ({$file_count} files) <span class="cat_head_options">{$options_links}</span>
</h2>

<div id="children{$category[id]}">
{if: !empty($has_children) }

{$children_output}

{endif}
</div>
</div>
{/block:category}