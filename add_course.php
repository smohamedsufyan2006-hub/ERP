<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK ADMIN LOGIN
// =========================================

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");
    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// FETCH PROGRAMS
// =========================================

$sql = "SELECT *
        FROM programs
        ORDER BY program_name ASC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Add Course

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
<div class="container">

    <div class="form-card">

        <div class="page-header">

            <a href="manage_course.php" class="back-btn">

                <i class="fa-solid fa-arrow-left"></i>

            </a>

            <h1>Add Course</h1>

        </div>

        <form action="add_course_process.php" method="POST">

            <div class="row">

                <div class="input-group">

                    <label>Course Name</label>

                    <input
                    type="text"
                    name="department_name"
                    required>

                </div>

                <div class="input-group">

                    <label>Program</label>

                    <select name="program_id" required>

    <option value="">-- Select Program --</option>

    <?php while($program = mysqli_fetch_assoc($result)) { ?>

        <option value="<?php echo $program['program_id']; ?>">

            <?php echo htmlspecialchars($program['program_name']); ?>

        </option>

    <?php } ?>

</select>
                </div>

            </div>

            <div class="button-row">

    <button
        type="submit"
        class="save-btn">

        <i class="fa-solid fa-floppy-disk"></i>

        Save

    </button>

    <a
        href="manage_course.php"
        class="cancel-btn">

        Cancel

    </a>

</div>

        </form>

    </div>

</div>

</body>

</html>

<?php

mysqli_close($conn);

?>