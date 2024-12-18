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
    $email = $_POST['email'];
    $submitted_password = $_POST['password'];

    if (empty($email) || empty($submitted_password)) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        return;
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($submitted_password, $user["password"])) {
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];

            $response['success'] = true;
            $response['message'] = "Login successful!";
            $response['user'] = [
                'username' => $user['username'],
                'email' => $user['email']
            ];
        } else {
            $response['message'] = "Invalid password";
        }
    } else {
        $response['message'] = "User not found";
    }


    $connection->close();
    echo json_encode($response);
    exit;
} else {
    $response["message"] = "Must be a POST request";
    echo json_encode($response);
    exit;
}
