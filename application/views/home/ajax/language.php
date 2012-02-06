<div id="language">
 	<h3>I18n & l10n system</h3>
	<p>
		MVCLight includes a localization library based on gettext principles. 
		Both gettext and a custom translations file system are available, and they allow support for plural forms.
		To use it, include this line on your controller, as it is done in <em>appController</em> in this demo.
		<em>$this->language->initGetText('en_GB');</em>
	</p>
	<p>
		Then, if gettext is available you can use your own MO files. Otherwise, you can take the file <em>application/lang/es_ES</em> as an example of use.
	</p>
	<p>
		The function __() is defined to overload the gettext function _() and it is used like this:
	</p>
	<pre>
    __('there are %s apples', 
         array( 'count'  => 9, 
                        'caps'   => ('first'|'words'|'upper'|'none'),
                        'vars'   => array('kitchen','window',...) ) 
          );
	</pre>
	<p>
		<? foreach ($this->registry->supported_langs as $s) :?>
		<a href="<?=baseUrl()?>language/ajax/?action=change&new_lang=<?=$s?>" class="language"><?=$s?></a>
		<? endforeach; ?>
	</p>
	<p><strong>Examples:</strong></p>
	<table>
		<tr>
			<th>Code</th>
			<td>__('there are %1$s apples in %2$s',array('count'=>0,'vars'=>__('the kitchen')))</td>	
		</tr>
		<tr>
			<th>Produces</th>
			<td><?=__('there are %1$s apples in %2$s',array('count'=>0,'vars'=>__('the kitchen')))?></td>
		</tr>
		<tr>
			<th>Code</th>
			<td>__('there are %1$s apples in %2$s',array('count'=>1,'vars'=>__('the window')))</td>
		</tr>
		<tr>
			<th>Produces</th>
			<td><?=__('there are %1$s apples in %2$s',array('count'=>1,'vars'=>__('the window')))?></td>
		</tr>
		<tr>
			<th>Code</th>
			<td>__('there are %1$s apples in %2$s',array('count'=>5,'vars'=>__('the living room')))</td>
		</tr>
		<tr>
			<th>Produces</th>
			<td><?=__('there are %1$s apples in %2$s',array('count'=>5,'vars'=>__('the living room')))?></td>
		</tr>
		<tr>
			<th>Code</th>
			<td>__('there are %1$s apples in %2$s',array('count'=>12,'vars'=>__('the corridor')))</td>
		</tr>
		<tr>
			<th>Produces</th>
			<td><?=__('there are %1$s apples in %2$s',array('count'=>12,'vars'=>__('the corridor')))?></td>
		</tr>
	</table>
</div>