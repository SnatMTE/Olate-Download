function collapse (categoryid)
{
	if (document.getElementById){
		children = document.getElementById( 'children' + categoryid ); 
		button = document.getElementById( 'button' + categoryid ); 
		
		if (children.style.display != 'none') {
			children.style.display='none';
			button.innerHTML='+';
		}
		else
		{
			children.style.display='block';
			button.innerHTML='-';
		}
	}
	
	return false;
}

function collapse_admin (section)
{
	if (document.getElementById('acp_' + section).style.display=='none') {
		document.getElementById('acp_' + section).style.display='block';	
		document.getElementById('button_acp_' + section).innerHTML='-';
	}
	else
	{
		document.getElementById('acp_' + section).style.display='none';
		document.getElementById('button_acp_' + section).innerHTML='+';
	}

	return false;
}

function collapse_custom (categoryid, button_show_text, button_hide_text)
{
	if (document.getElementById('children' + categoryid).style.display=='none') {
		document.getElementById('children' + categoryid).style.display='block'	
		document.getElementById('button' + categoryid).innerHTML=button_hide_text;
	}
	else
	{
		document.getElementById('children' + categoryid).style.display='none'
		document.getElementById('button' + categoryid).innerHTML=button_show_text;
	}
}

function toggle_checks (form)
{
	var elements = form.elements;
	var element_count = elements.length;
	
	for (var i = 0; i < element_count; i++)
	{
		if (elements[i].type == "checkbox")
		{
			if (elements[i].checked == true)
			{
				elements[i].checked = false;
			}
			else
			{
				elements[i].checked = true;
			}
		}
	}
}

function check(checkbox_id)
{
	var checkbox = document.getElementById(checkbox_id);
	
	if (checkbox.checked)
	{
		checkbox.checked = false;
	}
	else
	{
		checkbox.checked = true;
	}
}