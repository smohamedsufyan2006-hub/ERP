
<?php

require_once __DIR__ . "/config.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = mysqli_connect(
        $servername,
        $username,
        $password,
        $database,
        $port
    );

    mysqli_set_charset($conn, "utf8mb4");

} catch (mysqli_sql_exception $e) {

    error_log("Database connection failed: " . $e->getMessage());

    http_response_code(500);
    die("Database connection unavailable.");

}

?>
