{if: !isset($empty)}

<!-- BEGIN: Sorting -->
<h1>{lang:frontend:showing_files}: {$category[name]} <span class="small">( {lang:frontend:sort_by} 
{if: $current_sort == 'name' and $current_order == 'asc'}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=name&amp;order=desc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:name}, {lang:frontend:descending}">{lang:frontend:name}</a>
<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" />,
{else}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=name&amp;order=asc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:name}, {lang:frontend:ascending}">{lang:frontend:name}</a> 
<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" />,
{endif}

{if: $current_sort == 'date' and $current_order == 'asc'}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=date&amp;order=desc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:date}, {lang:frontend:descending}">{lang:frontend:date}</a> 
<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" />,
{else}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=date&amp;order=asc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:date}, {lang:frontend:ascending}">{lang:frontend:date}</a> 
<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" />,
{endif}

{if: $current_sort == 'downloads' and $current_order == 'asc'}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=downloads&amp;order=desc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:downloads}, {lang:frontend:descending}">{lang:frontend:downloads}</a>
<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" />,
{else}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=downloads&amp;order=asc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:downloads}, {lang:frontend:ascending}">{lang:frontend:downloads}</a> 
<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" />,
{endif}

{if: $current_sort == 'size' and $current_order == 'asc'}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=size&amp;order=desc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:filesize}, {lang:frontend:descending}">{lang:frontend:filesize}</a>
<img src="templates/olate/images/arrow_desc.gif" alt="{lang:frontend:descending}" /> ) 
{else}
<a href="{$global_vars[php_self]}?cat={$cat}&amp;cmd=all&amp;sort=size&amp;order=asc&amp;page={$current_page}" title="{lang:frontend:sort_by} {lang:frontend:filesize}, {lang:frontend:ascending}">{lang:frontend:filesize}</a>
<img src="templates/olate/images/arrow_asc.gif" alt="{lang:frontend:ascending}" /> )
{endif}
</span></h1>
<!-- END: Sorting -->

<!-- BEGIN: Category Files Listing -->
{block:file}
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

{/block:file}
<!-- END: Category Files Listing -->


<!-- Display if category is empty -->
{else}
<h1>{lang:frontend:category_empty}</h1>

<!-- Error -->
<p>{$empty}</p>
{endif}
<!-- BEGIN: Pagination -->
{$pagination}
<!-- END: Pagination -->