<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "config.php";

try {
    $sql = file_get_contents("init.sql");
    $pdo->exec($sql);

    echo "Database and table created successfully.";
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
