<h1>{lang:admin:categories_edit}</h1>
{if: !isset($success)}
<p>{lang:admin:categories_edit_desc}</p>
    <form action="admin.php?cmd=categories_edit" method="post">
    	<h2>{lang:admin:category_details}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:name}</td>
            	<td><input name="name" type="text" id="name" value="{$current_category[name]}" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:description}</td>
            	<td><textarea name="description" cols="45" rows="3" id="description">{$current_category[description]}</textarea></td>
   			</tr>
   			<tr>
            	<td class="formleft">{lang:admin:keywords}</td>
            	<td><textarea name="keywords" cols="45" rows="3" id="keywords">{$current_category[keywords]}</textarea><br />{lang:admin:separate_comma}</td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:parent_category}</td>
        		<td><select name="parent_id" id="parent_id">
        			<option value="{$parent_id}" selected="selected">{$category_name}</option>
        			<option value="{$parent_id}">------------</option>
					<option value="0">{lang:admin:make_parent_category}</option>
					{block:cats}
        			<option value="{$category[id]}">{$category[name]}</option>
					{/block:cats}          		
        		</select></td>
        	</tr>
        	<tr>
            	<td class="formleft">{lang:admin:order}</td>
            	<td><input name="sort" type="text" id="sort" value="{$current_category[sort]}" size="5" /></td>
       		</tr>
        	</table>
		<p><input type="submit" name="Submit" value="{lang:admin:edit}" />
			<input name="action" type="hidden" id="action" value="edit" />
			<input name="id" type="hidden" id="id" value="{$current_category[id]}" /></p>
    </form>
{else}
<p>{lang:admin:categories_edit_done}.</p>
{endif}