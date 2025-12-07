{if: $site_config[enable_ratings] == 1}
<div class="box">
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