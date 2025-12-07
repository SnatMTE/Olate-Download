<h1>{lang:frontend:recommend_friend}</h1>
{if: empty($hide_form) || $hide_form === false}
<form action="recommend.php?file={$file[id]}" method="post">
	<p>{lang:frontend:recommend_friend_desc}</p>
	<h2>{lang:admin:file_details}</h2>
	
	<div class="box">
	<h2 class="filebox_breadcrumb">
		<a href="files.php?cat={$file[cat_id]}">{$file[cat_name]}</a> &#187; 
		<a href="details.php?file={$file[id]}">{$file[name]}</a>
	</h2>
	
	<p>{$file[description_small]}</p>
	<div class="filebox_links">
		{lang:frontend:date}: {$file[date]} -
		<a href="details.php?file={$file[id]}" title="{lang:frontend:view_more_about} '{$file[name]}'">{lang:frontend:view_full}</a> -
		{lang:frontend:downloads}: {$file[downloads]} -
		{lang:frontend:filesize}: {$file[size]}{$filesize_format}
	</div>
	</div>
	
	{if: $has_error === true}
	<div class="box">
		<h2>{lang:frontend:error}</h2>
		<p>{$message}</p>
	</div>
	{endif}
	
	<h2>{lang:frontend:your_details}</h2>
	
	<table class="form">
		<tr>
			<td class="formleft" style="width: 25%;">
				{lang:frontend:your_name}
			</td>
			<td>
				<input type="text" name="sender_name" maxlength="100" style="width: 95%;" value="{$data[sender_name]}" />
			</td>
		</tr>
		<tr>
			<td class="formleft">
				{lang:frontend:your_email}
			</td>
			<td>
				<input type="text" name="sender_email" maxlength="100" style="width: 95%;" value="{$data[sender_email]}" />
			</td>
		</tr>
		<tr>
			<td>{lang:admin:save_settings_cookies}</td>
			<td>
				<input type="checkbox" name="save_settings" value="1" {if:!empty($data[save_settings])}
					checked="checked" {endif} />
			</td>
		</tr>
	</table>
	<h2>{lang:frontend:recipient_details}</h2>
	<table class="form">
		<tr>
			<td class="formleft" style="width: 25%;">
				{lang:frontend:recipient_name}
			</td>
			<td>
				<input type="text" name="rcpt_name" maxlength="100" style="width: 95%;" value="{$data[rcpt_name]}" />
			</td>
		</tr>
		<tr>
			<td class="formleft">
				{lang:frontend:recipient_address}
			</td>
			<td>
				<input type="text" name="rcpt_email" maxlength="100" style="width: 95%;" value="{$data[rcpt_email]}" />
			</td>
		</tr>
		<tr>
			<td class="formleft">
				{lang:frontend:your_message}
			</td>
			<td>
				<textarea name="message" rows="7" cols="40" style="width: 95%;">{$data[message]}</textarea>
			</td>
		</tr>
	</table>
	<p>
	{lang:frontend:recommend_friend_logged}
	</p>
	<p>
		<input type="submit" name="submit" value="{lang:frontend:recommend_file}" />
	</p>
</form>
{else}
<p>{$message}</p>
{endif}