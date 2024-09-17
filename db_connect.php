<?php


    $host = 'localhost';
    $dbname = 'vrijstelling';
    $username = 'root';
    $password = '';
    $port = 3307;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        exit();
    }
