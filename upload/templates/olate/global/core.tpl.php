<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{$page_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset={lang:general:charset}"/>
{if:isset($page_refresh)}
<meta http-equiv="Refresh" content="2;URL={$page_refresh}" />
{endif}

{block:meta_tags}
{if: $tag[type] == 'http-equiv'}
<meta http-equiv="{$tag[name]}" content="{$tag[value]}" />
{else}
<meta name="{$tag[name]}" content="{$tag[value]}" />
{endif}
{/block:meta_tags}

<link rel="alternate" type="application/rdf+xml" title="Latest Downloads" href="rss.php" />
<link href="templates/olate/global/core.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/olate/global/core.js"></script>
</head>

<body>

<div id="wrapper">

	<div id="logo"><img src="templates/olate/images/logo.gif" width="190" height="68" alt="{lang:frontend:logo}"/></div>	
	
	{$page_content}
	
	<div id="footer">
		Powered by <a style="color:#FFFFFF" href="http://www.olate.co.uk">Olate Download</a> {$global_vars[version]} 
		<p>
{if: $site_config[allow_user_lang]}
		<a style="color:#FFFFFF" href="language.php">{lang:frontend:change_language}</a>
{endif}
{if:$global_vars[enable_rss] == 1}
{if: $site_config[allow_user_lang]}
			|
{endif}
			<a style="color:#FFFFFF" href="{$global_vars[url]}rss.php">RSS</a>
{endif} 
{if:$global_vars[debug] == 1}
			| Execution Time: {$global_vars[exec_time]} | Query Count: {$global_vars[queries]}
{endif}
		</p>
	</div>
	
</div>

</body>
</html>