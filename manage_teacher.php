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
// FETCH TEACHERS
// =========================================

$sql = "SELECT

            t.teacher_id,
            t.employee_id,
            t.teacher_name,
            t.phone,
            u.email,
            d.department_name,
            g.gender_name

        FROM teachers t

        INNER JOIN user u
        ON t.user_id = u.user_id

        LEFT JOIN departments d
        ON t.department_id = d.department_id

        LEFT JOIN genders g
        ON t.gender_id = g.gender_id";

if ($search != "") {

    $sql .= " WHERE

                t.employee_id LIKE ?

                OR

                t.teacher_name LIKE ?";

}

$sql .= " ORDER BY t.teacher_name ASC";

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
// TOTAL TEACHERS
// =========================================

$total_teachers = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

Manage Teachers

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

        Manage Teachers

    </h1>

</div>

<div class="search-toolbar">

    <form method="GET" class="search-form">

        <input
        type="text"
        name="search"
        placeholder="Search by Employee ID or Teacher Name"
        value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>

            Search

        </button>

    </form>

    <!-- Add Teacher -->

    <a href="teacher.php"
       class="icon-btn add-btn"
       title="Add Teacher">

        <i class="fa-solid fa-user-plus"></i>

    </a>

    <br>
    <br>

</div>

<br>
<br>

<!-- ========================================= -->
<!-- TOTAL TEACHERS -->
<!-- ========================================= -->

<div class="student-count-card">

<h3>

Total Teachers

</h3>

<p>

<?php echo $total_teachers; ?>

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

<th>Employee ID</th>

<th>Teacher Name</th>

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

if ($total_teachers > 0) {

while ($teacher = mysqli_fetch_assoc($result)) {

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

<td>

<?php echo htmlspecialchars($teacher["employee_id"]); ?>

</td>

<td>

<?php echo htmlspecialchars($teacher["teacher_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($teacher["email"]); ?>

</td>

<td>

<?php echo htmlspecialchars($teacher["phone"]); ?>

</td>

<td>

<?php echo htmlspecialchars($teacher["department_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($teacher["gender_name"]); ?>

</td>

<td class="action-buttons">

<a

href="teacher_profile.php?teacher_id=<?php echo $teacher["teacher_id"]; ?>"

class="student-view-btn">

<i class="fa-solid fa-eye"></i>

View

</a>

<a

href="teacher_update.php?teacher_id=<?php echo $teacher["teacher_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-pen-to-square"></i>

Edit

</a>

<a

href="delete_teacher.php?teacher_id=<?php echo $teacher["teacher_id"]; ?>"

class="student-delete-btn"

onclick="return confirm('Are you sure you want to delete this teacher?');">

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

No teacher records found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<br>

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