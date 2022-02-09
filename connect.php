<?php
// will be used for all db connections

// ----------
// always set db passwords to 255 chars 
// ----------

$db = 'wp_final';
$user = 'root';
$pass = '';

try{
    // creates PDO (PHP Data Object)
    $pdo = new PDO('mysql:host=localhost; dbname='.$db,$user,$pass);
    
    // must have if you use PDOException
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    // only for edu to demo connection status
    $dbstat = "<br>Connected<br>";

    // optional (utf8 is default)
    $pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
    $dbstat = '<br>NOT Connected<br>'.
    $e->getMessage();
    // die();
}

SESSION_START();

?>