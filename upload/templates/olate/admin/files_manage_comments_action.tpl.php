<h1>{lang:admin:comments_manage}</h1>
{if: $action == 1}
<p>{lang:admin:comments_deleted_p}</p>
{elseif: $action == 2}
<p>{lang:admin:comments_unapproved_p}</p>
{elseif: $action == 3}
<p>{lang:admin:comments_approved_p}</p>
{elseif: $action == 0}
<p>{lang:admin:must_select}</p>
{endif}