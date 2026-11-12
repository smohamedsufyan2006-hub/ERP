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
// CHECK CLASS ID
// =========================================

if (!isset($_GET["class_id"])) {

    header("Location: manage_class.php");
    exit();

}

$class_id = $_GET["class_id"];


// =========================================
// FETCH CLASS DETAILS
// =========================================

$class_sql = "

SELECT

c.year_of_study,

c.section,

d.department_name,

s.semester_name,

ay.academic_year_name

FROM classes c

INNER JOIN departments d
ON c.department_id = d.department_id

INNER JOIN semesters s
ON c.semester_id = s.semester_id

INNER JOIN academic_years ay
ON c.academic_year_id = ay.academic_year_id

WHERE c.class_id = ?

";

$stmt = mysqli_prepare($conn, $class_sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $class_id
);

mysqli_stmt_execute($stmt);

$class_result = mysqli_stmt_get_result($stmt);

$class = mysqli_fetch_assoc($class_result);


// =========================================
// FETCH STUDENTS
// =========================================

$student_sql = "

SELECT

s.student_name,

s.phone,

u.register_number

FROM students s

INNER JOIN user u
ON s.user_id = u.user_id

WHERE s.class_id = ?

ORDER BY s.student_name

";

$stmt2 = mysqli_prepare($conn, $student_sql);

mysqli_stmt_bind_param(
    $stmt2,
    "i",
    $class_id
);

mysqli_stmt_execute($stmt2);

$result = mysqli_stmt_get_result($stmt2);

$total_students = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Class Students</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="manage_class_students.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
    <div class="class-page-card">


<div class="class-students-container">


    <!-- ==========================
         PAGE HEADER
    =========================== -->

    <div class="class-page-header">

        <a href="manage_class.php" class="class-back-btn">

            <i class="fa-solid fa-arrow-left"></i>

        </a>

        <h1>Class Students</h1>

    </div>



    <!-- ==========================
         CLASS DETAILS + TOTAL STUDENTS
    =========================== -->

    <div class="class-top-section">


        <!-- CLASS DETAILS CARD -->

        <div class="class-info-card">

            <h2>

                <?php

                $roman = [

                    1 => "I",
                    2 => "II",
                    3 => "III",
                    4 => "IV"

                ];

                echo $roman[$class["year_of_study"]] . " ";

                echo htmlspecialchars($class["department_name"]);

                echo " " . htmlspecialchars($class["section"]);

                ?>

            </h2>

            <p>

                <?php echo htmlspecialchars($class["semester_name"]); ?>

            </p>

            <p>

                Academic Year :
                <?php echo htmlspecialchars($class["academic_year_name"]); ?>

            </p>

        </div>



        <!-- TOTAL STUDENTS CARD -->

        <div class="student-count-card">

            <h3>Total Students</h3>

            <p>

                <?php echo $total_students; ?>

            </p>

        </div>

    </div>


    <!-- ==========================
         STUDENT TABLE STARTS HERE
    =========================== -->

    <div class="student-table-container">

        <table class="class-student-table">

            <thead>

                <tr>

                    <th>S.No</th>

                    <th>Register Number</th>

                    <th>Student Name</th>

                    <th>Phone</th>

                </tr>

            </thead>

            <tbody>
                <?php

$serial = 1;

if ($total_students > 0) {

    while ($student = mysqli_fetch_assoc($result)) {

?>

<tr>

    <td><?php echo $serial++; ?></td>

    <td>

        <?php echo htmlspecialchars($student["register_number"]); ?>

    </td>

    <td>

        <?php echo htmlspecialchars($student["student_name"]); ?>

    </td>

    <td>

        <?php echo htmlspecialchars($student["phone"]); ?>

    </td>

</tr>

<?php

    }

} else {

?>

<tr>

    <td colspan="4" class="no-students">

        <i class="fa-solid fa-circle-info"></i>

        No students assigned to this class.

    </td>

</tr>

<?php

}

?>

            </tbody>

        </table>

    </div>

</div>
</div>

</body>

</html>