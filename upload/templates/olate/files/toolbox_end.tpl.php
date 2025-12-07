	</div>
	
	<div id="toolbox_right">

		<h2>{lang:frontend:comments_add}</h2>
		
		<form action="details.php?file={$file_id}&amp;cmd=addcomment" method="post">
		
		<div class="comments">
		<h3>{lang:frontend:your_name}</h3>
		<p><input type="text" size="30" name="name" value="{$data[name]}" /></p>
			
		<h3>{lang:frontend:email}</h3>
		<p><input type="text" size="30" name="email" value="{$data[email]}" /></p>
		
		<h3>{lang:frontend:comment}</h3>
		<p><textarea rows="6" cols="30" name="comment">{$data[comment]}</textarea></p>
		
		<p><input type="submit" name="submit" value="{lang:frontend:comment_add}" /></p>
		<p class="small" style="border:0">{lang:frontend:formatting}:<br /><strong>[b]{lang:frontend:bold}[/b]</strong>, <em>[i]{lang:frontend:italic}[/i]</em>, <ins>[u]{lang:frontend:underline}[/u]</ins>, [url]http://www.google.com[/url]</p>
		</div>
		
		</form>
		{if: $site_config[enable_ratings] == 1}
		<div class="comments">
		<form action="details.php?file={$file_id}&amp;cmd=rate" method="post">
			<h2>{lang:frontend:rate_file}</h2>
			<p class="small">{lang:frontend:lowest}
			<input type="radio" name="rating" value="1" checked="checked" />
			<input type="radio" name="rating" value="2" />
			<input type="radio" name="rating" value="3" />
			<input type="radio" name="rating" value="4" />
			<input type="radio" name="rating" value="5" /> {lang:frontend:highest}
			</p>
			<p style="border:0"><input type="submit" name="submit" value="{lang:frontend:rate}" /></p>
		</form>
		</div>
		{endif}
	</div>
</div>