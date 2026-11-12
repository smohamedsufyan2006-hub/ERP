
<?php

// Database Configuration

$servername = getenv('DB_HOST') ?: 'localhost';

$username = getenv('DB_USER') ?: 'root';

$password = getenv('DB_PASSWORD') ?: '';

$database = getenv('DB_NAME') ?: 'student_management_system';

$port = (int)(getenv('DB_PORT') ?: 3306);

?>
