<div id="database">
	<h3>Single Database connection, read and write methods</h3>
	<p>A simple interface allows to query a database to execute SQL directly without bothering about the connection.</p>
	<p>PDO and MySQL are supported.</p>
	<p>Use $config['db_auto_load'] to automatically load the DB connection, or instance it explicitely in the model.</p>
	<p>
		<strong>Example:</strong> $this->db->read('SELECT * FROM ' . $this->table);<br />
		<strong>Produces:</strong>
	</p>
	<table>
 	<? foreach ($users as $u) :?>
		<tr>
			<td>#<?=$u['userID']?></td>
			<td><?=$u['userName']?></td>
			<td><?=$u['userEmail']?></td>
		</tr>
	<? endforeach;?>
	</table>
</div>