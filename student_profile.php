<?php

// =========================================
// START SESSION
// =========================================

session_start();

require "database.php";

// =========================================
// CHECK ADMIN LOGIN
// =========================================

if(!isset($_SESSION["admin_id"])){

    header("Location: login.php");
    exit();

}

// =========================================
// GET STUDENT ID
// =========================================

if(!isset($_GET["student_id"])){

    die("Student ID Missing.");

}

$student_id = intval($_GET["student_id"]);

// =========================================
// FETCH STUDENT DETAILS
// =========================================

$sql = "SELECT

            s.*,
            u.register_number,
            u.email,
            g.gender_name,
            d.department_name,
            p.program_name,
            y.year_name

        FROM students s

        INNER JOIN user u
        ON s.user_id=u.user_id

        LEFT JOIN genders g
        ON s.gender_id=g.gender_id

        LEFT JOIN departments d
        ON s.department_id=d.department_id

        LEFT JOIN programs p
        ON s.program_id=p.program_id

        LEFT JOIN years y
        ON s.year_id=y.year_id

        WHERE s.student_id='$student_id'";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0){

    die("Student Not Found");

}

$student = mysqli_fetch_assoc($result);

// =========================================
// FETCH PROFILE PHOTO
// document_type_id = 1
// =========================================

$student_photo = "default.png";

$sql = "SELECT file_path

        FROM student_documents

        WHERE student_id='$student_id'

        AND document_type_id='1'

        LIMIT 1";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

    $photo = mysqli_fetch_assoc($result);

    $student_photo = $photo["file_path"];

}

// =========================================
// FETCH ADDRESS
// =========================================

$sql = "SELECT *

        FROM student_addresses

        WHERE student_id='$student_id'";

$result_address = mysqli_query($conn,$sql);

$address = mysqli_fetch_assoc($result_address);

// =========================================
// FETCH FAMILY DETAILS
// =========================================

$sql = "SELECT

            fd.*,
            r.relationship_name

        FROM family_details fd

        LEFT JOIN relationships r

        ON fd.relationship_id=r.relationship_id

        WHERE fd.student_id='$student_id'";

$result_family = mysqli_query($conn,$sql);

// =========================================
// FETCH ACADEMIC DETAILS
// =========================================

$sql = "SELECT

            ad.*,
            b.board_name

        FROM academic_details ad

        LEFT JOIN boards b

        ON ad.board_id=b.board_id

        WHERE ad.student_id='$student_id'

        ORDER BY ad.academic_id";

$result_academic = mysqli_query($conn,$sql);

// =========================================
// FETCH DOCUMENTS
// =========================================

$sql = "SELECT

            sd.*,
            dt.document_name

        FROM student_documents sd

        INNER JOIN document_types dt

        ON sd.document_type_id=dt.document_type_id

        WHERE sd.student_id='$student_id'

        ORDER BY sd.document_type_id";

$result_documents = mysqli_query($conn,$sql);


// =========================================
// FETCH ACADEMIC DETAILS
// =========================================

$sql = "SELECT
            ad.*,
            b.board_name

        FROM academic_details ad

        LEFT JOIN boards b
        ON ad.board_id = b.board_id

        WHERE ad.student_id='$student_id'

        ORDER BY ad.academic_id ASC";

$result_academic = mysqli_query($conn,$sql);

// =========================================
// FETCH ADDRESS
// =========================================

$sql = "SELECT *

        FROM student_addresses

        WHERE student_id='$student_id'

        LIMIT 1";

$result_address = mysqli_query($conn,$sql);

$address = mysqli_fetch_assoc($result_address);

// =========================================
// FETCH FAMILY DETAILS
// =========================================

$sql = "SELECT
            fd.*,
            r.relationship_name

        FROM family_details fd

        LEFT JOIN relationships r

        ON fd.relationship_id = r.relationship_id

        WHERE fd.student_id='$student_id'

        ORDER BY fd.family_id ASC";

$result_family = mysqli_query($conn,$sql);

// =========================================
// FETCH DOCUMENTS
// =========================================

$sql = "SELECT
            sd.*,
            dt.document_name

        FROM student_documents sd

        LEFT JOIN document_types dt

        ON sd.document_type_id = dt.document_type_id

        WHERE sd.student_id='$student_id'

        ORDER BY dt.document_name";

$result_documents = mysqli_query($conn,$sql);

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Student Profile

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

</head>

<body>

<div class="container">

<!-- ========================================= -->
<!-- PROFILE HEADER -->
<!-- ========================================= -->

<div class="teacher-profile-header">

    <div class="teacher-profile-photo">

        <img

        src="uploads/student/photos/<?php echo htmlspecialchars($student_photo); ?>"

        class="profile-photo"

        alt="Student Photo">

    </div>

    <div class="teacher-basic-details">

        <h1>

            <?php echo htmlspecialchars($student["student_name"]); ?>

        </h1>

        <h2>

            <?php echo htmlspecialchars($student["program_name"]); ?>

        </h2>

        <h3>

            <?php echo htmlspecialchars($student["department_name"]); ?>

        </h3>

        <div class="employee-id">

            Register Number :
            <?php echo htmlspecialchars($student["register_number"]); ?>

        </div>

    </div>

</div>

<!-- ========================================= -->
<!-- NAVIGATION -->
<!-- ========================================= -->

<div class="teacher-tabs">

<a href="#personal">Personal</a>

<a href="#academic">Academic</a>

<a href="#education">Education</a>

<a href="#address">Address</a>

<a href="#family">Family</a>

<a href="#documents">Documents</a>

</div>

<!-- ========================================= -->
<!-- PERSONAL INFORMATION -->
<!-- ========================================= -->

<div class="profile-section" id="personal">

<h2>

Personal Information

</h2>

<table class="profile-table">

<tr>

<th width="25%">

Register Number

</th>

<td>

<?php echo htmlspecialchars($student["register_number"]); ?>

</td>

</tr>

<tr>

<th>

Student Name

</th>

<td>

<?php echo htmlspecialchars($student["student_name"]); ?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?php echo htmlspecialchars($student["email"]); ?>

</td>

</tr>

<tr>

<th>

Phone

</th>

<td>

<?php echo htmlspecialchars($student["phone"]); ?>

</td>

</tr>

<tr>

<th>

Gender

</th>

<td>

<?php echo htmlspecialchars($student["gender_name"]); ?>

</td>

</tr>

<tr>

<th>

Date of Birth

</th>

<td>

<?php echo htmlspecialchars($student["date_of_birth"]); ?>

</td>

</tr>

</table>

</div>

<!-- ========================================= -->
<!-- ACADEMIC INFORMATION -->
<!-- ========================================= -->

<div class="profile-section" id="academic">

<h2>

Academic Information

</h2>

<table class="profile-table">

<tr>

<th width="25%">

Program

</th>

<td>

<?php echo htmlspecialchars($student["program_name"]); ?>

</td>

</tr>

<tr>

<th>

Department

</th>

<td>

<?php echo htmlspecialchars($student["department_name"]); ?>

</td>

</tr>

<tr>

<th>

Year

</th>

<td>

<?php echo htmlspecialchars($student["year_name"]); ?>

</td>

</tr>

</table>

</div>
<!-- ========================================= -->
<!-- EDUCATIONAL DETAILS -->
<!-- ========================================= -->

<div class="profile-section" id="education">

<h2>

Educational Details

</h2>

<table class="profile-table">

    <thead>

        <tr>

            <th>Qualification</th>

            <th>School / College</th>

            <th>Board</th>

            <th>Percentage</th>

        </tr>

    </thead>

    <tbody>

<?php

if(mysqli_num_rows($result_academic)>0){

    while($row=mysqli_fetch_assoc($result_academic)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["qualification"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["school_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["board_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["percentage"]); ?> %

</td>

</tr>

<?php

    }

}

else{

?>

<tr>

<td colspan="4">

No Academic Details Available

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<!-- ========================================= -->
<!-- ADDRESS DETAILS -->
<!-- ========================================= -->

<div class="profile-section" id="address">

<h2>

Address Details

</h2>

<table class="profile-table">

<tr>

<th width="25%">

Permanent Address

</th>

<td>

<?php

echo !empty($address["permanent_address"])

? nl2br(htmlspecialchars($address["permanent_address"]))

: "Not Available";

?>

</td>

</tr>

<tr>

<th>

Temporary Address

</th>

<td>

<?php

echo !empty($address["temporary_address"])

? nl2br(htmlspecialchars($address["temporary_address"]))

: "Not Available";

?>

</td>

</tr>

</table>

</div>

<!-- ========================================= -->
<!-- FAMILY DETAILS -->
<!-- ========================================= -->

<div class="profile-section" id="family">

<h2>

Family Details

</h2>

<table class="profile-table">

<thead>

<tr>

<th>Relationship</th>

<th>Name</th>

<th>Occupation</th>

<th>Phone</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result_family)>0){

    while($family=mysqli_fetch_assoc($result_family)){

?>

<tr>

<td>

<?php echo htmlspecialchars($family["relationship_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($family["name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($family["occupation"]); ?>

</td>

<td>

<?php echo htmlspecialchars($family["phone"]); ?>

</td>

</tr>

<?php

    }

}

else{

?>

<tr>

<td colspan="4">

No Family Details Available

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>
<!-- ========================================= -->
<!-- DOCUMENTS -->
<!-- ========================================= -->

<div class="profile-section" id="documents">

    <h2>Documents</h2>

    <table class="profile-table">

        <thead>

            <tr>

                <th>Document Name</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

<?php

if(mysqli_num_rows($result_documents)>0){

    while($row=mysqli_fetch_assoc($result_documents)){

        // =========================================
        // SELECT FOLDER
        // =========================================

        if($row["document_type_id"] == 1){

    $folder = "photos/";

}
elseif($row["document_type_id"] == 2){

    $folder = "tenth_marksheets/";

}
elseif($row["document_type_id"] == 3){

    $folder = "twelfth_marksheets/";

}
elseif($row["document_type_id"] == 4){

    $folder = "aadhar_cards/";

}
elseif($row["document_type_id"] == 5){

    $folder = "transfer_certificates/";

}
elseif($row["document_type_id"] == 6){

    $folder = "community_certificates/";

}
else{

    $folder = "";

}

?>

<tr>

    <td>

        <?php echo htmlspecialchars($row["document_name"]); ?>

    </td>

    <td>

        <a

        href="uploads/student/<?php echo $folder . htmlspecialchars($row["file_path"]); ?>"

        target="_blank"

        class="view-btn">

            View

        </a>

    </td>

</tr>

<?php

    }

}

else{

?>

<tr>

    <td colspan="2">

        No Documents Uploaded

    </td>

</tr>

<?php

}

?>

        </tbody>

    </table>

</div>

<!-- ========================================= -->
<!-- BUTTONS -->
<!-- ========================================= -->

<!-- ========================================= -->
<!-- BUTTONS -->
<!-- ========================================= -->

<div class="dashboard-buttons">

<a

href="update_profile.php?student_id=<?php echo $student_id; ?>"

class="dashboard-btn blue">

Update Profile

</a>

<a

href="manage_student.php"

class="dashboard-btn green">

Back to Students

</a>

</div>

</div>

</body>

</html>

<?php

mysqli_close($conn);

?>