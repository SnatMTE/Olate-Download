<h1>{lang:admin:categories_add}</h1>
{if: !isset($success)}
<p>{lang:admin:categories_add_desc}:</p>
    <form action="admin.php?cmd=categories_add" method="post">
    	<h2>{lang:admin:category_details}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:name}</td>
            	<td><input name="name" type="text" id="name" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:description}</td>
            	<td><textarea name="description" cols="45" rows="3" id="description"></textarea></td>
   			</tr>
   			<tr>
            	<td class="formleft">{lang:admin:keywords}</td>
            	<td><textarea name="keywords" cols="45" rows="3" id="keywords"></textarea><br />{lang:admin:separate_comma}</td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:category_parent}</td>
        		<td><select name="parent_id" id="parent_id">
        			<option value="0" selected="selected">{lang:admin:none}</option>
					{block:cats}
        			<option value="{$category[id]}">{$category[name]}</option>
					{/block:cats}
        		</select></td>
        	</tr>
        	<tr>
        		<td class="formleft">{lang:admin:order}</td>
        		<td><input name="sort" type="text" id="sort" size="5" /></td>
       		</tr>
        	</table>
		<p><input type="submit" name="Submit" value="{lang:admin:add}" />
           <input name="submit" type="hidden" id="submit" value="1" /></p>
    </form>
{else}
<p>{lang:admin:categories_added}.</p>
{endif}