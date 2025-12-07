<h1>{lang:admin:upload_file}</h1>
{if: !isset($success)}
<p>{lang:admin:upload_file_desc_2}</p>
    <form enctype="multipart/form-data" action="userupload.php?cmd=files_add_file" method="post" name="upload" id="upload">
		<h2>{lang:admin:upload_file}</h2>
		<table cellpadding="4" cellspacing="0" class="form" style="text-align:left">
		<tr>
			<td class="formleft">
				<input name="uploadfile" type="file" /> 
				({lang:frontend:allowed_extensions}: {$global_vars[uploads_allowed_ext]} 
				{lang:frontend:max_upload_size}: {$max_upload_size})<br />
{block:submitted_data}
				<input type="hidden" name="{$key}" value="{$value}" />
{/block:submitted_data}
				<input name="submit" type="submit" value="{lang:admin:upload}" />
			</td>
		</tr>
		</table>
	</form>
{elseif: isset($success) && $success === true}
<p><a href="details.php?file={$id}">{lang:admin:file_added}</a></p>
{else}
<p>{$error}</p>
{endif}