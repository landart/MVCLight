<div id="profiler">
 	<h3>A simple Profiler</h3>
	<p>
		As you can see in this demo, a profiler has been included with the framework.
		When it is activated from the config file, it is used by several libraries to log their progress.
		Finally, a report is constructed after the application finishes. 
	</p>
	<p>Please note that AJAX actions can have the profiler activated separately (no profiling information is added to these AJAX calls)</p>
	<p>
		Finally, have in mind that the <em>Profiler</em>, the <em>_debug()</em> function and PHP errors are configured to do not output anything when on production mode.
		Production mode is determined in <em>fw.php</em> according to the host name (currently, all hosts but <em>localhost</em> are production servers except).
	</p>
</div>