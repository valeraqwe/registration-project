<?php
$logFile = 'registration_log.txt';

// Load existing users from the file or initialize as an empty array if the file doesn't exist
$existingUsers = json_decode(file_get_contents('users_data.json'), true) ?? [];

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $repeatPassword = $_POST['repeatPassword'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email!';
        logData($email, 'Invalid email');
    } elseif ($password !== $repeatPassword) {
        $response['message'] = 'Passwords do not match!';
        logData($email, 'Passwords mismatch');
    } elseif (in_array($email, array_column($existingUsers, 'email'), true)) {
        $response['message'] = 'Email already exists!';
        logData($email, 'Email already exists');
    } else {
        $existingUsers[] = [
            'id' => count($existingUsers) + 1,
            'email' => $email,
            'name' => $_POST['name'] ?? '',
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        file_put_contents('users_data.json', json_encode($existingUsers));  // Persist the updated user data
        $response['success'] = true;
        $response['message'] = 'Registration successful!';
        logData($email, 'Registration successful');
    }
}

header('Content-Type: application/json');
echo json_encode($response);

function logData(string $email, string $message): void {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Email: $email - Message: $message\n", FILE_APPEND);
}

