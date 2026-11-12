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
// CHECK CLASS ID
// =========================================

if (!isset($_GET["class_id"])) {

    header("Location: manage_class.php");
    exit();

}

$class_id = $_GET["class_id"];

// =========================================
// FETCH CLASS
// =========================================

$sql = "SELECT *
        FROM classes
        WHERE class_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$class_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    header("Location: manage_class.php");
    exit();

}

$class = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// FETCH DEPARTMENTS
// =========================================

$department_result = mysqli_query($conn,

"SELECT department_id,department_name
FROM departments
ORDER BY department_name");

// =========================================
// FETCH SEMESTERS
// =========================================

$semester_result = mysqli_query($conn,

"SELECT semester_id,semester_name
FROM semesters
ORDER BY semester_id");

// =========================================
// FETCH ACADEMIC YEARS
// =========================================

$academic_result = mysqli_query($conn,

"SELECT academic_year_id,academic_year_name
FROM academic_years
ORDER BY academic_year_name DESC");

// =========================================
// FETCH TEACHERS OF SELECTED DEPARTMENT
// =========================================

$sql="SELECT teacher_id,teacher_name
      FROM teachers
      WHERE department_id=?
      ORDER BY teacher_name";

$stmt=mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

$stmt,

"i",

$class["department_id"]

);

mysqli_stmt_execute($stmt);

$teacher_result=mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Class</title>

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

Update Class

</h1>

</div>

<form

action="update_class_process.php"

method="POST">

<input

type="hidden"

name="class_id"

value="<?php echo $class["class_id"]; ?>">

<!-- Department -->

<div class="form-group">

<label>

Department

</label>

<select

name="department_id"

id="department"

required>

<?php

while($department=mysqli_fetch_assoc($department_result)){

?>

<option

value="<?php echo $department["department_id"]; ?>"

<?php

if($department["department_id"]==$class["department_id"])

echo "selected";

?>>

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

<?php

for($i=1;$i<=4;$i++){

?>

<option

value="<?php echo $i;?>"

<?php

if($i==$class["year_of_study"])

echo "selected";

?>>

<?php

echo $i;

?>

</option>

<?php

}

?>

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

<?php

$sections=["A","B","C","D"];

foreach($sections as $section){

?>

<option

value="<?php echo $section;?>"

<?php

if($section==$class["section"])

echo "selected";

?>>

<?php echo $section;?>

</option>

<?php

}

?>

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

<?php

while($semester=mysqli_fetch_assoc($semester_result)){

?>

<option

value="<?php echo $semester["semester_id"]; ?>"

<?php

if($semester["semester_id"]==$class["semester_id"])

echo "selected";

?>>

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

<?php

while($year=mysqli_fetch_assoc($academic_result)){

?>

<option

value="<?php echo $year["academic_year_id"]; ?>"

<?php

if($year["academic_year_id"]==$class["academic_year_id"])

echo "selected";

?>>

<?php echo htmlspecialchars($year["academic_year_name"]); ?>

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

<?php

while($teacher=mysqli_fetch_assoc($teacher_result)){

?>

<option

value="<?php echo $teacher["teacher_id"]; ?>"

<?php

if($teacher["teacher_id"]==$class["advisor_teacher_id"])

echo "selected";

?>>

<?php echo htmlspecialchars($teacher["teacher_name"]); ?>

</option>

<?php

}

?>

</select>

</div>

<div class="form-buttons">

<button

type="submit"

class="save-btn">

Update

</button>

<a

href="manage_class.php"

class="cancel-btn">

Cancel

</a>

</div>

</form>

</div>

<script>

document.getElementById("department").addEventListener("change",function(){

let department=this.value;

fetch("get_teachers.php?department_id="+department)

.then(response=>response.text())

.then(data=>{

document.getElementById("advisor").innerHTML=data;

});

});

</script>

</body>

</html>

<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>