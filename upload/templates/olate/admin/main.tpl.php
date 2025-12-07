<h1>{lang:admin:admin_panel}</h1>

<p>{lang:admin:admin_panel_welcome}</p>

<h2>{lang:admin:statistics}</h2>

<ul class="children">
	<li class="nolink">{lang:admin:total_files}: {$total_files}</li>
{if: $user_permissions[acp_files_approve_files]}
	<li><a href="admin.php?cmd=files_approve_files">{lang:admin:total_inactive_files}: {$total_inactive_files}</a></li>
{else}
	<li class="nolink">{lang:admin:total_inactive_files}: {$total_inactive_files}</li>
{endif}
	<li class="nolink">{lang:admin:total_downloads}: {$total_downloads}</li>
	<li><a href="admin.php?cmd=files_manage_comments">{lang:admin:total_comments}: {$total_comments}</a></li>	
	<li><a href="admin.php?cmd=files_approve_comments">{lang:admin:total_comments_pending}: {$pending_comments}</a></li>
	<li class="nolink">{lang:admin:total_users}: {$total_users}</li>
</ul>

<h2>{lang:admin:tech_support}</h2>

<p>{lang:admin:tech_support_desc}</p>
{if: !isset($up_to_date)}

<h2>{lang:admin:updates_available}</h2>

<p>{lang:admin:updates_available_desc_1} {$global_vars[version]} {lang:admin:updates_available_desc_2} ({$latest_version}) {lang:admin:updates_available_desc_3}</p>
{endif}