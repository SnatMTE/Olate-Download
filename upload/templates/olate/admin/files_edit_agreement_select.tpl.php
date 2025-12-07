<h1>{lang:admin:select_agreement_title}</h1>
<p>{lang:admin:agreement_edit_select}</p>
    <form action="admin.php?cmd=files_edit_agreement" method="post">
    	<h2>{lang:admin:agreement}</h2>
		<p><select name="id" id="id">
        			<option value="0" selected="selected">{lang:admin:select_agreement}</option>
					{block:agreements}
					<option value="{$agreement[id]}">{$agreement[name]}</option>
					{/block:agreements}                	
        		</select></p>
		<p><input type="submit" name="Submit" value="{lang:admin:edit}" />
			<input name="action" type="hidden" id="action" value="select" /></p>
    </form>