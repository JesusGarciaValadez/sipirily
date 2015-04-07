<?php
try {

    $dbh = new PDO( 'mysql:host=localhost;dbname=*7uF070C0n3lGu3r0_', '_Gu3r0-Y1sus01_', '_Asukal01_' );
    $dbh->exec("SET CHARACTER SET utf8");
} catch ( PDOException $e ){

    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}