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
// FETCH SUBJECTS
// =========================================

$sql = "SELECT *

        FROM subjects";

if ($search != "") {

    $sql .= " WHERE

                subject_code LIKE ?

                OR

                subject_name LIKE ?";

}

$sql .= " ORDER BY subject_code ASC";

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
// TOTAL SUBJECTS
// =========================================

$total_subjects = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Manage Subjects

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

        Manage Subjects

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

        placeholder="Search by Subject Code or Subject Name"

        value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">

            <i class="fa-solid fa-search"></i>

            Search

        </button>

    </form>

    <a href="add_subject.php"

       class="icon-btn add-btn"

       title="Add Subject">

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

Total Subjects

</h3>

<p>

<?php echo $total_subjects; ?>

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

<th>Subject Code</th>

<th>Subject Name</th>

<th>Credits</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

$serial = 1;

if ($total_subjects > 0) {

while ($subject = mysqli_fetch_assoc($result)) {

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

<td>

<?php echo htmlspecialchars($subject["subject_code"]); ?>

</td>

<td>

<?php echo htmlspecialchars($subject["subject_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($subject["credits"]); ?>

</td>

<td class="action-buttons">

<a

href="update_subject.php?subject_id=<?php echo $subject["subject_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-pen-to-square"></i>

Edit

</a>

<a

href="delete_subject.php?subject_id=<?php echo $subject["subject_id"]; ?>"

class="student-delete-btn"

onclick="return confirm('Are you sure you want to delete this subject?');">

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

<td colspan="5">

<i class="fa-solid fa-circle-info"></i>

No subjects found.

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