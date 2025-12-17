<div id="leftbar">
<h2>{lang:admin:admin_cp}</h2>

<!-- BEGIN: Admin Menu -->
<ul class="root_categories">
<li>
	<p>
		<a href="">{lang:admin:main}</a>
		<a href="" onclick="return collapse('_main');" id="button_main" class="collapse">-</a>
	</p>

	<ul class="children" id="children_main">
{if:$user_permissions[acp_view]}
		<li><a href="admin.php">{lang:admin:admin_index}</a></li>
{endif}
	
{if:$user_permissions[acp_main_settings]}
		<li><a href="admin.php?cmd=main_settings">{lang:admin:general_settings}</a></li>
{endif}

{if:$user_permissions[acp_languages]}
		<li><a href="admin.php?cmd=languages">{lang:admin:language_settings}</a></li>
{endif}
		<li><a href="index.php">{lang:admin:view_site}</a></li>
		<li><a href="admin.php?cmd=logout">{lang:admin:logout}</a></li>
	</ul>
</li>


{if: $user_permissions[acp_ip_restrict_main]}
<li>
	<p>
		<a href="">{lang:admin:security}</a> 
		<a href="" onclick="return collapse('_security');" id="button_security" class="collapse">-</a>
	</p>
	<ul class="children" id="children_security">
{if: $user_permissions[acp_ip_restrict_main]}
		<li><a href="admin.php?cmd=ip_restrict_main">{lang:admin:ip_restriction}</a></li>
{endif}
{if: $user_permissions[acp_leech_settings]}
		<li><a href="admin.php?cmd=leech_settings">{lang:admin:leech_settings}</a></li>
{endif}
	</ul>
</li>
{endif}

{if:$user_permissions[acp_categories_add]||$user_permissions[acp_categories_edit]||$user_permissions[acp_categories_delete]||$user_permissions[acp_categories_ordering]}
<li>
	<p>
		<a href="">{lang:admin:categories}</a> 
		<a href="" onclick="return collapse('_categories');" id="button_categories" class="collapse">-</a>
	</p>
	<ul class="children" id="children_categories">
{if:$user_permissions[acp_categories_add]}
		<li><a href="admin.php?cmd=categories_add">{lang:admin:categories_add}</a></li>
{endif}
{if:$user_permissions[acp_categories_edit]}
		<li><a href="admin.php?cmd=categories_edit">{lang:admin:categories_edit}</a></li>
{endif}
{if:$user_permissions[acp_categories_delete]}
		<li><a href="admin.php?cmd=categories_delete">{lang:admin:categories_delete}</a></li>
{endif}
{if:$user_permissions[acp_categories_delete_multiple]}
		<li><a href="admin.php?cmd=categories_delete_multiple">{lang:admin:categories_delete_multi}</a></li>
{endif}
{if:$user_permissions[acp_categories_ordering]}
		<li><a href="admin.php?cmd=categories_ordering">{lang:admin:categories_ordering}</a></li>
{endif}
	</ul>
</li>
{endif}

{if:$user_permissions[acp_files_add_file]||$user_permissions[acp_files_edit_file]||$user_permissions[acp_files_delete_file]||$user_permissions[acp_files_mass_move]||$user_permissions[acp_files_mass_delete]}
<li>
	<p>
		<a href="">{lang:admin:files}</a> 
		<a href="" onclick="return collapse('_files');" id="button_files" class="collapse">-</a>
	</p>
	
	<ul class="children" id="children_files">
{if:$user_permissions[acp_files_approve_files]}
		<li><a href="admin.php?cmd=files_approve_files">{lang:admin:files_approve}</a></li>
{endif}
{if:$user_permissions[acp_files_add_file]}
		<li><a href="admin.php?cmd=files_add_file">{lang:admin:file_add}</a></li>
{endif}
{if:$user_permissions[acp_files_edit_file]}
		<li><a href="admin.php?cmd=files_edit_file">{lang:admin:file_edit}</a></li>
{endif}
{if:$user_permissions[acp_files_delete_file]}
		<li><a href="admin.php?cmd=files_delete_file">{lang:admin:file_delete}</a></li>
{endif}
{if:$user_permissions[acp_files_mass_move]}
		<li><a href="admin.php?cmd=files_mass_move">{lang:admin:file_mass_move}</a></li>
{endif}
{if:$user_permissions[acp_files_mass_delete]}
		<li><a href="admin.php?cmd=files_mass_delete">{lang:admin:file_mass_delete}</a></li>
{endif}
	</ul>
</li>
{endif}

{if:$user_permissions[acp_files_approve_comments]||$user_permissions[acp_files_add_comment]||$user_permissions[acp_files_manage_comments]}
<li>
	<p>
		<a href="">{lang:admin:comments}</a> 
		<a href="" onclick="return collapse('_comments');" id="button_comments" class="collapse">-</a>
	</p>

	<ul class="children" id="children_comments">
{if:$user_permissions[acp_files_approve_comments]}
		<li><a href="admin.php?cmd=files_approve_comments">{lang:admin:comments_approve}</a></li>
{endif}
{if:$user_permissions[acp_files_manage_comments]}
		<li><a href="admin.php?cmd=files_manage_comments">{lang:admin:comments_manage}</a></li>
{endif}
	</ul>
</li>
{endif}

{if:$user_permissions[acp_files_add_agreement]||$user_permissions[acp_files_edit_agreement]||$user_permissions[acp_files_delete_agreement]}
<li>
	<p>
		<a href="">{lang:admin:agreements}</a> 
		<a href="" onclick="return collapse('_agreements');" id="button_agreements" class="collapse">-</a>
	</p>

	<ul class="children" id="children_agreements">
{if:$user_permissions[acp_files_add_agreement]}
		<li><a href="admin.php?cmd=files_add_agreement">{lang:admin:agreement_add}</a></li>
{endif}
{if:$user_permissions[acp_files_edit_agreement]}
		<li><a href="admin.php?cmd=files_edit_agreement">{lang:admin:agreement_edit}</a></li>
{endif}
{if:$user_permissions[acp_files_delete_agreement]}
		<li><a href="admin.php?cmd=files_delete_agreement">{lang:admin:agreement_delete}</a></li>
{endif}
	</ul>
</li>
{endif}

{if:$user_permissions[acp_customfields_add]||$user_permissions[acp_customfields_edit]||$user_permissions[acp_customfields_delete]}
<li>
	<p>
		<a href="">{lang:admin:custom_fields}</a> 
		<a href="" onclick="return collapse('_customfields');" id="button_customfields" class="collapse">-</a>
	</p>

	<ul class="children" id="children_customfields">
{if:$user_permissions[acp_customfields_add]}
		<li><a href="admin.php?cmd=customfields_add">{lang:admin:custom_fields_add}</a></li>
{endif}
{if:$user_permissions[acp_customfields_edit]}
		<li><a href="admin.php?cmd=customfields_edit">{lang:admin:custom_fields_edit}</a></li>
{endif}
{if:$user_permissions[acp_customfields_delete]}
		<li><a href="admin.php?cmd=customfields_delete">{lang:admin:custom_fields_delete}</a></li>
{endif}
	</ul>
</li>
{endif}

{if:$user_permissions[acp_users_add_user]||$user_permissions[acp_users_edit_user]||$user_permissions[acp_users_delete_user]||$user_permissions[acp_users_add_group]||$user_permissions[acp_users_edit_group]||$user_permissions[acp_users_delete_group]}
<li>
	<p>
		<a href="">{lang:admin:users_groups}</a> 
		<a href="" onclick="return collapse('_users');" id="button_users" class="collapse">-</a>
	</p>
	
	<ul class="children" id="children_users">
{if:$user_permissions[acp_users_add_user]}
		<li><a href="admin.php?cmd=users_add_user">{lang:admin:users_add}</a></li>
{endif}
{if:$user_permissions[acp_users_edit_user]}
		<li><a href="admin.php?cmd=users_edit_user">{lang:admin:users_edit}</a></li>
{endif}
{if:$user_permissions[acp_users_delete_user]}
		<li><a href="admin.php?cmd=users_delete_user">{lang:admin:users_delete}</a></li>
{endif}
{if:$user_permissions[acp_users_add_group]}
		<li><a href="admin.php?cmd=users_add_group">{lang:admin:groups_add}</a></li>
{endif}
{if:$user_permissions[acp_users_edit_group]}
		<li><a href="admin.php?cmd=users_edit_group">{lang:admin:groups_edit}</a></li>
{endif}
{if:$user_permissions[acp_users_delete_group]}
		<li><a href="admin.php?cmd=users_delete_group">{lang:admin:groups_delete}</a></li>
{endif}
	</ul>
</li>
{endif}

<li>
	<p>
		<a href="">{lang:general:title}</a>
		<a href="" onclick="return collapse('_od');" id="button_od" class="collapse">-</a>
	</p>

	<ul class="children" id="children_od">
		<li><a href="admin.php?cmd=od_updates">{lang:admin:updates}</a></li>
		<li><a href="admin.php?cmd=od_license">{lang:admin:license}</a></li>
	</ul>
</li>
</ul>
</div>
	
<div id="contentarea">
	<ul id="menu"><li><a href="admin.php">{lang:admin:index}</a></li><li><a href="index.php">{lang:admin:view_site}</a></li>{if:$user_permissions[acp_main_settings]}<li><a href="admin.php?cmd=main_settings">{lang:admin:settings}</a></li>{endif}{if:$user_permissions[acp_files_add_file]}<li><a href="admin.php?cmd=files_add_file">{lang:admin:add_file}</a></li>{endif}
	</ul>