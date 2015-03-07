
<div class="content">
	<h2><?php yourls_e( 'The bookmarklet', 'isq_translation') ?></h2>

	<?php $bookmarkletdialog = yourls__( 'Save this as a bookmark.', 'isq_translation') // Can't put it where it belongs as there'd be too much char escaping ?>
	<p><?php yourls_e( 'Add this to your bookmarks or drag it to your bookmarks bar to quickly access shortening functions.', 'isq_translation') ?> </p>
	<p class="bookmarklet-container"><a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo YOURLS_SITE . '/index.php'; ?>',l=d.location,p='?url='+enc(l.href)+'&title='+enc(d.title)+'&keyword='+s2,u=f+p;try%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0);%7D)();" onClick="alert('<?php echo $bookmarkletdialog; ?>'); return false;" class="bookmarklet button"><span class="icon-move"><?php include('public/images/move.svg'); ?></span><?php yourls_e( 'Shorten', 'isq_translation') ?></a></p>
	<p><?php yourls_e( 'This bookmarklet takes the page URL and title and opens a new tab, where you can fill out a CAPTCHA. If you have selected text before using the bookmarklet, that will be used as the keyword.', 'isq_translation') ?></p>
	<p><?php yourls_e( 'Support for bookmarklets on mobile varies. For example, they work on Chrome for Android but you have to add and sync them from your desktop.', 'isq_translation') ?></p>
</div>

<div class="content footer">
	<p><?php yourls_e( 'Powered by <a href="http://yourls.org/">YOURLS</a>. Designed by <a href="http://tomslominski.net/">Tom Slominski</a>.', 'isq_translation') ?> <a class="icon-github" href="https://github.com/tomslominski/infinity-squared"><?php include('public/images/github.svg'); ?></p>
</div>
</div>
</div>

<?php if (!empty(ISQ::$general['clipboard'])) { ?>
	<script type="text/javascript" src="public/ZeroClipboard/ZeroClipboard.min.js"></script>
	<script>
		ZeroClipboard.config({
			swfPath: "public/ZeroClipboard/ZeroClipboard.swf"
		});

		var clipboardClient = new ZeroClipboard( document.getElementsByClassName("copy-button") );

		clipboardClient.on( 'aftercopy', function(event) {
        	event.target.innerHTML = "Copied!";
        } );
	</script>
<?php } ?>

</body>
</html>
