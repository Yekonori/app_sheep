<?php

function add_spend() {
	$pdo = get_pdo();

	$prepare = $pdo->prepare("INSERT INTO `spends` (`title`, `price`, `pay_date`, `status`) VALUES (?, ?, ?, 'paid');");

	$prepare->bindValue(1, $_POST["titre"]);
	$prepare->bindValue(2, $_POST["prix"]);
	$prepare->bindValue(3, $_POST["date"]);

	$prepare->execute();
}