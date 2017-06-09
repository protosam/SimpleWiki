<!-- BEGIN: editor -->

<form method="post" action="/save/" id="updatepage">

	<small>The URL Slug for this page:</small>
	<input class="form-control" type="text" name="page_slug" value="{page}" />
	<small>Page Contents (uses Github-like markdown <a href="https://help.github.com/categories/writing-on-github/" target="_blank">More Info?</a>):</small>

	<input type="hidden" name="editor" value="" />

	<div id="editor" style="resize:vertical; min-height: 600px; width: 100%; border: 1px solid #cecece; border-radius: 4px;">{content}</div>

	<script>
		/* Use the kitchen sink for testing... https://ace.c9.io/build/kitchen-sink.html */
		var editor = ace.edit("editor");
		var modelist = ace.require("ace/ext/modelist")
		var mode = modelist.getModeForPath("new.markdown").mode

		editor.setTheme("ace/theme/github");
		editor.getSession().setMode(mode);
		editor.getSession().setUseWrapMode(true);

		var input = $('input[name="editor"]');
		editor.getSession().on("change", function () {
			input.val(editor.getSession().getValue());
		});
		editor.commands.addCommand({
			name: "toggle-fullscreen",
			bindKey: {win: "Escape", mac: "Escape"},
			exec: function(editor) {
				$('#editor').toggleClass('fullscreen');
			}
		});
		
		input.val(editor.getSession().getValue());
	</script>
</form>
<br>
<p class="float-right">
	<code>Toggle Fullscreen (esc)</code>
</p>
<button class="btn btn-success" form="updatepage">Save Page</button>
<a class="btn btn-danger" onclick="return confirm('Are you absolutely sure you want to delete {page}?')" href="/delete/{page}">Delete Page</a>
<!-- END: editor -->
