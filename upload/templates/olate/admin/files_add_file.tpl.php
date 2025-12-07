<h1>{lang:admin:file_add}</h1>
{if: !isset($success)}
<p>{lang:admin:file_add_desc}</p>
    <form action="admin.php?cmd=files_add_file" method="post" id="add">
    	<h2>{lang:admin:file_details}</h2>
    	<table cellpadding="4" cellspacing="0" class="form">
    		<tr>
            	<td class="formleft">{lang:admin:file_name}<span style="color:#CC0000; ">*</span></td>
            	<td><input name="name" type="text" id="name" size="30" /></td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:short_description}<span style="color:#CC0000; ">*</span></td>
            	<td>
            		{if: $use_fckeditor}
            			{$desc_small_html}
            		{else}
            			<textarea name="description_small" cols="45" rows="3" id="description_small"></textarea>
            		{endif}
            	</td>
   			</tr>
    		<tr>
            	<td class="formleft">{lang:admin:large_description}<span style="color:#CC0000; ">*</span></td>
            	<td>
            		{if: $use_fckeditor}
            			{$desc_big_html}
            		{else}
            			<textarea name="description_big" cols="45" rows="5" id="description_big"></textarea>
            		{endif}
            	</td>
   			</tr>
   			<tr>
            	<td class="formleft">{lang:admin:convert_newlines}<span style="color:#CC0000; ">*</span></td>
            	<td>
            		<input type="radio" name="convert_newlines" value="1" /> {lang:admin:yes}<br />
            		<input type="radio" name="convert_newlines" value="0" checked="checked" /> {lang:admin:no}<br />
            		{lang:admin:convert_newlines_desc}
            	</td>
   			</tr>
   			<tr>
            	<td class="formleft">{lang:admin:keywords}</td>
            	<td><textarea name="keywords" cols="45" rows="3" id="keywords"></textarea><br />{lang:admin:separate_comma}</td>
   			</tr>
        	<tr>
        		<td class="formleft">{lang:admin:category}<span style="color:#CC0000; ">*</span></td>
        		<td><select name="category" id="category">
        			<option selected="selected">{lang:admin:select_category}</option>
					<option>{lang:admin:no_category}</option>
					{block:cats}
        			<option value="{$category[id]}">{$category[name]}</option>
					{/block:cats}
					</select></td>
        	</tr>
        	<tr>
        		<td class="formleft">{lang:admin:downloads_count}</td>
        		<td><input name="downloads" type="text" id="downloads" value="0" size="5" /></td>
        	</tr>
			<tr>
        		<td class="formleft">{lang:admin:views}</td>
        		<td><input name="views" type="text" id="views" value="0" size="5" /></td>
        	</tr>
        	<tr>
        		<td class="formleft">{lang:admin:file_size}<span style="color:#CC0000; ">*</span></td>
        		<td>
        			<input name="size" type="text" id="size" size="5" />
        			<select name="filesize_format" id="filesize_format">
	        			<option value="b">{lang:admin:file_size_format_b}&nbsp;&nbsp;</option>
	        			<option value="kb">{lang:admin:file_size_format_kb}&nbsp;&nbsp;</option>
	        			<option value="mb" selected="selected">{lang:admin:file_size_format_mb}&nbsp;&nbsp;</option>
	        			<option value="gb">{lang:admin:file_size_format_gb}&nbsp;&nbsp;</option>
       				</select>
       			</td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:agreement}<span style="color:#CC0000; ">*</span></td>
        		<td><select name="agreement" id="agreement">
        			<option value="0" selected="selected">{lang:admin:select_agreement}</option>
					<option value="0">{lang:admin:none}</option>
					{block:agreements}
					<option value="{$agreement[id]}">{$agreement[name]}</option>
					{/block:agreements}
                	
        		</select></td>
       		</tr>
        	<tr>
        		<td class="formleft">{lang:admin:password}:</td>
        		<td><input name="password" type="password" id="password" size="12" /> 
       			<span class="small">{lang:admin:leave_blank}</span></td>
       		</tr>
       		<tr>
       			<td>{lang:admin:activate_at}</td>
       			<td>
       				<select name="day">
       					<option value="--">{lang:admin:day}</option>
       					<option value="1">1</option>
       					<option value="2">2</option>
       					<option value="3">3</option>
       					<option value="4">4</option>
       					<option value="5">5</option>
       					<option value="6">6</option>
       					<option value="7">7</option>
       					<option value="8">8</option>
       					<option value="9">9</option>
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
       					<option value="--">{lang:admin:month}</option>
       					<option value="1">1</option>
       					<option value="2">2</option>
       					<option value="3">3</option>
       					<option value="4">4</option>
       					<option value="5">5</option>
       					<option value="6">6</option>
       					<option value="7">7</option>
       					<option value="8">8</option>
       					<option value="9">9</option>
       					<option value="10">10</option>
       					<option value="11">11</option>
       					<option value="12">12</option>
					</select> /
					<select name="year">
						<option value="--">{lang:admin:year}</option>
{block:year_select}
						<option value="{$year}">{$year}</option>
{/block:year_select}
					</select> 
					<select name="hour">
						<option value="--">{lang:admin:hour}</option>
       					<option value="0">0</option>
       					<option value="1">1</option>
       					<option value="2">2</option>
       					<option value="3">3</option>
       					<option value="4">4</option>
       					<option value="5">5</option>
       					<option value="6">6</option>
       					<option value="7">7</option>
       					<option value="8">8</option>
       					<option value="9">9</option>
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
       					<option value="--">{lang:admin:minute}</option>
       					<option value="0">0</option>
       					<option value="1">1</option>
       					<option value="2">2</option>
       					<option value="3">3</option>
       					<option value="4">4</option>
       					<option value="5">5</option>
       					<option value="6">6</option>
       					<option value="7">7</option>
       					<option value="8">8</option>
       					<option value="9">9</option>
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
					{lang:admin:leave_blank_ignore}<br />
					{$date_message}
       			</td>
       		</tr>
        </table>
		{if: isset($custom_field)}
		<h2>{lang:admin:custom_fields}</h2>
		<p>{lang:admin:leave_blank_ignore}</p>
		<table cellpadding="4" cellspacing="0" class="form">
    		{block:custom_fields}
			<tr>
				<td class="formleft">{$custom_field[label]}</td>
				<td>
					<input name="custom_field_{$custom_field[uid]}_value" type="text" id="custom_field_{$custom_field[uid]}_value" value="{$custom_field[value]}" size="35" />
					<input name="custom_field_{$custom_field[uid]}_field_id" type="hidden" id="custom_field_{$custom_field[uid]}_field_id" value="{$custom_field[id]}" />
				</td>
			</tr>
		{/block:custom_fields}
		</table>
		<div style="display: none">
			<input name="custom_field_total" type="hidden" id="custom_field_total" value="{$custom_field_total}" />
		</div>
        {endif}
		<h2>{lang:admin:upload_file}</h2>
		<p>{lang:admin:upload_file_desc_1}</p>
		<table cellpadding="4" cellspacing="0" class="form" style="text-align:left">
		<tr>
			<td class="formleft">{lang:admin:upload_file}</td>
			<td><input name="upload" type="checkbox" id="upload" value="1" /></td>
		</tr>
		</table>
		<h2>{lang:admin:download_locations}</h2>
		<table cellpadding="4" cellspacing="0" class="form" style="text-align:center">
		<tr>
			<td class="formleft">{lang:admin:name}<span style="color:#CC0000; ">*</span></td>
			<td class="formleft">{lang:admin:location}<span style="color:#CC0000; ">*</span></td>
			<td class="formleft">{lang:admin:file_url}<span style="color:#CC0000; ">*</span></td>
		</tr>
		{block:mirror}
		<tr>
			<td><input type="text" name="mirror{$mirror}_name" /></td>
			<td><input type="text" name="mirror{$mirror}_location" /></td>
			<td><input type="text" name="mirror{$mirror}_url" size="50" /></td>
		</tr>
		{/block:mirror}
		</table>
		<p>{lang:admin:mirror_rows}</p>
		<p><input type="submit" name="submit" value="{lang:admin:add}" /><input name="submit" type="hidden" id="submit" value="1" /></p>
</form>
{else}
<p><a href="details.php?file={$id}">{lang:admin:file_added}</a></p>
{endif}