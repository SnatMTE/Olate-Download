<h1>{lang:frontend:search}</h1>

<p>{lang:frontend:search_desc}:</p>
<!-- BEGIN: Search Form -->
<form action="search.php" method="get">
<h2>
{if: empty($submitted)}
<input name="query" type="text" size="30" alt="{lang:frontend:search_query}" />
{else}
<input name="query" type="text" value="{$query}" size="30" alt="{lang:frontend:search_query}" />
{endif}
<input type="submit" value="{lang:frontend:search}" />
</h2>
</form>
<!-- END: Search Form -->
{if: !empty($submitted)}
<!-- BEGIN: Results Box -->
<h1>{lang:frontend:results}</h1>
{if: $num_results != 0}
<p>{lang:frontend:search_returned} {$num_results} {lang:frontend:results_lc}:</p>
<!-- BEGIN: Search Results -->
{block:search}
<!-- BEGIN: Result -->
<h2 style="border-bottom:1px solid #899EC8;">
<a href="details.php?file={$result[id]}">{$result[name]}</a></h2>
<p style="border-bottom:0">{$result[description_small]}</p>
<!-- END: Result -->
{/block:search}
<!-- END: Search Results -->
{else}
{lang:frontend:search_zero_returned}.
{endif}
<!-- END: Results Box -->
{endif}
<!-- BEGIN: Pagination -->
{$pagination}
<!-- END: Pagination -->