<h1>{lang:frontend:top_downloads}</h1>

<!-- BEGIN: Top Files Listing -->
{block:top_file}
<div class="box">
<h2 class="filebox_breadcrumb">
	<a href="files.php?cat={$file[cat_id]}">{$file[cat_name]}</a> &#187; 
	<a href="details.php?file={$file[id]}">{$file[name]}</a>
</h2>

<div class="wysiwyg">{$file[description_small]}</div>
<div class="filebox_links">
	{lang:frontend:date}: {$file[date]} -
	<a href="details.php?file={$file[id]}" title="{lang:frontend:view_more_about} '{$file[name]}'">{lang:frontend:view_full}</a> -
	{lang:frontend:downloads}: {$file[downloads]} -
	<a href="download.php?file={$file[id]}" title="Download {$file[name]}">{lang:frontend:download} <strong>{$file[name]}</strong> ({$file[size]}{$filesize_format})</a>
</div>
</div>

{/block:top_file}
<!-- END: Top Files Listing -->