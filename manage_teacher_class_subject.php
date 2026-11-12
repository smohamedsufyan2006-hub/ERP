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

if(isset($_GET["search"])){

    $search = trim($_GET["search"]);

}

// =========================================
// FETCH DATA
// =========================================

$sql = "SELECT

            tcs.teacher_class_subject_id,

            c.year_of_study,

            c.section,

            d.department_name,

            s.subject_code,

            s.subject_name,

            t.teacher_name

        FROM teacher_class_subjects tcs

        INNER JOIN class_subjects cs

        ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN classes c

        ON cs.class_id = c.class_id

        INNER JOIN departments d

        ON c.department_id = d.department_id

        INNER JOIN subjects s

        ON cs.subject_id = s.subject_id

        INNER JOIN teachers t

        ON tcs.teacher_id = t.teacher_id";

if($search!=""){

    $sql .= " WHERE

                d.department_name LIKE ?

                OR

                s.subject_code LIKE ?

                OR

                s.subject_name LIKE ?

                OR

                t.teacher_name LIKE ?";

}

$sql .= " ORDER BY

            d.department_name,

            c.year_of_study,

            c.section,

            s.subject_code";

$stmt = mysqli_prepare($conn,$sql);

if($search!=""){

    $value="%".$search."%";

    mysqli_stmt_bind_param(

        $stmt,

        "ssss",

        $value,

        $value,

        $value,

        $value

    );

}

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

$total_records=mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Teacher Subject Allocation</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="manage-student-container">

<div class="page-header">

<a href="admin_dashboard.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Teacher Subject Allocation

</h1>

</div>

<div class="search-toolbar">

<form method="GET" class="search-form">

<input

type="text"

name="search"

placeholder="Search Teacher or Subject"

value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">

<i class="fa-solid fa-search"></i>

Search

</button>

</form>

<a

href="add_teacher_class_subject.php"

class="icon-btn add-btn"

title="Assign Teacher">

<i class="fa-solid fa-plus"></i>

</a>

</div>

<br><br>

<div class="student-count-card">

<h3>

Total Assignments

</h3>

<p>

<?php echo $total_records; ?>

</p>

</div>

<br>

<div class="student-table-wrapper">

<table class="student-table">

<thead>

<tr>

<th>S.No</th>

<th>Class</th>

<th>Subject Code</th>

<th>Subject Name</th>

<th>Teacher</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

$roman=[

1=>"I",

2=>"II",

3=>"III",

4=>"IV"

];

$serial=1;

if($total_records>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $serial++; ?></td>

<td>

<?php

echo $roman[$row["year_of_study"]]." ";

echo htmlspecialchars($row["department_name"])." ";

echo htmlspecialchars($row["section"]);

?>

</td>

<td>

<?php echo htmlspecialchars($row["subject_code"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["subject_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["teacher_name"]); ?>

</td>

<td class="action-buttons">

<a

href="update_teacher_class_subject.php?teacher_class_subject_id=<?php echo $row["teacher_class_subject_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-pen-to-square"></i>

Edit

</a>

<a

href="delete_teacher_class_subject.php?teacher_class_subject_id=<?php echo $row["teacher_class_subject_id"]; ?>"

class="student-delete-btn"

onclick="return confirm('Are you sure you want to delete this assignment?');">

<i class="fa-solid fa-trash"></i>

Delete

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6">

<i class="fa-solid fa-circle-info"></i>

No teacher assignments found.

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