<h1>{lang:admin:file_edit}</h1>
{if: !isset($success) || $success === false}
<p>{lang:admin:file_edit_desc}</p>
<form action="admin.php?cmd=files_edit_file&amp;action=file_edit" method="post" id="edit">
<h2>{lang:admin:file_details}</h2>
{if: is_bool($success) && $success === false && empty($error)}
<div class="box">
<h2>{lang:admin:errors_with_submission}</h2>
<p style="border-bottom: 0px;">{lang:admin:errors_highlighted_red}</p>
</div>
{elseif: !empty($error)}
<div class="box">
<h2>{lang:admin:errors_with_submission}</h2>
<p style="border-bottom: 0px;">{$error}</p>
</div>
{endif}
   <table cellpadding="4" cellspacing="0" class="form">
    	<tr>
            <td class="formleft">{lang:admin:file_name}</td>
            <td><input name="name" type="text" id="name" value="{$file[name]}" size="30" /></td>
   		</tr>
    	<tr>
            <td class="formleft">{lang:admin:short_description}</td>
            <td>
            	{if: $use_fckeditor}
            		{$desc_small_html}
            	{else}
            		<textarea name="description_small" cols="45" rows="3" id="description_small">{$file[description_small]}</textarea>
            	{endif}
            </td>
   		</tr>
    	<tr>
            <td class="formleft">{lang:admin:large_description}</td>
            <td>
            	{if: $use_fckeditor}
            		{$desc_big_html}
            	{else}
            		<textarea name="description_big" cols="45" rows="5" id="description_big">{$file[description_big]}</textarea>
            	{endif}
            </td>
   		</tr>
   		<tr>
        	<td class="formleft">{lang:admin:convert_newlines}<span style="color:#CC0000; ">*</span></td>
        	<td>
        		<input type="radio" name="convert_newlines" value="1" {if:$file[convert_newlines] == 1}
checked="checked" {endif}/> {lang:admin:yes}<br />
        		<input type="radio" name="convert_newlines" value="0" {if:empty($file[convert_newlines])}
checked="checked" {endif}/> {lang:admin:no}<br />
        		{lang:admin:convert_newlines_desc}
        	</td>
   		</tr>
			<tr>
        	<td class="formleft">{lang:admin:keywords}</td>
        	<td><textarea name="keywords" cols="45" rows="3" id="keywords">{$file[keywords]}</textarea><br />{lang:admin:separate_comma}</td>
			</tr>
        <tr>
        	<td class="formleft">{lang:admin:category}</td>
        	<td><select name="category" id="category">
        		<option value="{$file[category_id]}" selected="selected">{$current_category_name}</option>
        		<option>{lang:admin:select_category}</option>
				<option>{lang:admin:no_category}</option>
				{block:cats}
        		<option value="{$category[id]}">{$category[name]}</option>
				{/block:cats}
				</select></td>
        </tr>
        <tr>
        	<td class="formleft">{lang:admin:downloads_count}</td>
        	<td><input name="downloads" type="text" id="downloads" value="{$file[downloads]}" size="5" /></td>
        </tr>
		<tr>
        	<td class="formleft">{lang:admin:views}</td>
        	<td><input name="views" type="text" id="views" value="{$file[views]}" size="5" /></td>
        </tr>
        <tr>
        	<td class="formleft">{lang:admin:file_size}</td>
        	<td>
        		<input name="size" type="text" id="size" value="{$file[size]}" size="5" />
       			<select name="filesize_format" id="filesize_format">
	        		<option value="{$filesize_format_lc}" selected="selected">{$filesize_format}</option>
	        		<option value="{$filesize_format_lc}">- - - - - -</option>
	        		<option value="b">{lang:admin:file_size_format_b}</option>
	        		<option value="kb">{lang:admin:file_size_format_kb}</option>
	        		<option value="mb">{lang:admin:file_size_format_mb}</option>
	        		<option value="gb">{lang:admin:file_size_format_gb}</option>
       			</select>
        	</td>
       	</tr>
        <tr>
        	<td class="formleft">{lang:admin:votes}</td>
        	<td><input name="rating_votes" type="text" id="rating_votes" value="{$file[rating_votes]}" size="5" /></td>
	 </tr>
        <tr>
        	<td class="formleft">{lang:admin:rating}</td>
        	<td><input name="rating_value" type="text" id="rating_value" value="{$file[rating_value]}" size="5" /></td>
       	</tr>
        <tr>
        	<td class="formleft">{lang:admin:agreement}</td>
        	<td><select name="agreement" id="agreement">
        		<option value="{$file[agreement_id]}" selected="selected">{$current_agreement_name}</option>
        		<option value="0">{lang:admin:select_agreement}</option>        			
				<option value="0">{lang:admin:none}</option>
				{block:agreements}
				<option value="{$agreement[id]}">{$agreement[name]}</option>
				{/block:agreements}
        	</select></td>
       	</tr>
		<tr>
			<td class="formleft">{lang:admin:change_password}</td>
			<td>
				<input type="radio" name="change_pass" value="ignore" checked="checked" /> {lang:admin:no}<br />
				<input type="radio" name="change_pass" value="erase" /> {lang:admin:remove_password}<br />
				<input type="radio" name="change_pass" value="change" /> {lang:admin:yes}
			</td>
		</tr>
		<tr>
			<td class="formleft">{lang:admin:new_password}</td>
			<td><input type="password" name="password" id="password" size="30" maxlength="30"/></td>
		</tr>
		<tr>
			<td class="formleft">{lang:admin:confirm_password}</td>
			<td><input type="password" name="password_confirm" id="password" size="30" maxlength="30"/></td>
		</tr>
       	<tr>
        	<td class="formleft">{lang:admin:file_date}</td>
        	<td><input name="date" type="text" size="12" value="{$file[date]}" /> 
       		<span class="small">{lang:admin:file_date_format}</span></td>
       	</tr>
       	<tr>
       	  <td class="formleft">Status</td>
       	  <td><select name="status" id="status">
		  	{if: $file[status] == 1}
       	    <option value="1" selected="selected">{lang:admin:active}</option>
			{else}
			<option value="0" selected="selected">{lang:admin:disabled}</option>
			{endif}
			<option value="">{lang:admin:select_status}</option>
       	    <option value="1">{lang:admin:active}</option>
       	    <option value="0">{lang:admin:disabled}</option>
   	      </select></td>
     </tr>
     <tr>
		<td>{lang:admin:activate_at}</td>
		<td>
			<select name="day">
{if: $file[activate_at] > 0 && $file[activate_at_day] < 10}
				<option value="{$file[activate_at_day]}">0{$file[activate_at_day]}</option>
{elseif: $file[activate_at] > 0 && $file[activate_at_day] >= 10}
				<option value="{$file[activate_at_day]}">{$file[activate_at_day]}</option>
{endif}
				<option value="--">--</option>
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="5">05</option>
				<option value="6">06</option>
				<option value="7">07</option>
				<option value="8">08</option>
				<option value="9">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
		</select> /
		<select name="month">
{if: $file[activate_at] > 0 && $file[activate_at_month] < 10}
				<option value="{$file[activate_at_month]}">0{$file[activate_at_month]}</option>
{elseif: $file[activate_at] > 0 && $file[activate_at_month] >= 10}
				<option value="{$file[activate_at_month]}">{$file[activate_at_month]}</option>
{endif}
				<option value="--">--</option>
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="5">05</option>
				<option value="6">06</option>
				<option value="7">07</option>
				<option value="8">08</option>
				<option value="9">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
		</select> /
		<select name="year">
{if: $file[activate_at] > 0}
				<option value="{$file[activate_at_year]}">{$file[activate_at_year]}</option>
{endif}
			<option value="--">--</option>
{block:year_select}
			<option value="{$year}">{$year}</option>
{/block:year_select}
		</select> 
		<select name="hour">
{if: $file[activate_at] > 0 && $file[activate_at_hour] < 10}
				<option value="{$file[activate_at_hour]}">0{$file[activate_at_hour]}</option>
{elseif: $file[activate_at] > 0 && $file[activate_at_hour] >= 10}
				<option value="{$file[activate_at_hour]}">{$file[activate_at_hour]}</option>
{endif}
			<option value="--">--</option>
				<option value="0">00</option>
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="5">05</option>
				<option value="6">06</option>
				<option value="7">07</option>
				<option value="8">08</option>
				<option value="9">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
			</select> :
			<select name="minute">
{if: $file[activate_at] > 0 && $file[activate_at_minute] < 10}
				<option value="{$file[activate_at_minute]}">0{$file[activate_at_minute]}</option>
{elseif: $file[activate_at] > 0 && $file[activate_at_minute] >= 10}
				<option value="{$file[activate_at_minute]}">{$file[activate_at_minute]}</option>
{endif}
				<option value="--">--</option>
				<option value="0">00</option>
				<option value="1">01</option>
				<option value="2">02</option>
				<option value="3">03</option>
				<option value="4">04</option>
				<option value="5">05</option>
				<option value="6">06</option>
				<option value="7">07</option>
				<option value="8">08</option>
				<option value="9">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="36">36</option>
				<option value="37">37</option>
				<option value="38">38</option>
				<option value="39">39</option>
				<option value="40">40</option>
				<option value="41">41</option>
				<option value="42">42</option>
				<option value="43">43</option>
				<option value="44">44</option>
				<option value="45">45</option>
				<option value="46">46</option>
				<option value="47">47</option>
				<option value="48">48</option>
				<option value="49">49</option>
				<option value="50">50</option>
				<option value="51">51</option>
				<option value="52">52</option>
				<option value="53">53</option>
				<option value="54">54</option>
				<option value="55">55</option>
				<option value="56">56</option>
				<option value="57">57</option>
				<option value="58">58</option>
				<option value="59">59</option>
		</select>
		<em>dd/mm/yyyy hh:mm</em> ({lang:admin:leave_blank_ignore})<br />
		{$date_message}
		</td>
	</tr>
{if: isset($custom_field)}
{block:custom_fields}
	<tr>
		<td class="formleft">{$custom_field[label]}</td>
		<td>
			<input name="custom_field_{$custom_field[uid]}_value" type="text" id="custom_field_{$custom_field[uid]}_value" value="{$custom_field[value]}" size="35" />
			<input name="custom_field_{$custom_field[uid]}_field_id" type="hidden" id="custom_field_{$custom_field[uid]}_field_id" value="{$custom_field[field_id]}" />
			<input name="custom_field_{$custom_field[uid]}_id" type="hidden" id="custom_field_{$custom_field[uid]}_id" value="{$custom_field[id]}" /> {lang:admin:leave_blank_delete}
		</td>
	</tr>
{/block:custom_fields}
{endif}
</table>
<h2>{lang:admin:download_locations}</h2>
	<table cellpadding="4" cellspacing="0" class="form" style="text-align:center">
	<tr>
		<td class="formleft">{lang:admin:name}</td>
		<td class="formleft">{lang:admin:location}</td>
		<td class="formleft">{lang:admin:file_url}</td>
	</tr>
	{block:mirror_edit}
	<tr>
		<td><input name="mirror_existing[{$mirror[id]}][name]" type="text" value="{$mirror[name]}" /></td>
		<td><input name="mirror_existing[{$mirror[id]}][location]" type="text" value="{$mirror[location]}" /></td>
		<td><input name="mirror_existing[{$mirror[id]}][url]" type="text" value="{$mirror[url]}" size="50" /></td>
		<td>
			<input type="checkbox" name="mirror_existing[{$mirror[id]}][delete]" id="mirror_existing_{$mirror[id]}_delete" value="1" />
			<label for="mirror_existing_{$mirror[id]}_delete">Delete?</label>
		</td>
	</tr>
	{/block:mirror_edit}
	{block:mirror_bad}
	<tr style="background-color: #FFCECE;">
		<td><input name="{$mirror_prefix}[name]" type="text" value="{$mirror[name]}" /></td>
		<td><input name="{$mirror_prefix}[location]" type="text" value="{$mirror[location]}" /></td>
		{if: !empty($mirror_existing) && $mirror_existing === true}
		<td><input name="{$mirror_prefix}[url]" type="text" value="{$mirror[url]}" size="50" /></td>
		<td>
			<input type="checkbox" name="{$mirror_prefix}[delete]" id="{$mirror_id_prefix}_delete" value="1" />
			<label for="{$mirror_id_prefix}_delete">Delete?</label>
		</td>
		{else}
		<td colspan="2" style="text-align: left"><input name="{$mirror_prefix}[url]" type="text" value="{$mirror[url]}" size="50" /></td>
		{endif}
	</tr>
	{/block:mirror_bad}
	{block:mirror_add}
		<tr>
			<td><input type="text" name="mirror_new[{$mirror}][name]" /></td>
			<td><input type="text" name="mirror_new[{$mirror}][location]" /></td>
			<td colspan="2" style="text-align: left;"><input type="text" name="mirror_new[{$mirror}][url]" size="50" /></td>
		</tr>
	{/block:mirror_add}
		<tr>
			<td colspan="4" style="text-align: left;">{lang:admin:mirror_rows}</td>
		</tr>
	</table>
	{if: $success === false}
	<div class="box">
	<h2>{lang:admin:errors_with_submission}</h2>
	<p style="border-bottom: 0px;">{lang:admin:errors_have_you_corrected}</p>
	</div>
	{endif}
	<p>
	<input type="hidden" name="mirror_edit_type" value="1" />
	<input name="file_id" type="hidden" id="file_id" value="{$file[id]}" />
	{if: isset($custom_field_total) }
	<input name="custom_field_total" type="hidden" id="custom_field_total" value="{$custom_field_total}" />
	{endif}
	<input type="submit" name="submit" value="{lang:admin:edit}" />
	</p>
</form>
<h2>{lang:admin:comments}</h2>
	{if: !$comment_empty}
	<p>{lang:admin:approved_comments}</p>
		{block:comment}
		<div class="box">
			<div class="wysiwyg">{$text}</div>
			<div class="filebox_links">
				{lang:admin:posted_by}: {$comment[name]} 
				{lang:admin:on} {$date} 
				{lang:admin:at} {$time} 
				(<a href="admin.php?cmd=files_edit_comment&amp;id={$comment[id]}&redir=files_edit_file&file_id={$file[id]}">{lang:admin:edit}</a> | 
				<a href="admin.php?cmd=files_delete_comment&amp;id={$comment[id]}&redir=files_edit_file&file_id={$file[id]}">{lang:admin:delete}</a>)
			</div>
		</div>
		{/block:comment}
	{else}
	<p>{lang:admin:comments_none}</p>
	{endif}
{else}
<p><a href="details.php?file={$id}">{lang:admin:comments_updates_made}</a></p>
{endif}