<h1>{lang:frontend:child_categories}</h1>

<!-- BEGIN: Child Categories -->
<ul class="children">
{block:child}
<li><a href="files.php?cat={$child[id]}">{$child[name]}
{if: isset($count)}
 ({$count} {lang:frontend:files_lc})
{endif}
</a></li>
{/block:child}
</ul>
<!-- END: Child Categories -->