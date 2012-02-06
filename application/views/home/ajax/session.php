<div id="session">
 	<h3>Basic Session management</h3>
	<p>A simple session library helps to centralize and unify session variables storage and retrieval.</p>
	<p>It implements a simple login / logout method.</p>
	<p><strong>TO-DO:</strong> Add security encryption to cookies storage.</p>
	<p>
		<strong>Example:</strong><br />
		<? if ($this->session->isLogged()): ?>
		You are logged in as <?=$this->session->get('id')?>. 
		<a href="<?=baseUrl()?>users/ajax?action=logout" class="session">Log out!</a>
		<? else : ?>
		You are not logged in.
		<a href="<?=baseUrl()?>users/ajax?action=login" class="session">Log in!</a>
		<? endif; ?>
	</p>
</div>