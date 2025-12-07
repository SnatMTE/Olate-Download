<h1>{lang:admin:agreement_edit}</h1>
{if: !isset($success)}
<p>{lang:admin:agreement_edit_desc}</p>
    <form action="admin.php?cmd=files_edit_agreement" method="post">
    	<h2>{lang:admin:name}</h2>
		<p><input name="name" type="text" id="name" value="{$agreement[name]}" size="30" /></p>
   		<h2>{lang:admin:contents}</h2>
   		<p>
   		{if: $use_fckeditor}
   			{$contents_html}
   		{else}
   			<textarea name="contents" cols="55" rows="8" id="contents">{$agreement[contents]}</textarea></p>
   		{endif}
		<p>
			<input type="submit" name="Submit" value="{lang:admin:edit}" />
            <input name="action" type="hidden" id="action" value="edit" />
			<input name="id" type="hidden" id="id" value="{$agreement[id]}" />
		</p>
    </form>
{else}
<p>{lang:admin:updates_made}</p>
{endif}