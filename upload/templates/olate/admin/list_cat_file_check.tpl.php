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
<li class="nolink">
	<input type="checkbox" name="file_{$file_id}" id="file_{$file_id}" value="1" /> <label for="file_{$file_id}">{$file_label} - {lang:admin:added_on}: {$file_date}</label> 
	<a href="admin.php?cmd=files_edit_file&action=file_select&file_id={$file_id}" style="font-size: 80%;">(view file)</a>
</li>
{endif}
{/block:file}
</ul>
{endif}
{endif}
</div>
</div>
{/block:category}