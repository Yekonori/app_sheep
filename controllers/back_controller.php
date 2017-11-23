<?php
function dashboard(){

	$pdo = get_pdo();

	$lastDepenses = getSpendByUserPart(3, 0);

	$allUserSpended = getTotalSpend();

	$userSpended = getAllSpendByUser();

	$colors = ["purple", "orange", "red", "yellow", "green", "pink", "#65A4C5", "#EA69A9", "#1378A2", "#820333"];
	$i = 0;

	$diff = 25;

    include __DIR__ . '/../views/back/dashboard.php';
   
}

function history() {
	$pdo = get_pdo();

	if (isset($_GET['page'])) {
		if(intval($_GET['page']) != 0) {
			$page = ($_GET['page'] - 1) * 5;
		}
	} else {
		$page = 0;
	}
	

	$depenses = getSpendByUserPart($page, 5);

    include __DIR__ . '/../views/back/history.php';
}

function addDepense() {
	include __DIR__.'/../views/back/addSpend.php';
}

function logout(){
	// Détruit toutes les variables de session
	$_SESSION = array();

	// Si vous voulez détruire complètement la session, effacez également
	// le cookie de session.
	// Note : cela détruira la session et pas seulement les données de session !
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}

	// Finalement, on détruit la session.
	session_destroy();

	header("location: /");
	exit;
}

function spentUser() {
	$pdo = get_pdo();
	$datas = getSpendByUserPart(1);
	$data_user = getUser();
	// echo dump($_POST);
	include __DIR__.'/../views/back/addSpend.php';
}