<?php

// =========================================
// START SESSION
// =========================================

session_start();

require "database.php";

// =========================================
// CHECK LOGIN
// =========================================

// =========================================
// CHECK ACCESS
// =========================================

// =========================================
// CHECK ACCESS
// =========================================

if (isset($_SESSION["admin_id"])) {

    if (!isset($_GET["teacher_id"])) {

        die("Teacher ID not found.");

    }

    $teacher_id = $_GET["teacher_id"];

} elseif (isset($_SESSION["teacher_id"])) {

    $teacher_id = $_SESSION["teacher_id"];

} else {

    header("Location: login.php");
    exit();

}
// =========================================
// FETCH TEACHER DETAILS
// =========================================

$sql = "SELECT
            t.*,
            g.gender_name,
            d.department_name,
            sc.staff_category_name,
            ds.designation_name,
            ts.status_name

        FROM teachers t

        LEFT JOIN genders g
        ON t.gender_id = g.gender_id

        LEFT JOIN departments d
        ON t.department_id = d.department_id

        LEFT JOIN staff_categories sc
        ON t.staff_category_id = sc.staff_category_id

        LEFT JOIN designations ds
        ON t.designation_id = ds.designation_id

        LEFT JOIN teacher_status ts
        ON t.status_id = ts.status_id

        WHERE t.teacher_id='$teacher_id'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result)==0){

    die("Teacher not found.");

}

$teacher = mysqli_fetch_assoc($result);

// =========================================
// FETCH PROFILE PHOTO
// document_type_id = 1
// =========================================

$teacher_photo = "default.png";

$sql = "SELECT file_name
        FROM teacher_documents
        WHERE teacher_id='$teacher_id'
        AND document_type_id='1'
        LIMIT 1";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

    $photo = mysqli_fetch_assoc($result);

    $teacher_photo = $photo["file_name"];

}

// =========================================
// FETCH ADDRESS
// =========================================

$sql = "SELECT *
        FROM teacher_addresses
        WHERE teacher_id='$teacher_id'";

$result_address = mysqli_query($conn,$sql);

$address = mysqli_fetch_assoc($result_address);

// =========================================
// FETCH EMERGENCY CONTACT
// =========================================

$sql = "SELECT *
        FROM teacher_emergency_contacts
        WHERE teacher_id='$teacher_id'";

$result_emergency = mysqli_query($conn,$sql);

$emergency = mysqli_fetch_assoc($result_emergency);

// =========================================
// FETCH QUALIFICATIONS
// =========================================

$sql = "SELECT
            tq.*,
            q.qualification_name

        FROM teacher_qualifications tq

        INNER JOIN qualifications q

        ON tq.qualification_id=q.qualification_id

        WHERE tq.teacher_id='$teacher_id'";

$result_qualification = mysqli_query($conn,$sql);

// =========================================
// FETCH EXPERIENCE
// =========================================

$sql = "SELECT *
        FROM teacher_experience
        WHERE teacher_id='$teacher_id'";

$result_experience = mysqli_query($conn,$sql);

// =========================================
// FETCH DOCUMENTS
// =========================================

$sql = "SELECT
            td.*,
            dt.document_name

        FROM teacher_documents td

        INNER JOIN document_types dt

        ON td.document_type_id=dt.document_type_id

        WHERE td.teacher_id='$teacher_id'";

$result_documents = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="admin.css">

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="container">

<!-- ========================================= -->
<!-- PAGE HEADER -->
<!-- ========================================= -->

<div class="page-header">

    <a href="manage_teacher.php"
       class="back-btn"
       title="Back">

        <i class="fa-solid fa-arrow-left"></i>

    </a>


    <h1>Teacher Profile</h1>

</div>
<br>
<br>


<!-- ========================================= -->
<!-- PROFILE HEADER -->
<!-- ========================================= -->

<div class="teacher-profile-header">

    <div class="teacher-profile-photo">

        <img 
src="uploads/teachers/photos/<?php echo htmlspecialchars($teacher_photo); ?>"
class="profile-photo">

    </div>

    <div class="teacher-basic-details">

        <h1>

            <?php echo htmlspecialchars($teacher["teacher_name"]); ?>

        </h1>

        <h2>

            <?php echo htmlspecialchars($teacher["designation_name"]); ?>

        </h2>

        <h3>

            <?php echo htmlspecialchars($teacher["department_name"]); ?>

        </h3>

        <div class="employee-id">

            Employee ID :
            <?php echo htmlspecialchars($teacher["employee_id"]); ?>

        </div>

    </div>

</div>

<!-- ========================================= -->
<!-- NAVIGATION -->
<!-- ========================================= -->

<div class="teacher-tabs">

    <a href="#personal">

        Personal

    </a>

    <a href="#address">

        Address

    </a>

    <a href="#emergency">

        Emergency

    </a>

    <a href="#qualification">

        Qualifications

    </a>

    <a href="#experience">

        Experience

    </a>

    <a href="#documents">

        Documents

    </a>

</div>
<!-- ========================================= -->
<!-- PERSONAL DETAILS -->
<!-- ========================================= -->

<div class="profile-section" id="personal">

    <h2>Personal Details</h2>

    <table class="profile-table">

        <tr>

            <th>Teacher ID</th>

            <td><?php echo htmlspecialchars($teacher["employee_id"]); ?></td>

            <th>Teacher Name</th>

            <td><?php echo htmlspecialchars($teacher["teacher_name"]); ?></td>

        </tr>

        <tr>

            <th>Gender</th>

            <td><?php echo htmlspecialchars($teacher["gender_name"]); ?></td>

            <th>Date of Birth</th>

            <td><?php echo htmlspecialchars($teacher["date_of_birth"]); ?></td>

        </tr>

        <tr>

            <th>Phone Number</th>

            <td><?php echo htmlspecialchars($teacher["phone"]); ?></td>

            <th>Department</th>

            <td><?php echo htmlspecialchars($teacher["department_name"]); ?></td>

        </tr>

        <tr>

            <th>Designation</th>

            <td><?php echo htmlspecialchars($teacher["designation_name"]); ?></td>

            <th>Staff Category</th>

            <td><?php echo htmlspecialchars($teacher["staff_category_name"]); ?></td>

        </tr>

        <tr>

            <th>Joining Date</th>

            <td><?php echo htmlspecialchars($teacher["joining_date"]); ?></td>

            <th>Status</th>

            <td><?php echo htmlspecialchars($teacher["status_name"]); ?></td>

        </tr>

    </table>

</div>

<!-- ========================================= -->
<!-- ADDRESS DETAILS -->
<!-- ========================================= -->

<div class="profile-section" id="address">

    <h2>Address Details</h2>

    <table class="profile-table">

        <tr>

            <th width="25%">Permanent Address</th>

            <td>

                <?php

                echo !empty($address["permanent_address"])

                ? nl2br(htmlspecialchars($address["permanent_address"]))

                : "Not Available";

                ?>

            </td>

        </tr>

        <tr>

            <th>Temporary Address</th>

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
<!-- EMERGENCY CONTACT -->
<!-- ========================================= -->

<div class="profile-section" id="emergency">

    <h2>Emergency Contact</h2>

    <table class="profile-table">

        <tr>

            <th>Contact Person</th>

            <td>

                <?php

                echo !empty($emergency["contact_name"])

                ? htmlspecialchars($emergency["contact_name"])

                : "Not Available";

                ?>

            </td>

        </tr>

        <tr>

            <th>Relationship</th>

            <td>

                <?php

                echo !empty($emergency["relationship"])

                ? htmlspecialchars($emergency["relationship"])

                : "Not Available";

                ?>

            </td>

        </tr>

        <tr>

            <th>Phone Number</th>

            <td>

                <?php

                echo !empty($emergency["phone"])

                ? htmlspecialchars($emergency["phone"])

                : "Not Available";

                ?>

            </td>

        </tr>

    </table>

</div>
<!-- =========================================
QUALIFICATIONS
========================================= -->

<div class="profile-section" id="qualification">

    <h2>Qualifications</h2>

    <table class="profile-table">

        <thead>

            <tr>

                <th>Qualification</th>

                <th>Specialization</th>

                <th>Institution</th>

                <th>University</th>

                <th>Year</th>

                <th>CGPA / %</th>

            </tr>

        </thead>

        <tbody>

        <?php

        if(mysqli_num_rows($result_qualification)>0){

            while($row=mysqli_fetch_assoc($result_qualification)){

        ?>

            <tr>

                <td><?php echo htmlspecialchars($row["qualification_name"]); ?></td>

                <td><?php echo htmlspecialchars($row["specialization"]); ?></td>

                <td><?php echo htmlspecialchars($row["institution"]); ?></td>

                <td><?php echo htmlspecialchars($row["university"]); ?></td>

                <td><?php echo htmlspecialchars($row["year_of_passing"]); ?></td>

                <td><?php echo htmlspecialchars($row["percentage_or_cgpa"]); ?></td>

            </tr>

        <?php

            }

        }

        else{

        ?>

            <tr>

                <td colspan="6">

                    No Qualification Details Available

                </td>

            </tr>

        <?php

        }

        ?>

        </tbody>

    </table>

</div>

<!-- =========================================
EXPERIENCE
========================================= -->

<div class="profile-section" id="experience">

    <h2>Experience</h2>

    <table class="profile-table">

        <thead>

            <tr>

                <th>Organization</th>

                <th>Designation</th>

                <th>From</th>

                <th>To</th>

                <th>Experience Type</th>

            </tr>

        </thead>

        <tbody>

        <?php

        if(mysqli_num_rows($result_experience)>0){

            while($row=mysqli_fetch_assoc($result_experience)){

        ?>

            <tr>

                <td><?php echo htmlspecialchars($row["organization"]); ?></td>

                <td><?php echo htmlspecialchars($row["designation"]); ?></td>

                <td><?php echo htmlspecialchars($row["from_date"]); ?></td>

                <td><?php echo htmlspecialchars($row["to_date"]); ?></td>

                <td><?php echo htmlspecialchars($row["experience_type"]); ?></td>

            </tr>

        <?php

            }

        }

        else{

        ?>

            <tr>

                <td colspan="5">

                    No Experience Details Available

                </td>

            </tr>

        <?php

        }

        ?>

        </tbody>

    </table>

</div>

<!-- =========================================
DOCUMENTS
========================================= -->

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


        // Select folder based on document type

        // Select folder based on document type

if($row["document_type_id"] == 1){

    $folder = "photos/";

}
elseif($row["document_type_id"] == 4){

    $folder = "aadhar/";

}
elseif($row["document_type_id"] == 19){

    $folder = "pan/";

}
elseif($row["document_type_id"] == 23){

    $folder = "degree/";

}

elseif($row["document_type_id"] == 25){

    $folder = "experience/";

}

elseif($row["document_type_id"] == 29){

    $folder = "others/";

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

        href="uploads/teachers/<?php echo $folder . htmlspecialchars($row["file_name"]); ?>"

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

<!-- =========================================
BUTTONS
========================================= -->

<div class="dashboard-buttons">

    <a
    href="teacher_update.php"
    class="dashboard-btn blue">

        Update Profile

    </a>

    <a
    href="teacher_dashboard.php"
    class="dashboard-btn green">

        Back Dashboard

    </a>

</div>

</div>

</body>

</html>
