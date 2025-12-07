<h1>{lang:admin:groups_add}</h1>
{if:!isset($success)}
<form action="admin.php?cmd=users_add_group" method="post">
<h2>{lang:admin:name}:</h2>
<p><input name="name" type="text" id="name" size="20" value="{$post_vars[name]}" /></p>
		<h2>{lang:admin:permissions}</h2>
		<div class="box">
		<h2>{lang:admin:general} <a href="" onclick="return collapse_admin('general');" id="button_acp_general" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_general">
	        	<tr>
					<td class="formleft">{lang:admin:view_acp}</td>
	        		<td><input name="permissions[acp_view]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:edit_general_settings}</td>
	        		<td><input name="permissions[acp_main_settings]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:language_settings}</td>
	        		<td><input name="permissions[acp_languages]" type="checkbox" /></td>
	       		</tr>
	       	</table>
	    <h2>{lang:admin:security} <a href="" onclick="return collapse_admin('security');" id="button_acp_security" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_security">
		    	<tr>
		    		<td class="formleft">{lang:admin:ip_restriction}</td>
		    		<td>
		    			<input name="permissions[acp_ip_restrict_main]" type="checkbox" />
		    		</td>
		    	</tr>
		    	<tr>
		    		<td class="formleft">{lang:admin:leech_settings}</td>
		    		<td>
		    			<input name="permissions[acp_leech_settings]" type="checkbox" />
		    		</td>
		    	</tr>
		    	
	    	</table>
	    <h2>{lang:admin:categories} <a href="" onclick="return collapse_admin('categories');" id="button_acp_categories" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_categories">    	
	    		<tr>
					<td class="formleft">{lang:admin:add_categories}</td>
	        		<td><input name="permissions[acp_categories_add]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:edit_categories}</td>
	        		<td><input name="permissions[acp_categories_edit]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:delete_categories}</td>
	        		<td><input name="permissions[acp_categories_delete]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
	        		<td class="formleft">{lang:admin:categories_delete_multi}</td>
	        		<td><input name="permissions[acp_categories_delete_multiple]" type="checkbox" /></td>
        		</tr>
	        	<tr>
	        		<td class="formleft">{lang:admin:categories_ordering}</td>
	        		<td><input name="permissions[acp_categories_ordering]" type="checkbox" /></td>
        		</tr>
	       	</table>
		<h2>{lang:admin:files} <a href="" onclick="return collapse_admin('files');" id="button_acp_files" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_files"> 
	    		<tr>
					<td class="formleft">{lang:admin:files_approve}</td>
	        		<td><input name="permissions[acp_files_approve_files]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:file_add}</td>
	        		<td><input name="permissions[acp_files_add_file]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:file_edit}</td>
	        		<td><input name="permissions[acp_files_edit_file]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:file_delete}</td>
	        		<td><input name="permissions[acp_files_delete_file]" type="checkbox" /></td>
	       		</tr>
	       		<tr>
					<td class="formleft">{lang:admin:file_mass_move}</td>
	        		<td><input name="permissions[acp_files_mass_move]" type="checkbox" /></td>
	       		</tr>
	       		<tr>
					<td class="formleft">{lang:admin:file_mass_delete}</td>
	        		<td><input name="permissions[acp_files_mass_delete]" type="checkbox" /></td>
	       		</tr>
	       	</table>
		<h2>{lang:admin:agreements} <a href="" onclick="return collapse_admin('agreements');" id="button_acp_agreements" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_agreements"> 
	        	<tr>
					<td class="formleft">{lang:admin:agreement_add}</td>
	        		<td><input name="permissions[acp_files_add_agreement]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:agreement_edit}</td>
	        		<td><input name="permissions[acp_files_edit_agreement]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:agreement_delete}</td>
	        		<td><input name="permissions[acp_files_delete_agreement]" type="checkbox" /></td>
	       		</tr>
	        </table>
	    <h2>{lang:admin:comments} <a href="" onclick="return collapse_admin('comments');" id="button_acp_comments" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_comments"> 
	        	<tr>
					<td class="formleft">{lang:admin:comments_manage}</td>
	        		<td><input name="permissions[acp_files_manage_comments]" type="checkbox" /></td>
	       		</tr>
				<tr>
					<td class="formleft">{lang:admin:comments_approve}</td>
	        		<td><input name="permissions[acp_files_approve_comments]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:comments_edit_existing}</td>
	        		<td><input name="permissions[acp_files_edit_comment]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:comments_delete_existing}</td>
	        		<td><input name="permissions[acp_files_delete_comment]" type="checkbox" /></td>
	       		</tr>
	        </table>
		<h2>{lang:admin:custom_fields} <a href="" onclick="return collapse_admin('custom_fields');" id="button_acp_custom_fields" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_custom_fields"> 
	        	<tr>
					<td class="formleft">{lang:admin:custom_fields_add}</td>
	        		<td><input name="permissions[acp_customfields_add]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:custom_fields_edit}</td>
	        		<td><input name="permissions[acp_customfields_edit]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:custom_fields_delete}</td>
	        		<td><input name="permissions[acp_customfields_delete]" type="checkbox" /></td>
	       		</tr>
	       	</table>
	    <h2>{lang:admin:users_groups} <a href="" onclick="return collapse_admin('users');" id="button_acp_users" class="collapse">-</a></h2>
	    	<table cellpadding="4" cellspacing="0" class="form" id="acp_users"> 
	        	<tr>
					<td class="formleft">{lang:admin:users_add}</td>
	        		<td><input name="permissions[acp_users_add_user]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:users_edit}</td>
	        		<td><input name="permissions[acp_users_edit_user]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:users_delete}</td>
	        		<td><input name="permissions[acp_users_delete_user]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:groups_add}</td>
	        		<td><input name="permissions[acp_users_add_group]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:groups_edit}</td>
	        		<td><input name="permissions[acp_users_edit_group]" type="checkbox" /></td>
	       		</tr>
	        	<tr>
					<td class="formleft">{lang:admin:groups_delete}</td>
	        		<td><input name="permissions[acp_users_delete_group]" type="checkbox" /></td>
	       		</tr>
	        </table>
<p><input type="submit" name="submit" value="{lang:admin:add}" /></p>
</div>
</form>
{else}
<p>{lang:admin:the_group} {$post_vars[name]}{lang:admin:successfully_added}</p>
{endif}