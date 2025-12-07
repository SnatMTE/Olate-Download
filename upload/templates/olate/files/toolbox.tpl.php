<h1>{lang:frontend:toolbox}</h1>

<div id="toolbox">
	<div id="toolbox_left">

		{if: !empty($comment)}
		<h2>{lang:frontend:comments}</h2>
		
		<!-- BEGIN: Comments Display -->
		{block:comment}
		<div class="comments">
			<div class="wysiwyg">
				{$text}
			</div>
			<div class="filebox_breadcrumb">
				{lang:frontend:posted_by}: {$comment[name]} {lang:frontend:on} {$date} {lang:frontend:at} {$time}
			</div>
		</div>
		{/block:comment}
		<!-- END: Comments Display -->
		
		<!-- Display if no comments -->
		{else}
		<h2>{lang:frontend:comments}</h2>
		
		<p style="border-bottom:0">{lang:frontend:comments_none}</p>
		{endif}
		<!-- BEGIN: Pagination -->
		{$pagination}
		<!-- END: Pagination -->