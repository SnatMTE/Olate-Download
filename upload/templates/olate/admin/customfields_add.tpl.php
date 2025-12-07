<h1>{lang:admin:custom_fields_add}</h1>
{if: !isset($success)}
<p>{lang:admin:custom_fields_add_desc}</p>
    <form action="admin.php?cmd=customfields_add" method="post">
    	<h2>{lang:admin:field_details}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:label}</td>
            	<td><input name="label" type="text" id="label" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:value}</td>
            	<td><input name="value" type="text" id="value" size="30" /></td>
   			</tr>
        	</table>
		<p><input type="submit" name="Submit" value="{lang:admin:add}" />
           <input name="submit" type="hidden" id="submit" value="1" /></p>
    </form>
{else}
<p>{lang:admin:custom_fields_added}</p>
{endif}