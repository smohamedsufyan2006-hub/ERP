<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Management System - Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Login</h1>

    <p class="subtitle">

        Students, Teachers and Administrators can log in using the same portal.

    </p>

    <form action="login_process.php" method="POST">

        <label>Username</label>

        <input
        type="text"
        name="register_number"
        placeholder="Enter Register Number"
        required>

        <label>Password</label>

        <input
        type="password"
        name="password"
        placeholder="Enter Password"
        required>

        <button type="submit">

            Login

        </button>

        <p style="text-align:center; margin-top:20px;">

            New Student?

            <a class="register-link" href="register.php">

                Register Here

            </a>

        </p>

    </form>

</div>

</body>

</html>