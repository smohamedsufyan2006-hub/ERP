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
// FETCH CLASS SUBJECTS
// =========================================

$sql = "SELECT

            cs.class_subject_id,

            c.year_of_study,

            c.section,

            d.department_name,

            s.subject_code,

            s.subject_name

        FROM class_subjects cs

        INNER JOIN classes c
        ON cs.class_id = c.class_id

        INNER JOIN departments d
        ON c.department_id = d.department_id

        INNER JOIN subjects s
        ON cs.subject_id = s.subject_id

        WHERE cs.class_subject_id NOT IN
        (
            SELECT class_subject_id
            FROM teacher_class_subjects
        )

        ORDER BY

            d.department_name,

            c.year_of_study,

            c.section,

            s.subject_code";

$class_subject_result = mysqli_query($conn, $sql);

// =========================================
// FETCH TEACHERS
// =========================================

$sql = "SELECT

            teacher_id,

            teacher_name

        FROM teachers

        ORDER BY teacher_name";

$teacher_result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Assign Teacher

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

<a

href="manage_teacher_class_subject.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Assign Teacher to Class Subject

</h1>

</div>

<!-- ========================================= -->
<!-- FORM -->
<!-- ========================================= -->

<form

action="add_teacher_class_subject_process.php"

method="POST"

class="student-form">

<div class="form-grid">

<!-- CLASS SUBJECT -->

<div class="form-group">

<label>

Class Subject 
</label>

<select

name="class_subject_id"

required>

<option value="">

Select Class Subject

</option>

<?php

$roman = [

1=>"I",

2=>"II",

3=>"III",

4=>"IV"

];

while($row = mysqli_fetch_assoc($class_subject_result)){

?>

<option value="<?php echo $row["class_subject_id"]; ?>">

<?php

echo $roman[$row["year_of_study"]]." ";

echo htmlspecialchars($row["department_name"])." ";

echo htmlspecialchars($row["section"]);

echo " - ";

echo htmlspecialchars($row["subject_code"]);

echo " - ";

echo htmlspecialchars($row["subject_name"]);

?>

</option>

<?php

}

?>

</select>

</div>

<!-- TEACHER -->

<div class="form-group">

<label>

Teacher 

</label>

<select

name="teacher_id"

required>

<option value="">

Select Teacher

</option>

<?php

while($teacher = mysqli_fetch_assoc($teacher_result)){

?>

<option value="<?php echo $teacher["teacher_id"]; ?>">

<?php echo htmlspecialchars($teacher["teacher_name"]); ?>

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

href="manage_teacher_class_subject.php"

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