<h1>{lang:admin:file_add}</h1>
{if: !isset($success)}
<p>{lang:admin:file_add_desc}</p>
    <form action="userupload.php?cmd=files_add_file" method="post" id="add">
    	<h2>{lang:admin:file_details}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:file_name}<span style="color:#CC0000; ">*</span></td>
            	<td><input name="name" type="text" id="name" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:short_description}<span style="color:#CC0000; ">*</span></td>
            	<td>
            		{if: $use_fckeditor}
            			{$desc_small_html}
            		{else}
            			<textarea name="description_small" cols="45" rows="3" id="description_small"></textarea>
            		{endif}
            	</td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:large_description}<span style="color:#CC0000; ">*</span></td>
            	<td>
            		{if: $use_fckeditor}
            			{$desc_big_html}
            		{else}
            			<textarea name="description_big" cols="45" rows="5" id="description_big"></textarea>
            		{endif}
            	</td>
   			</tr>
   			<tr>
            	<td class="formleft">{lang:admin:convert_newlines}<span style="color:#CC0000; ">*</span></td>
            	<td>
            		<input type="radio" name="convert_newlines" value="1" /> {lang:admin:yes}<br />
            		<input type="radio" name="convert_newlines" value="0" checked="checked" /> {lang:admin:no}<br />
            		{lang:admin:convert_newlines_desc}
            	</td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:category}<span style="color:#CC0000; ">*</span></td>
        		<td><select name="category" id="category">
        			<option selected="selected">{lang:admin:select_category}</option>
					{block:cats}
        			<option value="{$category[id]}">{$category[name]}</option>
					{/block:cats}
					</select></td>
        	</tr>
        	<tr>
        		<td class="formleft">{lang:admin:file_size}<span style="color:#CC0000; ">*</span></td>
        		<td>
        			<input name="size" type="text" id="size" size="5" />
        			<select name="filesize_format" id="filesize_format">
	        			<option value="b">{lang:admin:file_size_format_b}</option>
	        			<option value="kb">{lang:admin:file_size_format_kb}</option>
	        			<option value="mb" selected="selected">{lang:admin:file_size_format_mb}</option>
	        			<option value="gb">{lang:admin:file_size_format_gb}</option>
       				</select>
        		</td>
       		</tr>
        </table>
                {if: isset($custom_field)}
 <h2>{lang:admin:custom_fields}</h2>
 <p>{lang:admin:leave_blank_ignore}</p>
 <table cellpadding="4" cellspacing="0" class="form">
     {block:custom_fields}
  <tr>
   <td class="formleft">{$custom_field[label]}</td>
   <td>
    <input name="custom_field_{$custom_field[uid]}_value" type="text" id="custom_field_{$custom_field[uid]}_value" value="{$custom_field[value]}" size="35" />
    <input name="custom_field_{$custom_field[uid]}_field_id" type="hidden" id="custom_field_{$custom_field[uid]}_field_id" value="{$custom_field[id]}" />
   </td>
  </tr>
 {/block:custom_fields}
 </table>
 <div style="display: none">
  <input name="custom_field_total" type="hidden" id="custom_field_total" value="{$custom_field_total}" />
 </div>
       {endif}
       	
       {if: $site_config[enable_actual_upload] == 1}
		<h2>{lang:admin:upload_file}</h2>
		<p>{lang:admin:upload_file_desc_1}</p>
		<table cellpadding="4" cellspacing="0" class="form" style="text-align:left">
		<tr>
			<td class="formleft">{lang:admin:upload_file}</td>
			<td><input name="upload" type="checkbox" id="upload" value="1" /></td>
		</tr>
		</table>
		{endif}
		{if: $site_config[enable_mirrors] == 1}
		<h2>{lang:admin:download_locations}</h2>
		<table cellpadding="4" cellspacing="0" class="form" style="text-align:center">
		<tr>
			<td class="formleft">{lang:admin:name}<span style="color:#CC0000; ">*</span></td>
			<td class="formleft">{lang:admin:location}<span style="color:#CC0000; ">*</span></td>
			<td class="formleft">{lang:admin:file_url}<span style="color:#CC0000; ">*</span></td>
		</tr>
		{block:mirror}
		<tr>
			<td><input type="text" name="mirror{$mirror}_name" /></td>
			<td><input type="text" name="mirror{$mirror}_location" /></td>
			<td><input type="text" name="mirror{$mirror}_url" size="50" /></td>
		</tr>
		{/block:mirror}
	  </table>
	  {endif}
		<p><input type="submit" name="submit" value="{lang:admin:add}" /><input name="submit" type="hidden" id="submit" value="1" /></p>
</form>
{elseif: isset($success) && $success === false}
<p>{lang:admin:add_file_check_required_fields}</p>
{else}
{if: $site_config[userupload_always_approve] == 1}
<p><a href="details.php?file={$id}">{lang:admin:file_added}</a></p>
{else}
<p>{lang:frontend:added_pending}</p>
{endif}
{endif}