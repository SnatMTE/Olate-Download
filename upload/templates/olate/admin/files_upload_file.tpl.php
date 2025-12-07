<h1>{lang:admin:upload_file}</h1>
{if: isset($id)}
<p>{lang:admin:upload_file_desc_2}</p>
    <form enctype="multipart/form-data" action="admin.php?cmd=files_add_file&file_id={$id}" method="post" name="upload" id="upload">
		<h2>{lang:admin:upload_file}</h2>
		<table cellpadding="4" cellspacing="0" class="form" style="text-align:left">
		<tr>
			<td class="formleft"><input name="uploadfile" type="file"> <input name="submit" type="submit" value="{lang:admin:upload}"></td>
		</tr>
		</table>
	</form>
{else}
<p>{$error}</p>
{endif}