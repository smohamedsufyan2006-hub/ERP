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
// FETCH COURSES
// =========================================

$sql = "SELECT

            d.department_id,
            d.department_name,
            p.program_name

        FROM departments d

        LEFT JOIN programs p

        ON d.program_id = p.program_id";

if ($search != "") {

    $sql .= " WHERE d.department_name LIKE ?";

}

$sql .= " ORDER BY d.department_name ASC";

$stmt = mysqli_prepare($conn, $sql);

if ($search != "") {

    $search_value = "%" . $search . "%";

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $search_value

    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// =========================================
// TOTAL COURSES
// =========================================

$total_courses = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Manage Courses

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="manage-student-container">

<!-- ========================================= -->
<!-- PAGE HEADER -->
<!-- ========================================= -->

<div class="page-header">

    <a href="admin_dashboard.php"
       class="back-btn"
       title="Dashboard">

        <i class="fa-solid fa-arrow-left"></i>

    </a>

    <h1>

        Manage Courses

    </h1>

</div>

<!-- ========================================= -->
<!-- SEARCH -->
<!-- ========================================= -->

<div class="search-toolbar">

    <form method="GET" class="search-form">

        <input

        type="text"

        name="search"

        placeholder="Search by Course Name"

        value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>

            Search

        </button>

    </form>

    <a href="add_course.php"

       class="icon-btn add-btn"

       title="Add Course">

        <i class="fa-solid fa-plus"></i>

    </a>

</div>

<br>
<br>

<!-- ========================================= -->
<!-- TOTAL -->
<!-- ========================================= -->

<div class="student-count-card">

<h3>

Total Courses

</h3>

<p>

<?php echo $total_courses; ?>

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

<th>Course Name</th>

<th>Program</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

$serial = 1;

if ($total_courses > 0) {

while ($course = mysqli_fetch_assoc($result)) {

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

<td>

<?php echo htmlspecialchars($course["department_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($course["program_name"]); ?>

</td>

<td class="action-buttons">

<a

href="update_course.php?department_id=<?php echo $course["department_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-pen-to-square"></i>

Edit

</a>

<a

href="delete_course.php?department_id=<?php echo $course["department_id"]; ?>"

class="student-delete-btn"

onclick="return confirm('Are you sure you want to delete this course?');">

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

<td colspan="4">

<i class="fa-solid fa-circle-info"></i>

No courses found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</body>

</html>

<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>