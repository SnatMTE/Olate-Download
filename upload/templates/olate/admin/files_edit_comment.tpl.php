<h1>{lang:admin:comments_edit}</h1>
{if: !isset($empty)}
{if: !isset($success)}
<p>{lang:admin:comments_edit_desc}</p>
<form action="admin.php?cmd=files_edit_comment" method="post">
<h2>{lang:admin:comment_details}</h2>
		
	<div class="comments">
	<h3>{lang:admin:name}</h3>
	<p><input type="text" size="30" name="name" value="{$comment[name]}" /></p>
			
	<h3>{lang:admin:email}</h3>
	<p><input type="text" size="30" name="email" value="{$comment[email]}" /></p>
		
	<h3>{lang:admin:comment}</h3>
	<p><textarea rows="6" cols="30" name="comment">{$comment[comment]}</textarea></p>
		
	<p><input type="submit" name="submit" value="{lang:admin:edit}" />
		<input name="id" type="hidden" value="{$comment[id]}" />
		{if: !empty($redir)}
			<input name="redir" type="hidden" value="{$redir}" />
		{endif}
		
		{if: !empty($file_id)}
			<input name="file_id" type="hidden" value="{$file_id}" />
		{endif}
	</p>
	<p class="small">{lang:admin:formatting_tags}<br /><strong>[b]{lang:admin:bold}[/b]</strong>, <em>[i]{lang:admin:italic}[/i]</em>, <ins>[u]{lang:admin:underline}[/ins]</ins>, [url]http://www.google.com[/url]</p>
	</div>
</form>
{else}
<p>{lang:admin:comments_edited}</p>
{endif}
{else}
<p>{lang:admin:comments_specify}</p>
{endif}