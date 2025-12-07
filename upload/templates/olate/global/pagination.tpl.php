<div class="box" id="pages">
<h2>{lang:frontend:pages}</h2>

<!-- BEGIN: Pages Box -->
<p style="border-bottom:0">{lang:frontend:page}
{block:page}
{if:$current_page == $page}
 - {$page}
{else}
 - <a href="{$link}page={$page}" class="page">{$page}</a>
{endif}
{/block:page}
</p>
<!-- END: Pages Box -->

</div>