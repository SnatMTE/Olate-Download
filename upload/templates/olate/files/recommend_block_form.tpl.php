<h1>{lang:frontend:recommend_block}</h1>
{if:empty($hide_form) || $hide_form === false}
<p>{lang:frontend:recommend_block_desc}<br /><br />
{lang:frontend:the_address_is} <strong>{$address}</strong></p>
<form action="recommend.php" method="post">
<p>
	<input type="hidden" name="action" value="block_address" />
	<input type="hidden" name="address" value="{$address}" />
	<input type="submit" name="confirm_block_yes" value="{lang:admin:yes}" /> 
	<input type="submit" name="confirm_block_no" value="{lang:admin:no}" />
</p>
</form>
{else}
<p>{$message}</p>
{endif}