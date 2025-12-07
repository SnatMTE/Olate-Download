<h1>{$title}</h1>
<p>{$desc}</p>
<ul class="confirm_item_list">
{block:items}
<li>{$text}</li>
{/block:items}
</ul>
<form action="{$action}" method="post">
<p>
{block:hidden_fields}
	<input type="hidden" name="{$field_name}" value="{$value}" />
{/block:hidden_fields}
	<input type="submit" name="confirm_yes" value="{lang:admin:yes}" /> 
	<input type="submit" name="confirm_no" value="{lang:admin:no}" />
</p>
</form>