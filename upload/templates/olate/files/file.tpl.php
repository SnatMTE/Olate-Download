{if: !isset($empty)}

<!-- BEGIN: Display File Details -->
<h1>{lang:frontend:viewing}: {$file[name]}</h1>

<div class="box">
<h2 style="margin:0">{$file[name]}</h2>

<div class="wysiwyg">{$file[description_big]}</div>
<div class="filebox_links">{lang:frontend:date}: {$file[date]} &#8226; 
{lang:frontend:views}: {$file[views]} &#8226; 
{lang:frontend:downloads}: {$file[downloads]} 
{if: $site_config[enable_ratings] == 1}
&#8226; {lang:frontend:rating}: {$file[rating_value]} ({$file[rating_votes]} {lang:frontend:votes}) 
{endif}
{block:custom_fields}
 &#8226; {$custom_field_label}: {$custom_field_value}
{/block:custom_fields}
<br />
	<a href="download.php?file={$file[id]}" title="Download {$file[name]}">{lang:frontend:download} <strong>{$file[name]}</strong> ({$file[size]}{$filesize_format})</a>
	&#8226; <a href="report.php?file={$file[id]}">{lang:frontend:report_problem}</a>
	{if:$site_config[enable_recommend_friend]}
		&#8226; <a href="recommend.php?file={$file[id]}">{lang:frontend:recommend_friend}</a>
	{endif}
</div>
</div>
<!-- END: Display File Details -->

<!-- Display error -->
{elseif: isset($empty)}
<h1>{lang:frontend:invalid_file}</h1>

<!-- Error -->
<p>{$empty}</p>
{endif}