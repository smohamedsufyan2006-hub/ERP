<?php

session_start();

if(!isset($_SESSION["student_name"])){

    header("Location: login.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="style.css">
    

</head>

<body>

<div class="container">

    <h1>Student Dashboard</h1>

    <p class="subtitle">
        Welcome to your dashboard. Manage your student account easily.
    </p>

    <div class="welcome-card">

        <h2>
            👋 Welcome,
            <?php echo $_SESSION["student_name"]; ?>
        </h2>

        <p>
            You have successfully logged in.
            
        </p>

    </div>

    <div class="dashboard-buttons">

        <a href="register.php" class="dashboard-btn blue">

            Register Student

        </a>

        <a href="student_assignments.php" class="dashboard-btn blue">

            My Assignments

        </a>

        <a href="logout.php" class="dashboard-btn blue">

            Logout

        </a>
        <a href="update_student.php" class="dashboard-btn blue">
    Update Profile
</a>

    </div>

</div>

</body>

</html>