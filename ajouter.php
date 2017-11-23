<?php

if(!empty($_POST)){
	include 'connexion.php';

	extract($_POST);

	$sql = "INSERT INTO membres SET 
			nom = 			'$nom',
			prenom = 		'$prenom',
			email =	 		'$email',
			adresse = 		'$adresse',
			cp = 			'$cp',
			ville = 		'$ville',
			tel = 			'$tel',
			naissance = 	'$naissance',
			inscription = 	NOW()";

	mysqli_query($link, $sql);
	header('location: liste.php');
}

?>

<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8" />


	<title>Ajouter un contact</title>
	<!-- <link rel="stylesheet" href="styles.css" type="text/css" /> -->

</head>

<body>
	<div id="container">
		<h1>Ajouter un contact</h1>
		<form action="ajouter.php" method="post" accept-charset="utf-8">
			<p><label for="nom">Nom : </label><input type="text" name="nom" value="" id="nom" /></p>
			<p><label for="prenom">Prenom : </label><input type="text" name="prenom" value="" id="prenom" /></p>
			<p><label for="email">eMail : </label><input type="text" name="email" value="" id="email" /></p>
			<p><label for="adresse">adresse : </label><input type="text" name="adresse" value="" id="adresse" /></p>
			<p><label for="cp">cp : </label><input type="text" name="cp" value="" id="cp" /></p>
			<p><label for="ville">ville : </label><input type="text" name="ville" value="" id="ville" /></p>
			<p><label for="tel">tel : </label><input type="text" name="tel" value="" id="tel" /></p>
			<p><label for="nele">Date de naissance : </label><input type="text" name="nele" value="" id="nele" /> AAAA-MM-JJ</p>
			<p class="submit"><input type="submit" name="submit" value=":: Ajouter ::" /></p>
		</form>
	</div>
</body>
</html>