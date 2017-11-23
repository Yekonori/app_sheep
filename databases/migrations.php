<?php
// Constantes and dependancies
$app = require_once __DIR__ . '/../config/app.php';

require_once __DIR__.'/../vendor/fzaninotto/faker/src/autoload.php';
$faker = Faker\Factory::create();

// codes utils

function aleaUserIds($nbUser, $totalUser){
    $ids = [];
    while( count($ids) < $nbUser)
    {
        $choiceId = rand(1,$totalUser);
        while( in_array($choiceId, $ids) == true ) $choiceId = rand(1, $totalUser);
        
        $ids[] = $choiceId;
    }

    return $ids;
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DBNAME', 'sheep');

// bootstrap PDO
  $defaults = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // PDO remonte les erreurs SQL, sinon il retourne une bête erreur PHP
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // retournera les données dans un tableau associatifs
    ];

    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME, 
        DB_USER, DB_PASSWORD, 
        $defaults
    );

$users ="CREATE TABLE `users`(
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(100) NOT NULL,
      `email` VARCHAR(100) NOT NULL,
      `password` VARCHAR(100) NOT NULL,
      `avatar` VARCHAR(100) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `users_email_unique` (`email`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$spends ="CREATE TABLE `spends`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `price`DECIMAL(7,2) NULL DEFAULT NULL,
    `pay_date` DATETIME NULL DEFAULT NULL,
    `status` ENUM('in progress', 'canceled', 'paid') NOT NULL DEFAULT 'in progress',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$user_spend ="CREATE TABLE `user_spend`(
      `user_id` INT UNSIGNED NOT NULL,
      `spend_id` INT UNSIGNED NOT NULL,
      `price` DECIMAL(7,2) NULL DEFAULT NULL,
      
  KEY `user_spend_user_id_foreign` (`user_id`),
  KEY `user_spend_spend_id_foreign` (`spend_id`),
  CONSTRAINT `user_spend_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_spend_spend_id_foreign` FOREIGN KEY (`spend_id`) REFERENCES `spends` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$parts ="CREATE TABLE `parts`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NULL DEFAULT NULL,
    `day` SMALLINT NOT NULL,
    `started` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `parts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$balances ="CREATE TABLE `balances`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `pricePart` DECIMAL(7,2) NOT NULL,
    `priceStay`  DECIMAL(7,2) NOT NULL,
    `priceDebit`  DECIMAL(7,2) NOT NULL,
    `priceCredit`  DECIMAL(7,2) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `parts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

// create table

$pdo->exec("DROP TABLE IF EXISTS balances");
$pdo->exec("DROP TABLE IF EXISTS parts");
$pdo->exec("DROP TABLE IF EXISTS user_spend");
$pdo->exec("DROP TABLE IF EXISTS users");
$pdo->exec("DROP TABLE IF EXISTS spends");

$pdo->exec($users);
$pdo->exec($spends);
$pdo->exec($user_spend);
$pdo->exec($parts);
$pdo->exec($balances);

// users seeders
$prepare = $pdo->prepare('INSERT INTO `users` (`name`, `email`, `password`) VALUES (?, ?, ?) ');

for ($i=0; $i < 10; $i++) {
    $prepare->bindValue(1, $faker->name, PDO::PARAM_STR);
    $prepare->bindValue(2, $faker->unique()->email, PDO::PARAM_STR);
    $prepare->bindValue(3, 'admin', PDO::PARAM_STR);

    $prepare->execute();
}

$prepare = null;
// spends 
$prepareSpend = $pdo->prepare('INSERT INTO `spends` (`title`, `description`, `price`, `status`, `pay_date`) VALUES (?, ?, ?, ?, ?) ');

for ($i=0; $i < 30; $i++) {

    // spends
    $prepareSpend->bindValue(1, $faker->randomElement(['shopping', 'transport', 'location', 'energy', 'billet', 'visit', 'various']), PDO::PARAM_STR);
    $prepareSpend->bindValue(2, $faker->text, PDO::PARAM_STR);
    $nbDec = rand(1,5) == 5 ? 4 : 2;
    $prepareSpend->bindValue(3, $faker->randomFloat($nbDec), PDO::PARAM_STR);
    $status = rand(0,1)?  'in progress' : 'paid' ;
    $prepareSpend->bindValue(4, $status, PDO::PARAM_STR);

    $t = 60*24*3600;
    $d = rand(0,$t);
    $prepareSpend->bindValue(5, date('Y-m-d h:i:s', time() - $d ));

    $prepareSpend->execute();

}

// mise à jour des prix par personne dans la table user_spend
// sélectionner toutes les dépences 

 // user relationship
 $prepareUser_spend = $pdo->prepare('INSERT INTO `user_spend` (`user_id`, `spend_id`, `price`) VALUES (?, ?, ?) '); 
 
 // récupérer toutes les dépendances 
 $queryDepend = $pdo->query('SELECT id, price FROM spends');

 $depends = $queryDepend->fetchAll(); // tableau de tableau associatif
 
 $queryCountUser = $pdo->query('SELECT COUNT(id) as total FROM users');
 
 $totalUser = $queryCountUser->fetch()['total']; // renvoie avec PDO une ligne 
 
 
 foreach($depends as $depend){
 
     if($depend['price'] > 1000){
        
        $nbUser = rand(2, $totalUser);
        $priceUser = round($depend['price'] / $nbUser,2);
        
        $ids = aleaUserIds($nbUser, $totalUser); // fonction permettant de récupérer les ids aléatoirement
        
        for($i = 0; $i < $nbUser; $i++)
        {
            $prepareUser_spend->bindValue(1,$ids[$i]);
            $prepareUser_spend->bindValue(2,$depend['id']);
            $prepareUser_spend->bindValue(3, $priceUser);
           
            $prepareUser_spend->execute(); // pour insérer effectivement
        }
    
    }else{
    
        $prepareUser_spend->bindValue(1, rand(1, $totalUser));
        $prepareUser_spend->bindValue(2,$depend['id']);
        $prepareUser_spend->bindValue(3, $depend['price']);
      
        $prepareUser_spend->execute(); // pour insérer effectivement
    }
 }
 
 $prepareUser_spend = null;
 $queryDepend = null;

 // vérifier que toutes les dépenses correspondent bien

$totalSpends = $pdo->query('SELECT SUM(price) as total FROM spends');
$t1 = $totalSpends->fetch();

$totalRelationShip = $pdo->query('SELECT SUM(price) as total FROM user_spend');
$t2 = $totalRelationShip->fetch();
print_r($t1);
print_r($t2);

$errors = print_r($t1['total']-$t2['total']);