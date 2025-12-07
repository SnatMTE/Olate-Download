<div id="leftbar">
<h2>{lang:frontend:categories}</h2>

<ul class="root_categories">
<!-- BEGIN: Categories -->
{block:cat_row}

<!-- BEGIN: Parent Categories -->
<li>
<p>
<a href="files.php?cat={$category[id]}" class="nounderline" title="{$category[description]}">{$category[name]}</a>
<!-- Display collapsable option -->
{if: $has_children === true}
<a href="" onclick="return collapse('{$category[id]}');" id="button{$category[id]}" class="collapse">-</a>
{endif}
</p>
<!-- END: Parent Categories -->
{if: $has_children === true}
<ul class="children" id="children{$category[id]}">
<!-- BEGIN: Child Categories -->
{block:cat_child}
<li><a href="files.php?cat={$child[id]}" title="{$child[description]}">{$child[name]}
{if: $site_config['enable_count']}
 ({$child[file_count]} {lang:frontend:files_lc})
{endif}
</a></li>
{/block:cat_child}
</ul>
<!-- END: Child Categories -->
{endif}
</li>
{/block:cat_row}
<!--END: Categories -->
</ul>
</div>
	
<div id="contentarea">
	<ul id="menu">
		<li><a href="index.php">{lang:frontend:index}</a></li>
		{if: $site_config[enable_topfiles] == 1}
		<li><a href="index.php?cmd=top">{lang:frontend:top} {$global_vars[top_files]} {lang:frontend:files}</a></li>
		{endif}
		{if: $site_config[enable_allfiles] == 1}
		<li><a href="index.php?cmd=all">{lang:frontend:all_files}</a></li>
		{endif}
		{if: $site_config[enable_search] == 1}
		<li><a href="search.php">{lang:frontend:search}</a></li>
		{endif}
		{if: $site_config[enable_useruploads] == 1}
		<li><a href="userupload.php">{lang:frontend:add_file}</a></li>
		{endif}
	</ul>
