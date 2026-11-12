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
// FETCH CLASSES
// =========================================

$class_sql = "SELECT

                c.class_id,
                c.year_of_study,
                c.section,
                d.department_name

              FROM classes c

              INNER JOIN departments d

              ON c.department_id=d.department_id

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

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Assign Subject to Class

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="form-card">

<!-- ========================================= -->
<!-- HEADER -->
<!-- ========================================= -->

<div class="page-header">

<a href="manage_class_subject.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Assign Subject to Class

</h1>

</div>

<!-- ========================================= -->
<!-- FORM -->
<!-- ========================================= -->

<form

action="add_class_subject_process.php"

method="POST"

class="student-form">

<div class="form-grid">

<!-- CLASS -->

<div class="form-group">

<label>

Class 

</label>

<select

name="class_id"

required>

<option value="">

Select Class

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

value="<?php echo $class["class_id"]; ?>">

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

Subject 

</label>

<select

name="subject_id"

required>

<option value="">

Select Subject

</option>

<?php

while($subject=mysqli_fetch_assoc($subject_result)){

?>

<option

value="<?php echo $subject["subject_id"]; ?>">

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

<!-- BUTTONS -->

<div class="form-buttons">

<button

type="submit"

class="save-btn">

Save

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