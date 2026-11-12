<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK LOGIN
// =========================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: login.php");
    exit();

}

// =========================================
// GET TEACHER ID
// =========================================

$teacher_id = $_SESSION["teacher_id"];

// =========================================
// FETCH TEACHER DETAILS
// =========================================

$sql = "SELECT *
        FROM teachers
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$teacher = mysqli_fetch_assoc($result);

// =========================================
// FETCH ADDRESS
// =========================================

$sql = "SELECT *
        FROM teacher_addresses
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$addressResult = mysqli_stmt_get_result($stmt);

$address = mysqli_fetch_assoc($addressResult);
// =========================================
// FETCH EMERGENCY CONTACT
// =========================================

$sql = "SELECT *
        FROM teacher_emergency_contacts
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$emergencyResult = mysqli_stmt_get_result($stmt);

$emergency = mysqli_fetch_assoc($emergencyResult);
// =========================================
// FETCH DOCUMENTS
// =========================================

// =========================================
// FETCH ALL TEACHER DOCUMENTS
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        td.*,
        dt.document_name

     FROM teacher_documents td

     LEFT JOIN document_types dt
     ON td.document_type_id = dt.document_type_id

     WHERE td.teacher_id = ?

     ORDER BY dt.document_name"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_id

);

mysqli_stmt_execute($stmt);

$documents_result = mysqli_stmt_get_result($stmt);

$documents = [];

while($row = mysqli_fetch_assoc($documents_result)){

    $documents[$row["document_type_id"]] = $row;

}

mysqli_stmt_close($stmt);
// =========================================
// FETCH QUALIFICATIONS
// =========================================

$sql = "SELECT *
        FROM teacher_qualifications
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$qualificationResult = mysqli_stmt_get_result($stmt);

// =========================================
// FETCH EXPERIENCE
// =========================================

$sql = "SELECT *
        FROM teacher_experience
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$experienceResult = mysqli_stmt_get_result($stmt);

// =========================================
// FETCH DROPDOWNS
// =========================================

$genderResult = mysqli_query($conn,
"SELECT * FROM genders");

$departmentResult = mysqli_query($conn,
"SELECT * FROM departments");

$designationResult = mysqli_query($conn,
"SELECT * FROM designations");

$staffCategoryResult = mysqli_query($conn,
"SELECT * FROM staff_categories");

$statusResult = mysqli_query($conn,
"SELECT * FROM teacher_status");

$qualificationDropdown = mysqli_query($conn,
"SELECT * FROM qualifications");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Update Teacher Profile</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h1>

Update Teacher Profile

</h1>

<form
action="teacher_update_process.php"
method="POST"
enctype="multipart/form-data">

<!-- ========================================= -->
<!-- PERSONAL DETAILS -->
<!-- ========================================= -->

<div class="form-card">

<h2>

Personal Details

</h2>

<div class="row">

<div class="input-group">

<label>

Employee ID

</label>

<input
type="text"
name="employee_id"
value="<?php echo htmlspecialchars($teacher["employee_id"]); ?>"
required>

</div>

<div class="input-group">

<label>

Teacher Name

</label>

<input
type="text"
name="teacher_name"
value="<?php echo htmlspecialchars($teacher["teacher_name"]); ?>"
required>

</div>

</div>

<div class="row">

<div class="input-group">

<label>

Gender

</label>

<select
name="gender_id"
required>

<option value="">Select Gender</option>

<?php

while($gender = mysqli_fetch_assoc($genderResult)){

?>

<option
value="<?php echo $gender["gender_id"]; ?>"

<?php

if($teacher["gender_id"] == $gender["gender_id"])
echo "selected";

?>

>

<?php echo $gender["gender_name"]; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="input-group">

<label>

Date of Birth

</label>

<input
type="date"
name="date_of_birth"
value="<?php echo $teacher["date_of_birth"]; ?>">

</div>

</div>

<div class="row">

<div class="input-group">

<label>

Phone Number

</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($teacher["phone"]); ?>">

</div>

<div class="input-group">

<label>

Joining Date

</label>

<input
type="date"
name="joining_date"
value="<?php echo $teacher["joining_date"]; ?>">

</div>

</div>

<div class="row">

<div class="input-group">

<label>

Department

</label>

<select
name="department_id"
required>

<option value="">Select Department</option>

<?php

while($department = mysqli_fetch_assoc($departmentResult)){

?>

<option
value="<?php echo $department["department_id"]; ?>"

<?php

if($teacher["department_id"] == $department["department_id"])
echo "selected";

?>

>

<?php echo $department["department_name"]; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="input-group">

<label>

Designation

</label>

<select
name="designation_id"
required>

<option value="">Select Designation</option>

<?php

while($designation = mysqli_fetch_assoc($designationResult)){

?>

<option
value="<?php echo $designation["designation_id"]; ?>"

<?php

if($teacher["designation_id"] == $designation["designation_id"])
echo "selected";

?>

>

<?php echo $designation["designation_name"]; ?>

</option>

<?php

}

?>

</select>

</div>

</div>

<div class="row">

<div class="input-group">

<label>

Staff Category

</label>

<select
name="staff_category_id"
required>

<option value="">Select Category</option>

<?php

while($category = mysqli_fetch_assoc($staffCategoryResult)){

?>

<option
value="<?php echo $category["staff_category_id"]; ?>"

<?php

if($teacher["staff_category_id"] == $category["staff_category_id"])
echo "selected";

?>

>

<?php echo $category["staff_category_name"]; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="input-group">

<label>

Status

</label>

<select
name="status_id"
required>

<option value="">Select Status</option>

<?php

while($status = mysqli_fetch_assoc($statusResult)){

?>

<option
value="<?php echo $status["status_id"]; ?>"

<?php

if($teacher["status_id"] == $status["status_id"])
echo "selected";

?>

>

<?php echo $status["status_name"]; ?>

</option>

<?php

}

?>

</select>

</div>

</div>

</div>
<br>
<br>
<!-- ========================================= -->
<!-- ADDRESS -->
<!-- ========================================= -->

<div class="form-card">

<h2>

Address

</h2>

<label>

Permanent Address

</label>

<textarea
name="permanent_address"
rows="4"><?php echo htmlspecialchars($address["permanent_address"] ?? ""); ?></textarea>

<label>

Temporary Address

</label>

<textarea
name="temporary_address"
rows="4"><?php echo htmlspecialchars($address["temporary_address"] ?? ""); ?></textarea>

</div>
<br>
<br>
<!-- ========================================= -->
<!-- EMERGENCY CONTACT -->
<!-- ========================================= -->

<div class="form-card">

<h2>

Emergency Contact

</h2>

<div class="row">

<div class="input-group">

<label>

Emergency Contact Name

</label>

<input
type="text"
name="emergency_name"
value="<?php echo htmlspecialchars($emergency["emergency_name"] ?? ""); ?>">

</div>

<div class="input-group">

<label>

Relationship

</label>

<select name="relationship_id">

<option value="">Select Relationship</option>

<?php

$relationshipResult = mysqli_query($conn,"SELECT * FROM relationships");

while($relationship = mysqli_fetch_assoc($relationshipResult)){

?>

<option
value="<?php echo $relationship["relationship_id"]; ?>"

<?php

if(($emergency["relationship_id"] ?? "") == $relationship["relationship_id"])
echo "selected";

?>

>

<?php echo $relationship["relationship_name"]; ?>

</option>

<?php

}

?>

</select>

</div>

</div>

<div class="row">

<div class="input-group">

<label>

Emergency Phone Number

</label>

<input
type="text"
name="emergency_phone"
value="<?php echo htmlspecialchars($emergency["emergency_phone"] ?? ""); ?>">

</div>

</div>

</div>

<br><br>
<!-- ========================================= -->
<!-- QUALIFICATIONS -->
<!-- ========================================= -->

<div class="form-card">

<h2>

Qualifications

</h2>
<br>

<table class="dynamic-table">

<thead>



<th>Please click add Qualification if you have an extra qualifications to add</th>



</thead>

<tbody id="qualification_container">

<?php

$count = 1;

while($qualification = mysqli_fetch_assoc($qualificationResult)){

?>

<tr>

<td>

<?php echo $count++; ?>

</td>

<td>

<select name="qualification_id[]" required>

<option value="">Select Qualification</option>

<?php

$result = mysqli_query($conn,"SELECT * FROM qualifications");

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["qualification_id"]; ?>"

<?php

if($qualification["qualification_id"] == $row["qualification_id"])
echo "selected";

?>

>

<?php echo $row["qualification_name"]; ?>

</option>

<?php

}

?>

</select>

</td>

<td>

<input
type="text"
name="specialization[]"
value="<?php echo htmlspecialchars($qualification["specialization"]); ?>">

</td>

<td>

<input
type="text"
name="institution[]"
value="<?php echo htmlspecialchars($qualification["institution"]); ?>">

</td>

<td>

<input
type="text"
name="university[]"
value="<?php echo htmlspecialchars($qualification["university"]); ?>">

</td>

<td>

<input
type="number"
name="year_of_passing[]"
value="<?php echo htmlspecialchars($qualification["year_of_passing"]); ?>">

</td>

<td>

<input
type="text"
name="percentage_or_cgpa[]"
value="<?php echo htmlspecialchars($qualification["percentage_or_cgpa"]); ?>">

</td>

<td>

<button
type="button"
class="remove-btn"
onclick="removeRow(this)">

Remove

</button>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<button
type="button"
class="dashboard-btn blue"
onclick="addQualification()">

+ Add Qualification

</button>

</div>
<br>
<br>
<!-- ========================================= -->
<!-- EXPERIENCE -->
<!-- ========================================= -->

<div class="form-card">

<h2>

Experience

</h2>

<table class="dynamic-table">
<br>
<thead>

<tr>

<th>Please click add Experience if you have an extra experience to add</th>

</tr>

</thead>

<tbody id="experience_container">

<?php

$count = 1;

while($experience = mysqli_fetch_assoc($experienceResult)){

?>

<tr>

<td>

<?php echo $count++; ?>

</td>

<td>

<input
type="text"
name="organization[]"
value="<?php echo htmlspecialchars($experience["organization"]); ?>">

</td>

<td>

<input
type="text"
name="designation[]"
value="<?php echo htmlspecialchars($experience["designation"]); ?>">

</td>

<td>

<input
type="date"
name="from_date[]"
value="<?php echo $experience["from_date"]; ?>">

</td>

<td>

<input
type="date"
name="to_date[]"
value="<?php echo $experience["to_date"]; ?>">

</td>

<td>

<input
type="text"
name="experience_type[]"
value="<?php echo htmlspecialchars($experience["experience_type"]); ?>">

</td>

<td>

<button
type="button"
class="remove-btn"
onclick="removeRow(this)">

Remove

</button>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<button
type="button"
class="dashboard-btn blue"
onclick="addExperience()">

+ Add Experience

</button>

</div>
<br>
<br>
<!-- ========================================= -->
<!-- PROFILE PHOTO -->
<!-- ========================================= -->

<!-- ========================================= -->
<!-- DOCUMENTS -->
<!-- ========================================= -->

<div class="form-card">

<h2>

Documents

</h2>

<div class="row">

<div class="input-group">

<label>

Passport Size Photo

</label>

<?php if(isset($documents[1])){ ?>

<p>

Current File :

<a
href="uploads/teachers/photos/<?php echo $documents[1]["file_name"]; ?>"
target="_blank">

View

</a>

</p>

<?php } ?>

<input type="file" name="photo">

</div>
<div class="input-group">

<label>

Aadhaar Card

</label>

<?php if(isset($documents[2])){ ?>

<p>

Current File :

<a
href="uploads/teachers/aadhar/<?php echo $documents[2]["file_name"]; ?>"
target="_blank">

View

</a>

</p>

<?php } ?>

<input type="file" name="aadhaar">

</div>
<div class="input-group">

<label>

PAN Card

</label>

<?php if(isset($documents[3])){ ?>

<p>

Current File :

<a
href="uploads/teachers/pan/<?php echo $documents[3]["file_name"]; ?>"
target="_blank">

View

</a>

</p>

<?php } ?>

<input type="file" name="pan">

</div>
<div class="input-group">

<label>

Degree Certificate

</label>

<?php if(isset($documents[4])){ ?>

<p>

Current File :

<a
href="uploads/teachers/degree/<?php echo $documents[4]["file_name"]; ?>"
target="_blank">

View

</a>

</p>

<?php } ?>

<input type="file" name="degree_certificate">

</div>
<div class="input-group">

<label>

Experience Certificate

</label>

<?php if(isset($documents[5])){ ?>

<p>

Current File :

<a
href="uploads/teachers/experience/<?php echo $documents[5]["file_name"]; ?>"
target="_blank">

View

</a>

</p>

<?php } ?>

<input type="file" name="experience_certificate">

</div>
<div class="input-group">

<label>

Other Document

</label>

<?php if(isset($documents[6])){ ?>

<p>

Current File :

<a
href="uploads/teachers/others/<?php echo $documents[6]["file_name"]; ?>"
target="_blank">

View

</a>

</p>

<?php } ?>

<input type="file" name="other_document">

</div>
</div>

</div>
<!-- ========================================= -->
<!-- CHANGE PASSWORD -->
<!-- ========================================= -->

<div class="form-card">

    <h2>Change Password</h2>

    <p class="note">
        Leave these fields blank if you do not want to change your password.
    </p>

    <div class="row">

        <div class="input-group">

            <label>Current Password</label>

            <input
            type="password"
            name="current_password"
            placeholder="Enter Current Password">

        </div>

        <div class="input-group">

            <label>New Password</label>

            <input
            type="password"
            name="new_password"
            placeholder="Enter New Password">

        </div>

        <div class="input-group">

            <label>Confirm New Password</label>

            <input
            type="password"
            name="confirm_password"
            placeholder="Confirm New Password">

        </div>

    </div>

</div>
<!-- ========================================= -->
<!-- BUTTONS -->
<!-- ========================================= -->

<div class="dashboard-buttons">

    <button type="submit" class="dashboard-btn green">
    Update Profile
</button>

<a href="teacher_dashboard.php" class="back-dashboard-btn">
    Back Dashboard
</a>

</div>
<!-- ========================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================= -->

<script>

// =========================================
// REMOVE ROW
// =========================================

function removeRow(button){

    button.parentNode.parentNode.remove();

}

// =========================================
// ADD QUALIFICATION
// =========================================

function addQualification(){

    let table = document.getElementById("qualification_container");

    let row = table.insertRow();

    row.innerHTML = `

        <td></td>

        <td>

            <select name="qualification_id[]" required>

                <option value="">Select Qualification</option>

                <?php

                mysqli_data_seek($qualificationDropdown,0);

                while($row=mysqli_fetch_assoc($qualificationDropdown)){

                ?>

                <option value="<?php echo $row["qualification_id"]; ?>">

                    <?php echo $row["qualification_name"]; ?>

                </option>

                <?php

                }

                ?>

            </select>

        </td>

        <td>

            <input
            type="text"
            name="specialization[]">

        </td>

        <td>

            <input
            type="text"
            name="institution[]">

        </td>

        <td>

            <input
            type="text"
            name="university[]">

        </td>

        <td>

            <input
            type="number"
            name="year_of_passing[]">

        </td>

        <td>

            <input
            type="text"
            name="percentage_or_cgpa[]">

        </td>

        <td>

            <button
            type="button"
            class="remove-btn"
            onclick="removeRow(this)">

            Remove

            </button>

        </td>

    `;

    updateQualificationNumbers();

}

// =========================================
// ADD EXPERIENCE
// =========================================

function addExperience(){

    let table = document.getElementById("experience_container");

    let row = table.insertRow();

    row.innerHTML = `

        <td></td>

        <td>

            <input
            type="text"
            name="organization[]">

        </td>

        <td>

            <input
            type="text"
            name="designation[]">

        </td>

        <td>

            <input
            type="date"
            name="from_date[]">

        </td>

        <td>

            <input
            type="date"
            name="to_date[]">

        </td>

        <td>

            <input
            type="text"
            name="experience_type[]">

        </td>

        <td>

            <button
            type="button"
            class="remove-btn"
            onclick="removeRow(this)">

            Remove

            </button>

        </td>

    `;

    updateExperienceNumbers();

}

// =========================================
// UPDATE SERIAL NUMBERS
// =========================================

function updateQualificationNumbers(){

    let rows = document.querySelectorAll("#qualification_container tr");

    rows.forEach(function(row,index){

        row.cells[0].innerHTML = index + 1;

    });

}

function updateExperienceNumbers(){

    let rows = document.querySelectorAll("#experience_container tr");

    rows.forEach(function(row,index){

        row.cells[0].innerHTML = index + 1;

    });

}

updateQualificationNumbers();

updateExperienceNumbers();

</script>

</form>

</div>

</body>

</html>