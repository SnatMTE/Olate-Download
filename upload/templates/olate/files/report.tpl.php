<h1>{lang:frontend:report_problem}</h1>

{if: $result == 1}
<p>{lang:frontend:report_thanks}.</p>
{elseif: $result == 2}
<p>{lang:frontend:report_flood}.</p>
{else}
<p>{lang:frontend:report_desc}</p>
<!-- BEGIN: Report Form -->
<form action="report.php?file={$file_id}" method="post">
<h2>{lang:frontend:email}</h2>
<p><input name="email" type="text" id="email" size="35" /></p>
<h2>{lang:frontend:description}</h2>
<p><textarea name="description" cols="35" rows="5" id="description"></textarea></p>
<p><input name="report" type="submit" value="{lang:frontend:send_report}" /></p>
</form>
<!-- END: Report Form -->
{endif}