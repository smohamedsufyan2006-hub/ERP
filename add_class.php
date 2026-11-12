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
// FETCH DEPARTMENTS
// =========================================

$department_result = mysqli_query($conn,

"SELECT department_id, department_name
FROM departments
ORDER BY department_name");

// =========================================
// FETCH SEMESTERS
// =========================================

$semester_result = mysqli_query($conn,

"SELECT semester_id, semester_name
FROM semesters
ORDER BY semester_id");

// =========================================
// FETCH ACADEMIC YEARS
// =========================================

$academic_result = mysqli_query($conn,

"SELECT academic_year_id, academic_year_name
FROM academic_years
ORDER BY academic_year_name DESC");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Add Class

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="form-card">

<div class="page-header">

<a href="manage_class.php"
class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Add Class

</h1>

</div>

<form

action="add_class_process.php"

method="POST">

<!-- Department -->

<div class="form-group">

<label>

Department

</label>

<select

name="department_id"

id="department"

required>

<option value="">

Select Department

</option>

<?php

while($department=mysqli_fetch_assoc($department_result)){

?>

<option
value="<?php echo $department["department_id"]; ?>">

<?php echo htmlspecialchars($department["department_name"]); ?>

</option>

<?php

}

?>

</select>

</div>

<!-- Year -->

<div class="form-group">

<label>

Year of Study

</label>

<select

name="year_of_study"

required>

<option value="">

Select Year

</option>

<option value="1">First Year</option>

<option value="2">Second Year</option>

<option value="3">Third Year</option>

<option value="4">Fourth Year</option>

</select>

</div>

<!-- Section -->

<div class="form-group">

<label>

Section

</label>

<select

name="section"

required>

<option value="">Select Section</option>

<option>A</option>

<option>B</option>

<option>C</option>

<option>D</option>

</select>

</div>

<!-- Semester -->

<div class="form-group">

<label>

Semester

</label>

<select

name="semester_id"

required>

<option value="">

Select Semester

</option>

<?php

while($semester=mysqli_fetch_assoc($semester_result)){

?>

<option
value="<?php echo $semester["semester_id"]; ?>">

<?php echo htmlspecialchars($semester["semester_name"]); ?>

</option>

<?php

}

?>

</select>

</div>

<!-- Academic Year -->

<div class="form-group">

<label>

Academic Year

</label>

<select

name="academic_year_id"

required>

<option value="">

Select Academic Year

</option>

<?php

while($academic=mysqli_fetch_assoc($academic_result)){

?>

<option
value="<?php echo $academic["academic_year_id"]; ?>">

<?php echo htmlspecialchars($academic["academic_year_name"]); ?>

</option>

<?php

}

?>

</select>

</div>

<!-- Advisor -->

<div class="form-group">

<label>

Class Advisor

</label>

<select

name="advisor_teacher_id"

id="advisor"

required>

<option value="">

Select Department First

</option>

</select>

</div>

<!-- Buttons -->

<div class="form-buttons">

    <button type="submit" class="save-btn">

        Save

    </button>

    <a href="manage_class.php" class="cancel-btn">

        Cancel

    </a>

</div>
</form>

</div>

<script>

document.getElementById("department").addEventListener("change", function(){

let department_id=this.value;

fetch("get_teachers.php?department_id="+department_id)

.then(response=>response.text())

.then(data=>{

document.getElementById("advisor").innerHTML=data;

});

});

</script>

</body>

</html>

<?php

mysqli_close($conn);

?>