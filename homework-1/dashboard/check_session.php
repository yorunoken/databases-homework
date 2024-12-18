<?php
session_start();
$response = ['loggedIn' => false];


if (isset($_SESSION["user_id"])) {
    $response['loggedIn'] = true;
    $response['username'] = $_SESSION['username'];
    $response['email'] = $_SESSION['email'];
}

header('Content-Type: application/json');
echo json_encode($response);
