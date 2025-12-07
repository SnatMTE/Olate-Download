<h1>{lang:admin:agreement_delete}</h1>
{if: $result == 3}
<p>{lang:admin:agreement_must_select}</p>
{elseif: $result == 1}
<p>{lang:admin:agreement_deleted}</p>
{elseif: $result == 2}
<p>{lang:admin:agreement_still_files}</p>
{elseif: $result == 4}
<p>{lang:admin:agreement_not_deleted}</p>
{else}
<p>{lang:admin:agreement_delete_select}</p>
<h2>{lang:admin:category}</h2>
    <form action="admin.php?cmd=files_delete_agreement" method="post">
    	<table cellpadding="4" cellspacing="1" class="fullwidth lborder">
        	<tr>
        		<td class="trow formleft_admin">{lang:admin:agreement}</td>
        		<td class="trow"><select name="id" id="id">
        			<option value="0" selected="selected">{lang:admin:select_agreement}</option>
					{block:agreements}
					<option value="{$agreement[id]}">{$agreement[name]}</option>
					{/block:agreements}                	
        		</select></td>
        	</tr>
        	</table>
		<p><input type="submit" name="Submit" value="{lang:admin:delete}" />
		   <input name="submit" type="hidden" id="submit" value="1" />
		</p>
    </form>
{endif}