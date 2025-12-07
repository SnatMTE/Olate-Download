<h1>{lang:admin:users_add}</h1>
{if:!isset($success)}
<p>{lang:admin:users_add_desc}</p>
    <form action="admin.php?cmd=users_add_user" method="post">
    	<h2>{lang:admin:required_information}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:username}</td>
            	<td><input name="username" type="text" id="username" size="20" value="{$post_vars[username]}" /></td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:password}</td>
        		<td><input name="password" type="password" id="password" size="20" /></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:password_confim}</td>
        		<td><input name="confirm" type="password" id="confirm" size="20" /></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:email}</td>
        		<td><input name="email" type="text" id="email" size="20" value="{$post_vars[email]}" /></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:user_group}</td>
        		<td><select name="group" id="group">
        		<option selected="selected">{lang:admin:select_group}</option>
        		{block:group}
        		<option value="{$group[id]}">+ {$group[name]}</option>
        		{/block:group}
        		</select></td>
       		</tr>
        </table>
    	<h2>{lang:admin:optional_information}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:first_name}</td>
            	<td><input name="firstname" type="text" id="firstname" size="20" value="{$post_vars[firstname]}" /></td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:surname}</td>
        		<td><input name="lastname" type="text" id="lastname" size="20" value="{$post_vars[lastname]}" /></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:location}</td>
        		<td><input name="location" type="text" id="location" size="20" value="{$post_vars[location]}" /></td>
       		</tr>
    		<tr style="vertical-align:top">
            	<td class="formleft">{lang:admin:signature}</td>
            	<td><textarea name="signature" cols="30" rows="5" id="signature">{$post_vars[signature]}</textarea></td>
   			</tr>
        </table>
		<p><input type="submit" name="submit" value="{lang:admin:add}" /></p>
	</form>
{else}
<p>{lang:admin:the_user} {$post_vars[username]}{lang:admin:successfully_added}</p>
{endif}