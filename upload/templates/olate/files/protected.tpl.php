<h1>{lang:frontend:protected_file}</h1>

<p>{lang:frontend:protected_file_desc}:</p>
<!-- BEGIN: Password Form -->
<form action="details.php?file={$file_id}" method="post">
<h2>{lang:frontend:password}:</h2>
<p><input name="password" type="password" size="15" /></p>
<p><input name="continue" type="submit" value="{lang:frontend:continue}" /></p>
</form>
<!-- END: Password Form -->