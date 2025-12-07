{if: !isset($success)}
<h1>{lang:admin:file_select}</h1>

<!-- BEGIN: All Files Listing -->
<p>{lang:admin:file_delete_desc}</p>
<ul class="children">
{block:all_file}
<li>
	<a href="admin.php?cmd=files_delete_file&amp;action=delete&amp;file={$file[id]}">
	{if: isset($file[cat_name])}
	{$file[cat_name]} &#187; 
	{endif}
	{$file[name]}
	</a>
</li>
{/block:all_file}
</ul>
<!-- END: All Files Listing -->
{elseif: $success !== "nothing"}
	<h1>{lang:admin:file_deleted}</h1>
	<p>{lang:admin:file_deleted_desc}</p>
	{if: ($file_deleted)}
		<p>{lang:admin:physically_deleted}</p>
	{endif}
{else}
	<h1>{lang:admin:file_delete}</h1>
	<p>{lang:admin:file_not_deleted}</p>
{endif}