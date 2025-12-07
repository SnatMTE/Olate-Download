{if: !empty($title)}
<h1>{$title}</h1>
{endif}
{if: !empty($text)}
<p>{$text}</p>
{endif}
{if: !empty($private_category)}
<!-- Private files -->
{$private_category}
{endif}
<!-- Normal files/categories -->
{block:category}
<div class="box">
<h2>{$cat_label} ({$file_count} files) <span class="cat_head_options">{$options_links}</span></h2>

<div id="children{$category[id]}">
{if: !empty($has_children) }

{$children_output}

{if: !empty($has_files) }
<ul class="children">
{block:file}

{if: $has_link === true}
<li><a href="{$link_url}">{$file_label}</a></li>
{else}
<li class="nolink">{$file_label}</li>
{endif}
{/block:file}
</ul>
{endif}
{endif}
</div>
</div>
{/block:category}