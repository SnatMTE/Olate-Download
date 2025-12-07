<h1>{lang:admin:comments_manage}</h1>

<p>{lang:admin:search_terms}</p>
<form action="admin.php?cmd=files_manage_comments" method="post" id="search">
  <table cellpadding="4" cellspacing="0" class="form">
    <tr>
      <td class="formleft">{lang:admin:comment_id}</td>
      <td><input name="comment_id" type="text" id="comment_id" size="5" /></td>
    </tr>
    <tr>
      <td class="formleft">{lang:admin:file}</td>
      <td><select name="file_id" id="file_id">
        <option selected="selected">{lang:admin:select_file}</option>
        {block:files}
		<option value="{$file[id]}">{$file[cat_name]} > {$file[name]}</option>
		{/block:files}
      </select></td>
    </tr>
    <tr>
      <td class="formleft">{lang:admin:date}</td>
      <td><input name="date" type="text" id="date" size="9" />
      {lang:admin:file_date_format}</td>
    </tr>
    <tr>
      <td class="formleft">{lang:admin:poster_name}</td>
      <td><input name="name" type="text" id="name" size="20" /></td>
    </tr>
    <tr>
		<td class="formleft">{lang:admin:poster_email}</td>
      <td><input name="email" type="text" id="email" size="35" /></td>
    </tr>
    <tr>
      <td class="formleft">Status</td>
      <td><select name="status" id="status">
          <option selected="selected">{lang:admin:select_status}</option>
          <option value="1">{lang:admin:approved}</option>
          <option value="0">{lang:admin:unapproved}</option>
      </select></td>
    </tr>
    <tr>
    	<td>
    		  <input type="submit" name="submit" value="{lang:admin:search}" />
			  <input name="search" type="hidden" value="1" />
		</td>
	</tr>
  </table>
</form>