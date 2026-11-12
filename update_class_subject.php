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
// CHECK CLASS SUBJECT ID
// =========================================

if (!isset($_GET["class_subject_id"])) {

    header("Location: manage_class_subject.php");
    exit();

}

$class_subject_id = $_GET["class_subject_id"];

// =========================================
// FETCH CURRENT RECORD
// =========================================

$sql = "SELECT *

        FROM class_subjects

        WHERE class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $class_subject_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    die("Record not found.");

}

$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// FETCH CLASSES
// =========================================

$class_sql = "SELECT

                c.class_id,
                c.year_of_study,
                c.section,
                d.department_name

              FROM classes c

              INNER JOIN departments d

              ON c.department_id = d.department_id

              ORDER BY

                d.department_name,

                c.year_of_study,

                c.section";

$class_result = mysqli_query($conn, $class_sql);

// =========================================
// FETCH SUBJECTS
// =========================================

$subject_sql = "SELECT *

                FROM subjects

                ORDER BY subject_code";

$subject_result = mysqli_query($conn, $subject_sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Class Subject</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="form-card">

<div class="page-header">

<a href="manage_class_subject.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Update Class Subject

</h1>

</div>

<form

action="update_class_subject_process.php"

method="POST"

class="student-form">

<input

type="hidden"

name="class_subject_id"

value="<?php echo $class_subject_id; ?>">

<div class="form-grid">

<!-- CLASS -->

<div class="form-group">

<label>

Class <span class="required">*</span>

</label>

<select

name="class_id"

required>

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

if($class["class_id"]==$data["class_id"])

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

<!-- SUBJECT -->

<div class="form-group">

<label>

Subject <span class="required">*</span>

</label>

<select

name="subject_id"

required>

<?php

while($subject=mysqli_fetch_assoc($subject_result)){

?>

<option

value="<?php echo $subject["subject_id"]; ?>"

<?php

if($subject["subject_id"]==$data["subject_id"])

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

</div>

<div class="form-buttons">

<button

type="submit"

class="save-btn">

Update

</button>

<a

href="manage_class_subject.php"

class="cancel-btn">

Cancel

</a>

</div>

</form>

</div>

</body>

</html>

<?php

mysqli_close($conn);

?>