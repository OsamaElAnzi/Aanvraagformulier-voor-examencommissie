<?php

require_once 'db_connect.php';

function fetchDataFromDatabase()
{
    $conn = get_connection();

    $sql = "SELECT * FROM aanvraag";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $data;
}
