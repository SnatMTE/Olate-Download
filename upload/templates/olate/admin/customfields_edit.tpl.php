<h1>{lang:admin:custom_fields_edit}</h1>
{if: !isset($success)}
<p>{lang:admin:custom_fields_add_desc}</p>
    <form action="admin.php?cmd=customfields_edit&amp;action=edit" method="post">
    	<h2>{lang:admin:field_details}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:label}</td>
            	<td><input name="label" type="text" id="label" value="{$customfield[label]}" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:value}</td>
            	<td><input name="value" type="text" id="value" value="{$customfield[value]}" size="30" /></td>
   			</tr>
   	  </table>
		<p><input type="submit" name="Submit" value="{lang:admin:edit}" />
           <input name="submit" type="hidden" id="submit" value="1" />
           <input name="id" type="hidden" id="id" value="{$customfield[id]}" />
		</p>
    </form>
{else}
<p>{lang:admin:custom_fields_edited}</p>
{endif}