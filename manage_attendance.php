<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK TEACHER LOGIN
// =========================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: teacher_login.php");

    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// LOGGED IN TEACHER
// =========================================

$teacher_id = $_SESSION["teacher_id"];

// =========================================
// SEARCH
// =========================================

$search = "";

if(isset($_GET["search"])){

    $search = trim($_GET["search"]);

}

// =========================================
// FETCH ASSIGNED CLASSES
// =========================================

$sql = "SELECT

            tcs.teacher_class_subject_id,

            c.class_id,

            c.year_of_study,

            c.section,

            d.department_name,

            s.subject_code,

            s.subject_name

        FROM teacher_class_subjects tcs

        INNER JOIN class_subjects cs

            ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN classes c

            ON cs.class_id = c.class_id

        INNER JOIN departments d

            ON c.department_id = d.department_id

        INNER JOIN subjects s

            ON cs.subject_id = s.subject_id

        WHERE

            tcs.teacher_id = ?";

if($search!=""){

    $sql .= " AND (

                d.department_name LIKE ?

                OR

                s.subject_name LIKE ?

                OR

                s.subject_code LIKE ?

            )";

}

$sql .= " ORDER BY

            c.year_of_study,

            c.section,

            s.subject_name";

$stmt = mysqli_prepare($conn,$sql);

if($search!=""){

    $value="%".$search."%";

    mysqli_stmt_bind_param(

        $stmt,

        "isss",

        $teacher_id,

        $value,

        $value,

        $value

    );

}else{

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $teacher_id

    );

}

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

$total=mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Attendance Management</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="teacher.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="manage-student-container">
    <div class="teacher-page-card">

<div class="page-header">

<a

href="teacher_dashboard.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Attendance Management

</h1>

</div>

<div class="search-toolbar">

<form

method="GET"

class="search-form">

<input

type="text"

name="search"

placeholder="Search Subject"

value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">

<i class="fa-solid fa-search"></i>

Search

</button>

</form>

</div>

<br><br>

<div class="student-count-card">

<h3>

Assigned Subjects

</h3>

<p>

<?php echo $total; ?>

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

<th>Subject</th>

<th>Action</th>

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

if($total>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

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

<a

href="take_attendance.php?teacher_class_subject_id=<?php echo $row["teacher_class_subject_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-calendar-check"></i>

Take Attendance

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5">

No Assigned Subjects

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

<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>