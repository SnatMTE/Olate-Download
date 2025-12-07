<h1>{lang:admin:updates}</h1>

{if: !isset($error)}
{if: isset($up_to_date)}
<p>{lang:admin:updates_latest}</p>
{else}
<p>{lang:admin:updates_not_latest}</p>
{endif}
<p>{lang:admin:updates_current} {$global_vars[version]}<br />
{lang:admin:updates_new} {$latest_version}<br /></p>
<p>{lang:admin:updates_download}</p>
{else}
<p>{$error}</p>
{endif}