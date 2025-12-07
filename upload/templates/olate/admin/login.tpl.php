<form action="admin.php?cmd=login" method="post">

<h1>{lang:admin:please_login}</h1>

{if:isset($error)}
<p>{$error}</p>
{endif}

<h2>{lang:admin:username}</h2>
<p><input type="text" size="30" name="username" /></p>

<h2>{lang:admin:password}</h2>
<p><input type="password" size="30" name="password" /></p>

<h2>{lang:admin:remember_me}</h2>
<p><input type="checkbox" id="remember" name="remember" /> <label for="remember">{lang:admin:remember_me_desc}</label></p>

<p><input type="submit" name="submit" value="{lang:admin:login}" /></p>

</form>