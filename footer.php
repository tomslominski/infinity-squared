</div>
<div class="paragraph desktop-only">
	<h2><?php yourls_e( 'The bookmarklets', 'isq_translation') ?></h2>
	<p><?php yourls_e( 'To use the bookmarklets drag them to your bookmark bar or simply right click on them and select the appropriate option.', 'isq_translation') ?></p>

	<p><?php yourls_e( 'This bookmarklet takes the page URL and title and opens a new tab, where you can fill out a CAPTCHA. If you have selected text before using the bookmarklet, that will be used as the keyword.', 'isq_translation') ?></p>
	<a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo YOURLS_SITE . '/index.php'; ?>',l=d.location,p='?url='+enc(l.href)+'&title='+enc(d.title)+'&keyword='+s2,u=f+p;try%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0);%7D)();" class="bookmarklet"><?php yourls_e( 'Shorten', 'isq_translation') ?></a>
</div>

<div class="footer">
	<p><?php yourls_e( 'Powered by <a href="http://yourls.org/">YOURLS</a>. Design by <a href="http://tomslominski.net/">Tom Slominski</a>. Also used: <a href="http://formalize.me/">Formalize</a> and <a href="http://qtip2.com/">qTip²</a>. Find this theme on <a href="https://github.com/tomslominski/infinity-squared">GitHub</a>.', 'isq_translation') ?></p>
</div>
</div>
</body>
</html>
