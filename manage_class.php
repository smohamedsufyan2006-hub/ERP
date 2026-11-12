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
// FETCH CLASSES
// =========================================

$sql = "SELECT

            c.class_id,
            c.year_of_study,
            c.section,

            d.department_name,

            t.teacher_name,

            s.semester_name,

            ay.academic_year_name

        FROM classes c

        INNER JOIN departments d
        ON c.department_id = d.department_id

        INNER JOIN semesters s
        ON c.semester_id = s.semester_id

        INNER JOIN academic_years ay
        ON c.academic_year_id = ay.academic_year_id

        LEFT JOIN teachers t
        ON c.advisor_teacher_id = t.teacher_id";

if ($search != "") {

    $sql .= " WHERE

                d.department_name LIKE ?

                OR

                t.teacher_name LIKE ?

                OR

                c.section LIKE ?";

}

$sql .= " ORDER BY

            d.department_name,

            c.year_of_study,

            c.section";

$stmt = mysqli_prepare($conn, $sql);

if ($search != "") {

    $value = "%" . $search . "%";

    mysqli_stmt_bind_param(

        $stmt,

        "sss",

        $value,

        $value,

        $value

    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total_classes = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

Manage Classes

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="manage-student-container">

<!-- ========================================= -->
<!-- HEADER -->
<!-- ========================================= -->

<div class="page-header">

    <a href="admin_dashboard.php"
       class="back-btn"
       title="Dashboard">

        <i class="fa-solid fa-arrow-left"></i>

    </a>

    <h1>

        Manage Classes

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

        placeholder="Search by Department, Advisor or Section"

        value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>

            Search

        </button>

    </form>

    <a

    href="add_class.php"

    class="icon-btn add-btn"

    title="Add Class">

        <i class="fa-solid fa-plus"></i>

    </a>

</div>

<br>
<br>

<!-- ========================================= -->
<!-- TOTAL CLASSES -->
<!-- ========================================= -->

<div class="student-count-card">

<h3>

Total Classes

</h3>

<p>

<?php echo $total_classes; ?>

</p>

</div>

<br>

<!-- ========================================= -->
<!-- TABLE -->
<!-- ========================================= -->

<div class="student-table-wrapper">

<table class="student-table">

<thead>

<tr>

<th>S.No</th>

<th>Class</th>

<th>Department</th>

<th>Advisor</th>

<th>Semester</th>

<th>Academic Year</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

$serial = 1;

$roman = [

1 => "I",
2 => "II",
3 => "III",
4 => "IV"

];

if ($total_classes > 0) {

while ($class = mysqli_fetch_assoc($result)) {

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

<td>

<?php

echo $roman[$class["year_of_study"]] . " ";

echo htmlspecialchars($class["department_name"]) . " ";

echo htmlspecialchars($class["section"]);

?>

</td>

<td>

<?php echo htmlspecialchars($class["department_name"]); ?>

</td>

<td>

<?php

if (!empty($class["teacher_name"])) {

    echo htmlspecialchars($class["teacher_name"]);

}

else {

    echo "<span style='color:red;'>Not Assigned</span>";

}

?>

</td>

<td>

<?php echo htmlspecialchars($class["semester_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($class["academic_year_name"]); ?>

</td>

<td class="action-buttons">
    <a

href="manage_class_students.php?class_id=<?php echo $class["class_id"]; ?>"

class="student-view-btn">

<i class="fa-solid fa-users"></i>

Students

</a>

<a

href="update_class.php?class_id=<?php echo $class["class_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-pen-to-square"></i>

Edit

</a>

<a

href="delete_class.php?class_id=<?php echo $class["class_id"]; ?>"

class="student-delete-btn"

onclick="return confirm('Are you sure you want to delete this class?');">

<i class="fa-solid fa-trash"></i>

Delete

</a>

</td>

</tr>

<?php

}

}

else {

?>

<tr>

<td colspan="7">

<i class="fa-solid fa-circle-info"></i>

No class records found.

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