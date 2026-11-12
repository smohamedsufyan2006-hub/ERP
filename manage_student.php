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
// SEARCH
// =========================================

$search = "";

if (isset($_GET["search"])) {

    $search = trim($_GET["search"]);

}

// =========================================
// FETCH STUDENTS
// =========================================

$sql = "SELECT

            s.student_id,
            u.register_number,
            u.email,
            s.student_name,
            s.phone,
            d.department_name,
            g.gender_name

        FROM students s

        INNER JOIN user u
        ON s.user_id = u.user_id

        LEFT JOIN departments d
        ON s.department_id = d.department_id

        LEFT JOIN genders g
        ON s.gender_id = g.gender_id";

if ($search != "") {

    $sql .= " WHERE

                u.register_number LIKE ?

                OR

                s.student_name LIKE ?";

}

$sql .= " ORDER BY s.student_name ASC";

$stmt = mysqli_prepare($conn, $sql);

if ($search != "") {

    $search_value = "%" . $search . "%";

    mysqli_stmt_bind_param(

        $stmt,

        "ss",

        $search_value,

        $search_value

    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// =========================================
// TOTAL STUDENTS
// =========================================

$total_students = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Manage Students

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
    


<div class="manage-student-container">

<div class="page-header">

    <a href="admin_dashboard.php"
       class="back-btn"
       title="Dashboard">

        <i class="fa-solid fa-arrow-left"></i>

    </a>


    <h1>
        Manage Students
    </h1>

</div>



<div class="search-toolbar">

    <form method="GET" class="search-form">

        <input
        type="text"
        name="search"
        placeholder="Search by Register Number or Student Name"
        value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>
            Search

        </button>

    </form>


    <!-- Add Student Icon -->
    <a href="register.php"
       class="icon-btn add-btn"
       title="Add Student">

        <i class="fa-solid fa-user-plus"></i>

    </a>
    <br>
    <br>


</div>
<!-- ========================================= -->
<!-- SEARCH -->
<!-- ========================================= -->
<br>
<br>
<!-- ========================================= -->
<!-- TOTAL STUDENTS -->
<!-- ========================================= -->

<div class="student-count-card">

<h3>

Total Students

</h3>

<p>

<?php echo $total_students; ?>

</p>

</div>

<!-- ========================================= -->
<!-- TABLE -->
<!-- ========================================= -->

<div class="student-table-wrapper">

<table class="student-table">

<thead>

<tr>

<th>S.No</th>

<th>Register No</th>

<th>Student Name</th>

<th>Email</th>

<th>Phone</th>

<th>Department</th>

<th>Gender</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

$serial = 1;

if ($total_students > 0) {

while ($student = mysqli_fetch_assoc($result)) {

?>
        <tr>

            <td>

                <?php echo $serial++; ?>

            </td>

            <td>

                <?php echo htmlspecialchars($student["register_number"]); ?>

            </td>

            <td>

                <?php echo htmlspecialchars($student["student_name"]); ?>

            </td>

            <td>

                <?php echo htmlspecialchars($student["email"]); ?>

            </td>

            <td>

                <?php echo htmlspecialchars($student["phone"]); ?>

            </td>

            <td>

                <?php echo htmlspecialchars($student["department_name"]); ?>

            </td>

            <td>

                <?php echo htmlspecialchars($student["gender_name"]); ?>

            </td>

            <td class="action-buttons">

                <a

                href="student_profile.php?student_id=<?php echo $student["student_id"]; ?>"

                class="student-view-btn">

                <i class="fa-solid fa-eye"></i>

                View

                </a>

                <a

                href="admin_update_student.php?student_id=<?php echo $student["student_id"]; ?>"

                class="student-edit-btn">

                <i class="fa-solid fa-pen-to-square"></i>

                Edit

                </a>

                <a

                href="delete_student.php?student_id=<?php echo $student["student_id"]; ?>"

                class="student-delete-btn"

                onclick="return confirm('Are you sure you want to delete this student?');">

                <i class="fa-solid fa-trash"></i>

                Delete

                </a>

            </td>

        </tr>

<?php

    }

}

else{

?>

<tr>

    <td colspan="8">

        <i class="fa-solid fa-circle-info"></i>

        No student records found.

    </td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<br>

<!-- ========================================= -->
<!-- BOTTOM BUTTONS -->
<!-- ========================================= -->


<!-- ========================================= -->
<!-- END CONTAINER -->
<!-- ========================================= -->

</div>

</body>

</html>

<?php

// =========================================
// CLOSE DATABASE CONNECTION
// =========================================

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>