<?php

	$pdo = get_pdo();

function spend_model($args = '*', $where = null,$limit = 10 ){

	global $pdo;

	$prepare = $pdo->prepare('SELECT '.$args.' FROM spends WHERE 1=1 LIMIT ?'); // ...
    
    $prepare->bindValue(1, $limit, PDO::PARAM_INT);
        
    $prepare->execute();

    return $prepare->fetchAll();

}

function getUser() {
    $pdo = get_pdo();

    $prepare = $pdo->prepare("SELECT id, name FROM users");
    $prepare->execute();

    return $prepare->fetchAll();
}

function getSpendByUserPart($limit, $offset = 0){

    $pdo = get_pdo();

    if ($offset == 0) {
        $sql = "
        SELECT us.user_id, GROUP_CONCAT(u.name) as names, s.price, s.pay_date, us.price as part 
        FROM users as u
        JOIN user_spend as us
        ON us.user_id = u.id
        JOIN spends as s 
        ON s.id = us.spend_id
        GROUP BY s.pay_date
        ORDER BY s.pay_date
        LIMIT $limit;
    ";
    } else {
        $sql = "
        SELECT us.user_id, GROUP_CONCAT(u.name) as names, s.price, s.pay_date, us.price as part 
        FROM users as u
        JOIN user_spend as us
        ON us.user_id = u.id
        JOIN spends as s 
        ON s.id = us.spend_id
        GROUP BY s.pay_date
        ORDER BY s.pay_date
        LIMIT $limit, $offset;
    ";
    } 

    $prepare = $pdo->prepare($sql);

    $prepare->execute();

    return $prepare->fetchAll();
}

function getAllSpendByUser(){

    $pdo = get_pdo();

    $sql = "
        SELECT name, user_id, SUM(us.price) as price
        FROM user_spend as us 
        INNER JOIN users as u
        ON u.id = us.user_id
        GROUP BY user_id
        LIMIT 10;
    ";

    $query = $pdo->query($sql);

    if( $query == false ) return null;

    return $query->fetchAll();

}

function getSpendByUser(int $id){
    $pdo = get_pdo();

    $sql = "
        SELECT name, user_id, SUM(us.price) as price 
        FROM user_spend as us 
        INNER JOIN users as u
        ON u.id = us.user_id
        WHERE us.user_id = ?
    ";

    $prepare = $pdo->prepare($sql);
    
    $prepare->bindValue(1, $id, PDO::PARAM_INT);

    if($query == false) return null;

    return $query->fetch();
}

 function getTotalSpend(){
 	global $pdo;

 	$prepare = $pdo->prepare('
 		SELECT SUM(s.price) as value_s_price FROM spends as s;
 		');
 	$prepare->execute();
 	$spended = $prepare->fetchAll();

 	//$sum = $spended['us.price'];

 	return $spended[0]['value_s_price'];
 }

function whereAnd(array $args){
	
    
    global $pdo;
    
    $w = ' WHERE 1 = 1 ';
    
    foreach($args as $name => $val ){
    
    	$w .= " AND $name = {$pdo->quote($val) }";
    
    }
    
    return $w;
    
    

}


function limit($offset = 0, $limit = 10){
	
	return " LIMIT $offset, $limit ";

}



function order(string $order){

	return " ORDER BY $order ";

}