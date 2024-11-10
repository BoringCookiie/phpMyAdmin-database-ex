<?php
$servername = "localhost";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS koki";
if (mysqli_query($conn, $sql)) {
    echo "Database created or already exists successfully. ";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// Select the database
mysqli_select_db($conn, 'koki');

// Create table
$query = "
CREATE TABLE IF NOT EXISTS Clients (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE,
    password VARCHAR(80),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
";
if (mysqli_query($conn, $query)) {
    echo "Table Clients created successfully. ";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// Hash the password for the first insert
$password = password_hash("amine123456", PASSWORD_DEFAULT);

// Check if email already exists for the first insert
$email = 'Amineze@gmail.com';
$sql_check = "SELECT email FROM Clients WHERE email = '$email'";
$result = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result) == 0) {
    // Email doesn't exist, proceed with insert
    $sql = "INSERT INTO Clients (firstname, lastname, email, password)
            VALUES ('Amine', 'Ze', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully for Amine. ";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    // Email already exists
    echo "Error: Email '$email' already exists in the database.";
}

// Now handling the bulk insert with duplicate check
$emails_to_insert = [
    ['John', 'Doe', 'john@example.com'],
    ['Mary', 'Moe', 'mary@example.com'],
    ['Julie', 'Dooley', 'julie@example.com']
];

foreach ($emails_to_insert as $client) {
    $firstname = $client[0];
    $lastname = $client[1];
    $email = $client[2];

    // Check if email already exists
    $sql_check = "SELECT email FROM Clients WHERE email = '$email'";
    $result = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result) == 0) {
        // Email doesn't exist, proceed with insert
        $password = password_hash("amine123456", PASSWORD_DEFAULT);
        $sql = "INSERT INTO Clients (firstname, lastname, email, password)
                VALUES ('$firstname', '$lastname', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully for $firstname. ";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // Email already exists
        echo "Error: Email '$email' already exists in the database for $firstname. ";
    }
}

// Close the connection after all queries
mysqli_close($conn);
?>
