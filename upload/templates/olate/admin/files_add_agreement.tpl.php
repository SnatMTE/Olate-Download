<h1>{lang:admin:agreement_add}</h1>
{if: !isset($success)}
<p>{lang:admin:agreement_add_desc}</p>
    <form action="admin.php?cmd=files_add_agreement" method="post">
    	<h2>{lang:admin:name}</h2>
        <p><input name="name" type="text" id="name" size="30" /></p>
		<h2>{lang:admin:contents}:</h2>
        <p>
        {if: $use_fckeditor}
        	{$contents_html}
        {else}
        	<textarea name="contents" cols="55" rows="8" id="contents"></textarea></p>
        {endif}
		<p><input type="submit" name="Submit" value="{lang:admin:add}" />
            <input name="submit" type="hidden" id="submit" value="1" /></p>
    </form>
{else}
<p>{lang:admin:agreement_added}</p>
{endif}