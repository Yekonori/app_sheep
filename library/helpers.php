<?php
/**
 * @author: Antoine07
 */

function getFlashMessage()
{

    if( !isset($_SESSION) ) {
        throw new RuntimeException("Attention les sessions ne marche pas sur votre site");
    }

    if (isset($_SESSION['flash'])) {
            $message = sprintf('<div class="info">
                <strong class="%s">%s</strong>
                </div>', 
            $_SESSION['flash']['type'],
            $_SESSION['flash']['message']
        );
        unset($_SESSION['flash']);

        return $message;
    }
}

function setFlashMessage($message, $type = 'success')
{
    if( !isset($_SESSION) ) {
        throw new RuntimeException("Attention les sessions ne marche pas sur votre site");
    }
    
    $_SESSION['flash'] = [
        'message' => $message,
        'type'    => $type
    ];
}

function hasFlashMessage(){
    if( !isset($_SESSION) ) {
        throw new RuntimeException("Attention les sessions ne marche pas sur votre site");
    }

    if(isset($_SESSION['flash'])) return true;

    return false;

}

function get_pdo(){
    
    $defaults = [
        // PDO remonte les erreurs SQL, sinon il retourne une bête erreur PHP
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // retournera les données dans un tableau associatifs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME, 
        DB_USER, DB_PASSWORD, 
        $defaults
    );

    return $pdo;

}

function dump($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}