<h1>{lang:frontend:mirrors}</h1>

<p>{lang:frontend:select_mirror}:</p>
<ul class="children">
<!-- BEGIN: Mirrors Listing -->
{block:mirror}
<li><a href="download.php?go=2&amp;file={$file_id}&amp;mirror={$mirror[id]}">{$mirror[name]} ({$mirror[location]})</a></li>
{/block:mirror}
<!-- END: Mirrors Listing -->
</ul>