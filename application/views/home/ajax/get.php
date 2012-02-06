<div id="get">
 	<h3>Allows GET parameters with mod_rewrite</h3>
	<p>Unlike other frameworks, the Apache url rewrite is done transparently so that the framework receives all parameters in the url, including GET variables.</p>
	<p>Then, instead of banish these parameters, an Input library is used to sanitize them.</p>
	<p><strong>TO-DO:</strong> Create a manual routing system that allows custom url's to be redirected to different controller/action pairs. Adding these lines in the .htaccess file is not recommended since it will break the transparent GET passage for those routes.</p>
	<p><strong>Example:</strong> param1 <em><?=$params[0]?></em> and param2 <em><?=$params[1]?></em></p>
</div>