<h1>{lang:admin:comments_manage}</h1>
{if: $no_results}
<p>{lang:admin:no_results}</p>
{else}
<p>{lang:admin:the} {$result_count} {lang:admin:the_results}</p>
<form action="admin.php?cmd=files_manage_comments" method="post" id="comments">
<table cellspacing="0" cellpadding="0" style="width:100%;text-align:center">
    <tr>
      <td><h2>{lang:admin:select}</h2></td>
      <td><h2>{lang:admin:date}</h2></td>
      <td><h2>{lang:admin:file}</h2></td>
      <td><h2>{lang:admin:poster_name}</h2></td>
      <td><h2>{lang:admin:status}</h2></td>
	  <td><h2>{lang:admin:actions}</h2></td>
    </tr>
	{block:comments}
    <tr>      
	  <td><input name="comment[{$comment[id]}]" type="checkbox" value="{$comment[id]}" /></td>
      <td>{$comment[timestamp]}</td>
      <td><a href="admin.php?cmd=files_edit_file&amp;action=file_select&amp;file_id={$file[id]}">{$file[name]}</a></td>
      <td>{$comment[name]}</td>
      <td>{$comment[status]}</td>	
	  <td><a href="admin.php?cmd=files_edit_comment&amp;id={$comment[id]}">{lang:admin:edit}</a></td>	  
    </tr>
	{/block:comments}
  </table>
  <p>
    <select name="action" id="action">
      <option selected="selected">{lang:admin:select_action}</option>
      <option value="1">{lang:admin:delete}</option>
      <option value="2">{lang:admin:unapprove}</option>
      <option value="3">{lang:admin:approve}</option>
    </select>
    <input type="submit" name="Submit" value="{lang:admin:selected_comments}" />
    <input name="perform" type="hidden" id="perform" value="1" />
</p>
</form>
{endif}