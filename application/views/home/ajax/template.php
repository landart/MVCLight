<div id="template">
 	<h3>Easy Templating system</h3>
	<p>The templating system is being used to render this pages, and it is really easy:</p>
	<ul>
		<li><em>$this->template->show('view',$params)</em> From a model, controller or view to render a piece of view.</li>
		<li><em>$this->template->render('view',$params)</em> To render a view and store the result in a variable.</li>
		<li><em>$this->template->page('view',$params)</em> From a controller to render a piece of view within the selected template.</li>
	</ul>
</div>