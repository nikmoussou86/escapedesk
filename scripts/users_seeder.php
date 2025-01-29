<?php
$host = 'db';
$dbname = 'escapedesk';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sql = "INSERT INTO users (user_name, email, password, type, employee_code, created_at, updated_at) 
            VALUES (:username, :email, :password, :type, :employee_code, NOW(), NOW())";

    $stmt = $pdo->prepare($sql);

    $users = [
        [
            'username' => 'tom_manager',
            'email' => 'manager@example.com',
            'password' => password_hash('securepass123', PASSWORD_BCRYPT),
            'type' => 1,
            'employee_code' => '1234567'
        ],
        [
            'username' => 'jerry_employee',
            'email' => 'employee@example.com',
            'password' => password_hash('securepass123', PASSWORD_BCRYPT),
            'type' => 2,
            'employee_code' => '7654321'
        ]
    ];

    foreach ($users as $user) {
        $stmt->execute($user);
    }

    echo "Records inserted successfully.";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
