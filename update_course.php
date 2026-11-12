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
// CHECK DEPARTMENT ID
// =========================================

if (!isset($_GET["department_id"])) {

    header("Location: manage_course.php");
    exit();

}

$department_id = intval($_GET["department_id"]);

// =========================================
// FETCH COURSE
// =========================================

$sql = "SELECT *
        FROM departments
        WHERE department_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $department_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    die("Course not found.");

}

$course = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// FETCH PROGRAMS
// =========================================

$sql = "SELECT *
        FROM programs
        ORDER BY program_name ASC";

$result_programs = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

Update Course

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="container">

    <div class="form-card">

        <!-- ========================================= -->
        <!-- PAGE HEADER -->
        <!-- ========================================= -->

        <div class="page-header">

            <a href="manage_courses.php"
               class="back-btn"
               title="Back">

                <i class="fa-solid fa-arrow-left"></i>

            </a>

            <h1>

                Update Course

            </h1>

        </div>

        <!-- ========================================= -->
        <!-- FORM -->
        <!-- ========================================= -->

        <form action="update_course_process.php" method="POST">

            <input
                type="hidden"
                name="department_id"
                value="<?php echo $course["department_id"]; ?>">

            <div class="input-group">

                <label>

                    Course Name 

                </label>

                <input
                    type="text"
                    name="department_name"
                    value="<?php echo htmlspecialchars($course["department_name"]); ?>"
                    required>

            </div>

            <div class="input-group">

                <label>

                    Program 

                </label>

                <select name="program_id" required>

                    <option value="">-- Select Program --</option>

                    <?php while($program = mysqli_fetch_assoc($result_programs)){ ?>

                        <option
                            value="<?php echo $program["program_id"]; ?>"

                            <?php
                            if($program["program_id"] == $course["program_id"]){
                                echo "selected";
                            }
                            ?>>

                            <?php echo htmlspecialchars($program["program_name"]); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="button-row">

                <button
                    type="submit"
                    class="save-btn">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Update

                </button>

                <a
                    href="manage_courses.php"
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