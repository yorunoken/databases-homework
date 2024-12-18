<?php
$servername = "localhost";
$username = "yoru";
$password = "0410";
$dbname = "homework";

$connection = new mysqli($servername, $username, $password, $dbname);
$response = ['success' => false, 'message' => ''];

if ($connection->connect_error) {
    $response['message'] = "Connection failed:  $connection->connect_error";
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        return;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Created successfully!";
    } else {
        $response['message'] = "Error: $stmt->error";
    }


    $connection->close();
    echo json_encode($response);
    exit;
} else {
    $response["message"] = "Must be a POST request";
    echo json_encode($response);
    exit;
}
