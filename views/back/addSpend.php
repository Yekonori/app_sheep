<?php ob_start() ?>

<?php include __DIR__ . '/../partials/nav.php'; ?>
<form action="/addSpend/form" method="POST">
	<input type="text" name="titre" placeholder="Titre"/>
	<input type="number" name="prix" placeholder="Prix" min="0"/>
	<input type="datetime-local" name="date"/>
	<?php foreach($data_user as $data) : ?>
		<tr>	
			<td>
				<br/>
				<input type="checkbox" name="choix[]"/>
				<p><?php echo htmlentities($data["name"]); ?></p>
			</td>
			<td>
				<p>Somme payé : </p>
				<input type="number" name="<?php echo intval($data["id"]); ?>" min="0"/>
			</td>
		</tr>
	<?php endforeach; ?>
	<input type="submit"/>
</form>

<?php $content = ob_get_clean() ?>
<?php include __DIR__.'/../layouts/master.php' ?>