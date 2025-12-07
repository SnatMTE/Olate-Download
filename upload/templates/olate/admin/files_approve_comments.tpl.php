<h1>{lang:admin:comments_approve}</h1>
{if: !$empty}
{if: !$success}
<p>{lang:admin:comments_desc}</p>
{block:comment}
<div class="box">
<div class="filebox_breadcrumb"><a href="admin.php?cmd=files_edit_file&amp;action=select&amp;file_id={$file[id]}">{$file[name]}</a></div>
<div class="wysiwyg">{$text}</div>
<div class="filebox_links">{lang:admin:posted_by}: {$comment[name]} {lang:admin:on} {$date} {lang:admin:at} {$time} (<a href="admin.php?cmd=files_approve_comments&amp;id={$comment[id]}">{lang:admin:approve}</a> | <a href="admin.php?cmd=files_edit_comment&amp;id={$comment[id]}">{lang:admin:edit}</a> | <a href="admin.php?cmd=files_delete_comment&amp;id={$comment[id]}">{lang:admin:delete}</a>)</div>
</div>
{/block:comment}
{else}
<p>{lang:admin:approved}</p>
{endif}
{else}
<p>{lang:admin:comments_none_pending}</p>
{endif}