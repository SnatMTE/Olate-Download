<h1>{lang:admin:general_settings}</h1>

{if: isset($reset)}
<p>{lang:admin:statistics_reset_done}</p>
{elseif: !isset($success)}
<p>{lang:admin:general_settings_desc}</p>
    <form action="admin.php?cmd=main_settings" method="post">
    	<h2>{lang:admin:general_options}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:site_name}</td>
            	<td><input name="site_name" type="text" id="site_name" value="{$site_name}" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:base_url}</td>
            	<td><input name="url" type="text" id="url" value="{$url}" size="35" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:admin_email}</td>
            	<td><input name="admin_email" type="text" id="admin_email" value="{$admin_email}" size="35" /></td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:flood_interval}</td>
        		<td><input name="flood_interval" type="text" id="flood_interval" value="{$flood_interval}" size="3" /></td>
       		</tr>
{if: $user_permissions[acp_languages]}
        	<tr>
        		<td class="formleft">{lang:admin:language}</td>
        		<td><a href="admin.php?cmd=languages">{lang:admin:language_settings}</td>
        	</tr>
{endif}
        	<tr>
        		<td class="formleft">{lang:admin:template}</td>
        		<td><select name="template" id="template">
        			<option value="{$template}" selected="selected">{$template}</option>
        			<option value="{$template}">{lang:admin:select_template}</option>
        			{block:templates}
					<option value="{$template_name}">{$template_name}</option>
					{/block:templates}
       			</select></td>
        	</tr>
        	<tr>
        		<td class="formleft">{lang:admin:date_format} (<a href="http://www.php.net/date">{lang:admin:help}</a>):</td>
        		<td><input name="date_format" type="text" id="date_format" value="{$date_format}" size="12" /></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:file_size_format}</td>
        		<td><select name="filesize_format" id="filesize_format">
        			<option value="{$filesize_format}" selected="selected">{if: $filesize_format == '--'}
{lang:admin:auto_select}
{else}
{$filesize_format}{endif}</option>
        			<option value="{$filesize_format}">{lang:admin:select_format}</option>
        			<option value="{lang:admin:file_size_format_b}">{lang:admin:file_size_format_b}</option>
        			<option value="{lang:admin:file_size_format_kb}">{lang:admin:file_size_format_kb}</option>
        			<option value="{lang:admin:file_size_format_mb}">{lang:admin:file_size_format_mb}</option>
        			<option value="{lang:admin:file_size_format_gb}">{lang:admin:file_size_format_gb}</option>
        			<option value="--">{lang:admin:auto_select}</option>
       			</select></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:no_mirrors}</td>
        		<td><input name="mirrors" type="text" id="mirrors" value="{$mirrors}" size="3" /></td>
       		</tr>
   	  </table>
		<h2>{lang:admin:feature_options_general}</h2>
		<table cellpadding="4" cellspacing="0" class="form">
          <tr>
            <td class="formleft">{lang:admin:no_files_page}</td>
            <td><input name="page_amount" type="text" id="page_amount22" value="{$page_amount}" size="3" /></td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:comments_enabled}</td>
            <td><input name="enable_comments" type="checkbox" id="enable_comments" value="1" {if: $enable_comments } checked="checked" 
				{endif}/></td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:comments_require_approval}</td>
            <td><input name="approve_comments" type="checkbox" id="approve_comments" value="1" {if: $approve_comments } checked="checked" 
				{endif}/></td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:rating_enabled}</td>
            <td><input name="enable_ratings" type="checkbox" id="enable_ratings" value="1" {if: $enable_ratings } checked="checked" 
				{endif}/></td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:statistics_enabled}</td>
            <td><input name="enable_stats" type="checkbox" id="enable_stats" value="1" {if: $enable_stats } checked="checked" 
				{endif}/></td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:rss_enabled}</td>
            <td><input name="enable_rss" type="checkbox" id="enable_rss" value="1" {if: $enable_rss } checked="checked" 
				{endif}/></td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:user_uploads_enabled}</td>
            <td><input name="enable_useruploads" type="checkbox" id="enable_useruploads" value="1" {if: $enable_useruploads } checked="checked" 
				{endif}/></td>
          </tr>
		  <tr>
            <td class="formleft">{lang:admin:user_allow_actual_upload}</td>
            <td><input name="enable_actual_upload" type="checkbox" id="enable_actual_upload" value="1" {if: $enable_actual_upload } checked="checked" 
				{endif}/></td>
          </tr>
		  <tr>
            <td class="formleft">{lang:admin:user_mirrors_enabled}</td>
            <td><input name="enable_mirrors" type="checkbox" id="enable_mirrors" value="1" {if: $enable_mirrors } checked="checked" 
				{endif}/></td>
          </tr>
		  <tr>
            <td class="formleft">{lang:admin:userupload_always_approve}</td>
            <td><input name="userupload_always_approve" type="checkbox" id="userupload_always_approve" value="1" {if: $userupload_always_approve } checked="checked" 
				{endif}/></td>
          </tr>
		  <tr>
            <td class="formleft">{lang:admin:leech_protection_enabled}</td>
            <td>
            	<input name="enable_leech_protection" type="checkbox" id="enable_leech_protection" value="1" {if: $enable_leech_protection } checked="checked" 
				{endif}/> 
            	{if: $user_permissions[acp_leech_settings]}	
            		<a href="admin.php?cmd=leech_settings">Advanced settings</a>
            	{endif}
            </td>
          </tr>
		  <tr>
            <td class="formleft">{lang:admin:uploads_ext}</td>
            <td><input name="uploads_allowed_ext" type="text" id="uploads_allowed_ext" value="{$uploads_allowed_ext}" size="35" /> {lang:admin:uploads_separate}</td>
          </tr>
          <tr>
            <td class="formleft">{lang:admin:acp_check_extensions}</td>
            <td><input name="acp_check_extensions" type="checkbox" id="acp_check_extensions" value="1" {if: $acp_check_extensions } checked="checked" 
				{endif}/></td>
          </tr><tr>
            <td class="formleft">{lang:admin:wysiwyg_editor}</td>
            <td>
            	{if: $wysiwyg_disabled === false}
            		{lang:admin:wysiwyg_editor_not_exist}
            	{else}
            		<input name="use_fckeditor" type="checkbox" id="use_fckeditor" value="1" {if: $use_fckeditor } checked="checked" 
						{endif} />
            	{endif}
            </td>
          </tr>
          <tr>
          	<td class="formleft">{lang:admin:allow_user_language}</td>
          	<td>
          		<input name="allow_user_lang" type="checkbox" id="allow_user_lang" value="1" {if: $allow_user_lang } checked="checked" 
				{endif}/>
          	</td>
          </tr>
        </table>
		<h2>{lang:admin:feature_options_menu}</h2>
		<table cellpadding="4" cellspacing="0" class="form">
        	<tr>
            	<td class="formleft">{lang:admin:no_latest_files}</td>
            	<td><input name="latest_files" type="text" id="latest_files" value="{$latest_files}" size="3" /></td>
       		</tr>
        	<tr>
              <td class="formleft">{lang:admin:no_top_files}</td>
              <td><input name="top_files" type="text" id="top_files" value="{$top_files}" size="3" /></td>
      	  </tr>
        	<tr>
        	  <td class="formleft">{lang:admin:enable_topfiles}</td>
        	  <td><input name="enable_topfiles" type="checkbox" id="enable_topfiles" value="1" {if: $enable_topfiles }checked="checked" 
				{endif}/></td>
      	  </tr>
        	<tr>
        	  <td class="formleft">{lang:admin:enable_allfiles}</td>
        	  <td><input name="enable_allfiles" type="checkbox" id="enable_allfiles" value="1" {if: $enable_allfiles }checked="checked" 
				{endif}/></td>
      	  </tr>
        	<tr>
        		<td class="formleft">{lang:admin:search_enabled}</td>
        		<td><input name="enable_search" type="checkbox" id="enable_search" value="1" {if: $enable_search }checked="checked" 
				{endif}/></td>
       		</tr>
			<tr>
        		<td class="formleft">{lang:admin:count_enabled}</td>
        		<td><input name="enable_count" type="checkbox" id="enable_count" value="1" {if: $enable_count }checked="checked" 
				{endif}/></td>
       		</tr>
       		<tr>
        		<td class="formleft">{lang:admin:filter_cats}</td>
        		<td><input name="filter_cats" type="checkbox" id="filter_cats" value="1" {if: $filter_cats }checked="checked" 
				{endif}/></td>
       		</tr>
   	  </table>
   	  <h2>{lang:admin:recommend_friend_options}</h2>
   	  <table class="form">
   	  	<tr>
   	  		<td class="formleft">{lang:admin:recommend_friend_enable}</td>
   	  		<td><input name="enable_recommend_friend" type="checkbox" id="enable_recommend_friend" value="1" {if: $enable_recommend_friend }checked="checked" 
				{endif}/></td>
   	  	</tr>
   	  	<tr>
   	  		<td class="formleft">{lang:admin:recommend_friend_confirm}</td>
   	  		<td><input name="enable_recommend_confirm" type="checkbox" id="enable_recommend_confirm" value="1" {if: $enable_recommend_confirm }checked="checked" 
				{endif}/><br />{lang:admin:recommend_friend_confirm_desc}</td>
   	  	</tr>
   	  	
   	  </table>
			<p>
				<input type="submit" name="submit" value="{lang:admin:update}" />
				<input name="version" type="hidden" value="{$version}" />
			</p>
		<h2>{lang:admin:additional_options}</h2>
		<p><a href="admin.php?cmd=main_settings&amp;stats_reset=1">{lang:admin:reset_statistics}</a></p>
    </form>
{else}
<p>{lang:admin:updates_made}</p>
{endif}
