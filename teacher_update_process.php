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

if(!isset($_SESSION["teacher_id"])){

    header("Location: login.php");

    exit();

}

// =========================================
// GET TEACHER ID
// =========================================

$teacher_id = $_SESSION["teacher_id"];

// =========================================
// CHECK REQUEST
// =========================================

if($_SERVER["REQUEST_METHOD"] != "POST"){

    header("Location: teacher_dashboard.php");

    exit();

}

// =========================================
// PERSONAL DETAILS
// =========================================

$employee_id       = trim($_POST["employee_id"]);
$teacher_name      = trim($_POST["teacher_name"]);
$gender_id         = $_POST["gender_id"];
$date_of_birth     = $_POST["date_of_birth"];
$phone             = trim($_POST["phone"]);
$department_id     = $_POST["department_id"];
$staff_category_id = $_POST["staff_category_id"];
$designation_id    = $_POST["designation_id"];
$joining_date      = $_POST["joining_date"];
$status_id         = $_POST["status_id"];

// =========================================
// UPDATE TEACHER
// =========================================

$sql = "UPDATE teachers
SET
employee_id = ?,
teacher_name = ?,
gender_id = ?,
date_of_birth = ?,
phone = ?,
department_id = ?,
staff_category_id = ?,
designation_id = ?,
joining_date = ?,
status_id = ?
WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

    "ssissiiisii",

    $employee_id,
    $teacher_name,
    $gender_id,
    $date_of_birth,
    $phone,
    $department_id,
    $staff_category_id,
    $designation_id,
    $joining_date,
    $status_id,
    $teacher_id

);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// ADDRESS
// =========================================

$permanent_address = trim($_POST["permanent_address"]);
$temporary_address = trim($_POST["temporary_address"]);

// Check if address exists

$sql = "SELECT teacher_address_id
        FROM teacher_addresses
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$teacher_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    $sql = "UPDATE teacher_addresses
            SET
            permanent_address = ?,
            temporary_address = ?
            WHERE teacher_id = ?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(

        $stmt,

        "ssi",

        $permanent_address,
        $temporary_address,
        $teacher_id

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}else{

    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO teacher_addresses
            (
                teacher_id,
                permanent_address,
                temporary_address
            )
            VALUES
            (?, ?, ?)";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(

        $stmt,

        "iss",

        $teacher_id,
        $permanent_address,
        $temporary_address

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}

// =========================================
// EMERGENCY CONTACT
// =========================================

$emergency_name  = trim($_POST["emergency_name"]);
$relationship_id = $_POST["relationship_id"];
$emergency_phone = trim($_POST["emergency_phone"]);

// Check if emergency contact exists

$sql = "SELECT emergency_contact_id
        FROM teacher_emergency_contacts
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$teacher_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    $sql = "UPDATE teacher_emergency_contacts
            SET
            emergency_name = ?,
            relationship_id = ?,
            emergency_phone = ?
            WHERE teacher_id = ?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(

        $stmt,

        "sisi",

        $emergency_name,
        $relationship_id,
        $emergency_phone,
        $teacher_id

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}else{

    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO teacher_emergency_contacts
            (
                teacher_id,
                emergency_name,
                relationship_id,
                emergency_phone
            )
            VALUES
            (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(

        $stmt,

        "isis",

        $teacher_id,
        $emergency_name,
        $relationship_id,
        $emergency_phone

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}
// =========================================
// QUALIFICATION UPDATE
// =========================================


// Delete old qualifications

$sql = "DELETE FROM teacher_qualifications
        WHERE teacher_id = ?";


$stmt = mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_id

);


mysqli_stmt_execute($stmt);


mysqli_stmt_close($stmt);



// Check qualification data

if(isset($_POST["qualification_name"])){


    $qualification_name = $_POST["qualification_name"];
    $university         = $_POST["university"];
    $year_of_passing    = $_POST["year_of_passing"];
    $percentage         = $_POST["percentage"];



    // Insert new qualifications


    for($i = 0; $i < count($qualification_name); $i++){


        // Skip empty rows

        if(trim($qualification_name[$i]) == ""){

            continue;

        }



        $sql = "INSERT INTO teacher_qualifications

                (

                    teacher_id,

                    qualification_name,

                    university,

                    year_of_passing,

                    percentage

                )

                VALUES

                (?,?,?,?,?)";



        $stmt = mysqli_prepare($conn,$sql);



        mysqli_stmt_bind_param(

            $stmt,

            "issis",

            $teacher_id,

            $qualification_name[$i],

            $university[$i],

            $year_of_passing[$i],

            $percentage[$i]

        );



        mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


    }


}
// =========================================
// EXPERIENCE UPDATE
// =========================================


// Delete old experience records

$sql = "DELETE FROM teacher_experience
        WHERE teacher_id = ?";


$stmt = mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_id

);


mysqli_stmt_execute($stmt);


mysqli_stmt_close($stmt);



// Check experience data exists

if(isset($_POST["company_name"])){


    $company_name   = $_POST["company_name"];
    $designation    = $_POST["experience_designation"];
    $from_date      = $_POST["from_date"];
    $to_date        = $_POST["to_date"];
    $experience_years = $_POST["experience_years"];



    // Insert new experience records


    for($i = 0; $i < count($company_name); $i++){


        // Skip empty rows

        if(trim($company_name[$i]) == ""){

            continue;

        }



        $sql = "INSERT INTO teacher_experience

                (

                    teacher_id,

                    organization,

                    designation,

                    from_date,

                    to_date,

                    experience_years

                )

                VALUES

                (?,?,?,?,?,?)";



        $stmt = mysqli_prepare($conn,$sql);



        mysqli_stmt_bind_param(

            $stmt,

            "issssi",

            $teacher_id,

            $company_name[$i],

            $designation[$i],

            $from_date[$i],

            $to_date[$i],

            $experience_years[$i]

        );



        mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


    }


}
/// =========================================
// DOCUMENT UPDATE
// =========================================

function updateTeacherDocument($conn, $teacher_id, $document_type_id, $input_name, $folder)
{

    if(
        !isset($_FILES[$input_name]) ||
        $_FILES[$input_name]["error"] != 0
    ){

        return;

    }

    // Create folder if it doesn't exist

    $upload_folder = "uploads/teachers/".$folder."/";

    if(!is_dir($upload_folder)){

        mkdir($upload_folder,0777,true);

    }

    // Generate new filename

    $extension = pathinfo(
        $_FILES[$input_name]["name"],
        PATHINFO_EXTENSION
    );

    $new_file_name =
        time()
        ."_"
        .uniqid()
        .".".$extension;

    move_uploaded_file(

        $_FILES[$input_name]["tmp_name"],

        $upload_folder.$new_file_name

    );

    // Check whether this document already exists

    $sql = "SELECT document_id
            FROM teacher_documents
            WHERE teacher_id=?
            AND document_type_id=?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $teacher_id,
        $document_type_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    if(mysqli_num_rows($result)>0){

        // Update existing document

        $sql = "UPDATE teacher_documents
                SET file_name=?
                WHERE teacher_id=?
                AND document_type_id=?";

        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param(

            $stmt,

            "sii",

            $new_file_name,

            $teacher_id,

            $document_type_id

        );

    }
    else{

        // Insert new document

        $sql = "INSERT INTO teacher_documents
                (
                    teacher_id,
                    document_type_id,
                    file_name
                )
                VALUES
                (?,?,?)";

        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param(

            $stmt,

            "iis",

            $teacher_id,

            $document_type_id,

            $new_file_name

        );

    }

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}
// =========================================
// UPDATE DOCUMENTS
// =========================================

updateTeacherDocument($conn,$teacher_id,1,"photo","photos");

updateTeacherDocument($conn,$teacher_id,2,"aadhaar","aadhar");

updateTeacherDocument($conn,$teacher_id,3,"pan","pan");

updateTeacherDocument($conn,$teacher_id,4,"degree_certificate","degree");

updateTeacherDocument($conn,$teacher_id,5,"experience_certificate","experience");

updateTeacherDocument($conn,$teacher_id,6,"other_document","others");
// =========================================
// UPDATE PASSWORD (OPTIONAL)
// =========================================

if(
    $current_password != "" ||
    $new_password != "" ||
    $confirm_password != ""
){

    if(
        $current_password == "" ||
        $new_password == "" ||
        $confirm_password == ""
    ){

        die("Please fill all password fields.");

    }

    if($new_password != $confirm_password){

        die("New password and confirm password do not match.");

    }

    // Get user_id from teachers table

    $sql = "SELECT user_id
            FROM teachers
            WHERE teacher_id=?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param($stmt,"i",$teacher_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $teacher = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    $user_id = $teacher["user_id"];

    // Get current password

    $sql = "SELECT password
            FROM user
            WHERE user_id=?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param($stmt,"i",$user_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if(!password_verify($current_password,$user["password"])){

        die("Current password is incorrect.");

    }

    $new_password_hash = password_hash($new_password,PASSWORD_DEFAULT);

    $sql = "UPDATE user
            SET password=?
            WHERE user_id=?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $new_password_hash,
        $user_id
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}


// =========================================
// SUCCESS MESSAGE
// =========================================


echo "

<script>

alert('Teacher details updated successfully.');

window.location.href='teacher_profile.php';

</script>";



exit();

?>