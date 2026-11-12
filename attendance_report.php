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
// FILTER VARIABLES
// =========================================

$class_id = "";

$subject_id = "";

$from_date = "";

$to_date = "";

if(isset($_GET["class_id"])){

    $class_id = trim($_GET["class_id"]);

}

if(isset($_GET["subject_id"])){

    $subject_id = trim($_GET["subject_id"]);

}

if(isset($_GET["from_date"])){

    $from_date = $_GET["from_date"];

}

if(isset($_GET["to_date"])){

    $to_date = $_GET["to_date"];

}

// =========================================
// LOAD CLASSES
// =========================================

$sql = "SELECT DISTINCT

            c.class_id,

            c.year_of_study,

            c.section,

            d.department_name

        FROM teacher_class_subjects tcs

        INNER JOIN class_subjects cs

            ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN classes c

            ON cs.class_id = c.class_id

        INNER JOIN departments d

            ON c.department_id = d.department_id

        WHERE

            tcs.teacher_id = ?

        ORDER BY

            c.year_of_study,

            c.section";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_id

);

mysqli_stmt_execute($stmt);

$class_result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);

// =========================================
// LOAD SUBJECTS
// =========================================

$sql = "SELECT DISTINCT

            s.subject_id,

            s.subject_code,

            s.subject_name

        FROM teacher_class_subjects tcs

        INNER JOIN class_subjects cs

            ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN subjects s

            ON cs.subject_id = s.subject_id

        WHERE

            tcs.teacher_id = ?

        ORDER BY

            s.subject_name";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_id

);

mysqli_stmt_execute($stmt);

$subject_result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);

// =========================================
// BUILD REPORT QUERY
// =========================================

$sql = "SELECT

            u.register_number,

            st.student_name,

            COUNT(a.attendance_id) AS total_classes,

            SUM(

                CASE

                    WHEN a.attendance_status='Present'

                    THEN 1

                    ELSE 0

                END

            ) AS present_count,

            SUM(

                CASE

                    WHEN a.attendance_status='Absent'

                    THEN 1

                    ELSE 0

                END

            ) AS absent_count,

            ROUND(

                (

                    SUM(

                        CASE

                            WHEN a.attendance_status='Present'

                            THEN 1

                            ELSE 0

                        END

                    )

                    /

                    COUNT(a.attendance_id)

                ) * 100,

                2

            ) AS attendance_percentage

        FROM attendance a

        INNER JOIN students st

            ON a.student_id = st.student_id

        INNER JOIN user u

            ON st.user_id = u.user_id

        INNER JOIN attendance_sessions ats

            ON a.attendance_session_id = ats.attendance_session_id

        INNER JOIN teacher_class_subjects tcs

            ON ats.teacher_class_subject_id = tcs.teacher_class_subject_id

        INNER JOIN class_subjects cs

            ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN classes c

            ON cs.class_id = c.class_id

        WHERE

            tcs.teacher_id = ?";

$types = "i";

$params = [$teacher_id];

// =========================================
// OPTIONAL FILTERS
// =========================================

if($class_id != ""){

    $sql .= " AND c.class_id = ?";

    $types .= "i";

    $params[] = $class_id;

}

if($subject_id != ""){

    $sql .= " AND cs.subject_id = ?";

    $types .= "i";

    $params[] = $subject_id;

}

if($from_date != "" && $to_date != ""){

    $sql .= " AND ats.attendance_date BETWEEN ? AND ?";

    $types .= "ss";

    $params[] = $from_date;

    $params[] = $to_date;

}

// =========================================
// GROUP BY
// =========================================

$sql .= " GROUP BY

            st.student_id

          ORDER BY

            u.register_number";

// =========================================
// EXECUTE QUERY
// =========================================

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

    $types,

    ...$params

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total = mysqli_num_rows($result);

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Attendance Report

</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="teacher.css">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="manage-student-container">
    <div class="teacher-page-card">

<!-- ========================================= -->
<!-- PAGE HEADER -->
<!-- ========================================= -->

<div class="page-header">

<a

href="teacher_dashboard.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Attendance Report

</h1>

</div>

<!-- ========================================= -->
<!-- FILTER CARD -->
<!-- ========================================= -->

<div class="form-card">

<form method="GET">

<div class="form-grid">

<div class="form-group">

<label>

Class

</label>

<select name="class_id">

<option value="">

All Classes

</option>

<?php

$roman = [

1=>"I",

2=>"II",

3=>"III",

4=>"IV"

];

while($class=mysqli_fetch_assoc($class_result)){

?>

<option

value="<?php echo $class["class_id"]; ?>"

<?php

if($class_id==$class["class_id"])

echo "selected";

?>

>

<?php

echo $roman[$class["year_of_study"]]." ";

echo htmlspecialchars($class["department_name"])." ";

echo htmlspecialchars($class["section"]);

?>

</option>

<?php

}

?>

</select>

</div>

<div class="form-group">

<label>

Subject

</label>

<select name="subject_id">

<option value="">

All Subjects

</option>

<?php

while($subject=mysqli_fetch_assoc($subject_result)){

?>

<option

value="<?php echo $subject["subject_id"]; ?>"

<?php

if($subject_id==$subject["subject_id"])

echo "selected";

?>

>

<?php

echo htmlspecialchars($subject["subject_code"]);

echo " - ";

echo htmlspecialchars($subject["subject_name"]);

?>

</option>

<?php

}

?>

</select>

</div>

<div class="form-group">

<label>

From Date

</label>

<input

type="date"

name="from_date"

value="<?php echo $from_date; ?>">

</div>

<div class="form-group">

<label>

To Date

</label>

<input

type="date"

name="to_date"

value="<?php echo $to_date; ?>">

</div>

</div>

<br>

<div class="form-buttons">

<button

type="submit"

class="save-btn">

<i class="fa-solid fa-filter"></i>

Search

</button>

<a

href="attendance_report.php"

class="cancel-btn">

Reset

</a>

</div>

</form>

</div>

<br>

<!-- ========================================= -->
<!-- TOTAL -->
<!-- ========================================= -->

<div class="student-count-card">

<h3>

Students Found

</h3>

<p>

<?php echo $total; ?>

</p>

</div>

<br>

<!-- ========================================= -->
<!-- REPORT TABLE -->
<!-- ========================================= -->

<div class="student-table-wrapper">

<table class="student-table">

<thead>

<tr>

<th>S.No</th>

<th>Register No</th>

<th>Student Name</th>

<th>Present</th>

<th>Absent</th>

<th>Total</th>

<th>Attendance %</th>

</tr>

</thead>

<tbody>

<?php

$serial = 1;

if($total>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>

<td>

<?php echo $row["present_count"]; ?>

</td>

<td>

<?php echo $row["absent_count"]; ?>

</td>

<td>

<?php echo $row["total_classes"]; ?>

</td>

<td>

<?php

$percentage = $row["attendance_percentage"];

if($percentage >= 75){

?>

<span style="color:green;font-weight:bold;">

<?php echo $percentage; ?>%

</span>

<?php

}elseif($percentage >= 60){

?>

<span style="color:orange;font-weight:bold;">

<?php echo $percentage; ?>%

</span>

<?php

}else{

?>

<span style="color:red;font-weight:bold;">

<?php echo $percentage; ?>%

</span>

<?php

}

?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7">

<i class="fa-solid fa-circle-info"></i>

No attendance records found.

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
