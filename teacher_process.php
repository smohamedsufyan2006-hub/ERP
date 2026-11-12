<?php

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK REQUEST
// =========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // =========================================
    // PERSONAL INFORMATION
    // =========================================

    $teacher_name = trim($_POST["teacher_name"]);

    $employee_id = trim($_POST["employee_id"]);

    $email = trim($_POST["email"]);

    $phone = trim($_POST["phone"]);

    $gender_id = intval($_POST["gender"]);

    $date_of_birth = $_POST["date_of_birth"];

    // =========================================
    // PROFESSIONAL INFORMATION
    // =========================================

    $staff_category_id = intval($_POST["staff_category"]);

    $designation_id = intval($_POST["designation"]);

    $department_id = intval($_POST["department"]);

   $joining_date = $_POST["date_of_joining"];;

    $status_id = intval($_POST["status"]);

    // =========================================
    // ADDRESS
    // =========================================

    $permanent_address = trim($_POST["permanent_address"]);

    $temporary_address = trim($_POST["temporary_address"]);

    // =========================================
    // EMERGENCY CONTACT
    // =========================================

    $emergency_name = trim($_POST["emergency_name"]);

    $relationship_id = intval($_POST["relationship"]);

    $emergency_phone = trim($_POST["emergency_phone"]);

    // =========================================
    // QUALIFICATIONS
    // =========================================

    $qualification_ids = $_POST["qualification"] ?? [];

    $institutions = $_POST["institution"] ?? [];

    $universities = $_POST["university"] ?? [];
    $specializations = $_POST["specialization"] ?? [];

    $percentages = $_POST["percentage"] ?? [];

    $passing_years = $_POST["passing_year"] ?? [];

    // =========================================
    // EXPERIENCE
    // =========================================

    $experience_institutions = $_POST["experience_institution"] ?? [];

    $experience_designations = $_POST["experience_designation"] ?? [];

    $experience_from = $_POST["experience_from"] ?? [];

    $experience_to = $_POST["experience_to"] ?? [];

   $experience_types = $_POST["experience_type"] ?? [];

    // =========================================
    // ACCOUNT
    // =========================================

    $register_number = trim($_POST["register_number"]);

    $password = $_POST["password"];

    $confirm_password = $_POST["confirm_password"];

    // =========================================
    // FILES
    // =========================================

    // =========================================
// FILES
// =========================================

$photo = $_FILES["photo"];

$aadhaar = $_FILES["aadhaar"];

$pan = $_FILES["pan"];

$degree_certificate = $_FILES["degree_certificate"];

$experience_certificate = $_FILES["experience_certificate"];

$other_document = $_FILES["other_document"];

        // =========================================
    // VALIDATION
    // =========================================

    if ($password != $confirm_password) {

        die("Password and Confirm Password do not match.");

    }

    // =========================================
    // CHECK EMPLOYEE ID
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "SELECT teacher_id
         FROM teachers
         WHERE employee_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $employee_id

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {

        die("Employee ID already exists.");

    }

    mysqli_stmt_close($stmt);

    // =========================================
    // CHECK REGISTER NUMBER
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "SELECT user_id
         FROM user
         WHERE register_number=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $register_number

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {

        die("Register Number already exists.");

    }

    mysqli_stmt_close($stmt);

    // =========================================
    // CHECK EMAIL
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "SELECT user_id
         FROM user
         WHERE email=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $email

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {

        die("Email already exists.");

    }

    mysqli_stmt_close($stmt);

    // =========================================
    // HASH PASSWORD
    // =========================================

    $password = password_hash(

        $password,

        PASSWORD_DEFAULT

    );
    // =========================================
// UPLOAD PHOTO
// =========================================
$photo = "";

if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){

    $photo = time()."_".basename($_FILES["photo"]["name"]);

    $upload_folder = "uploads/teachers/photos/";

    if(!file_exists($upload_folder)){
        mkdir($upload_folder,0777,true);
    }

    move_uploaded_file(

        $_FILES["photo"]["tmp_name"],

        $upload_folder.$photo

    );
    $stmt = mysqli_prepare(

    $conn,

    "INSERT INTO teacher_documents
    (
        teacher_id,
        document_type_id,
        file_name
    )
    VALUES
    (?,?,?)"

);


$photo_type = 1;


mysqli_stmt_bind_param(

    $stmt,

    "iis",

    $teacher_id,
    $photo_type,
    $photo

);


mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

}

    // =========================================
    // START DATABASE TRANSACTION
    // =========================================

    mysqli_begin_transaction($conn);

    try{
            // =========================================
    // INSERT INTO USER TABLE
    // =========================================

    $role_id = 2;

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO user
        (
            register_number,
            email,
            password,
            role_id
        )
        VALUES
        (?, ?, ?, ?)"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "sssi",

        $register_number,
        $email,
        $password,
        $role_id

    );

    mysqli_stmt_execute($stmt);

    $user_id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    // =========================================
    // INSERT INTO TEACHERS TABLE
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO teachers
(
    user_id,
    employee_id,
    teacher_name,
    phone,
    gender_id,
    date_of_birth,
    department_id,
    staff_category_id,
    designation_id,
    joining_date,
    status_id
)
VALUES
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "isssisiiisi",

        $user_id,
        $employee_id,
        $teacher_name,
        $phone,
        $gender_id,
        $date_of_birth,
        $department_id,
        $staff_category_id,
        $designation_id,
        $joining_date,
        $status_id

    );

    mysqli_stmt_execute($stmt);

    $teacher_id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);
        // =========================================
    // INSERT TEACHER ADDRESS
    // =========================================

       // =========================================
    // INSERT TEACHER ADDRESS
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO teacher_addresses
        (
            teacher_id,
            permanent_address,
            temporary_address
        )
        VALUES
        (?, ?, ?)"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "iss",

        $teacher_id,
        $permanent_address,
        $temporary_address

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    // =========================================
    // INSERT EMERGENCY CONTACT
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO teacher_emergency_contacts
        (
            teacher_id,
            emergency_name,
            relationship_id,
            emergency_phone
        )
        VALUES
        (?, ?, ?, ?)"

    );

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
        // =========================================
    // INSERT QUALIFICATIONS
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO teacher_qualifications
        (
            teacher_id,
            qualification_id,
            specialization,
            institution,
            university,
            year_of_passing,
            percentage_or_cgpa
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?)"

    );

    for($i = 0; $i < count($qualification_ids); $i++){

        mysqli_stmt_bind_param(

            $stmt,

            "iissssd",

            $teacher_id,
            $qualification_ids[$i],
            $specializations[$i],
            $institutions[$i],
            $universities[$i],
            $passing_years[$i],
            $percentages[$i]

        );

        mysqli_stmt_execute($stmt);

    }

    mysqli_stmt_close($stmt);

    // =========================================
    // INSERT EXPERIENCE
    // =========================================

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO teacher_experience
        (
            teacher_id,
            organization,
            designation,
            from_date,
            to_date,
            experience_type
        )
        VALUES
        (?, ?, ?, ?, ?, ?)"

    );

    for($i = 0; $i < count($experience_institutions); $i++){

        mysqli_stmt_bind_param(

            $stmt,

            "isssss",

            $teacher_id,
            $experience_institutions[$i],
            $experience_designations[$i],
            $experience_from[$i],
            $experience_to[$i],
            $experience_types[$i]

        );

        mysqli_stmt_execute($stmt);

    }

    mysqli_stmt_close($stmt);
        // =========================================
    // DOCUMENT UPLOAD
    // =========================================

    // =========================================
// DOCUMENT UPLOAD FUNCTION
// =========================================

function uploadDocument($file, $folder)
{

    if($file["error"] == 0)
    {

        $upload_folder = "uploads/teachers/".$folder."/";


        if(!file_exists($upload_folder))
        {

            mkdir($upload_folder,0777,true);

        }


        $extension = pathinfo(
            $file["name"],
            PATHINFO_EXTENSION
        );


        $new_file_name = 
            time()
            ."_"
            .uniqid()
            .".".$extension;



        move_uploaded_file(

            $file["tmp_name"],

            $upload_folder.$new_file_name

        );


        return $new_file_name;

    }


    return null;

}



// =========================================
// PHOTO UPLOAD
// =========================================



// =========================================
// OTHER DOCUMENTS
// =========================================

$documents = [

    4 => [
        "file"   => $aadhaar,
        "folder" => "aadhar"
    ],

    19 => [
        "file"   => $pan,
        "folder" => "pan"
    ],

    23 => [
        "file"   => $degree_certificate,
        "folder" => "degree"
    ],

    25 => [
        "file"   => $experience_certificate,
        "folder" => "experience"
    ],

    29 => [
        "file"   => $other_document,
        "folder" => "others"
    ]

];


foreach($documents as $document_type_id=>$data)
{


    $uploaded_file = uploadDocument(

        $data["file"],

        $data["folder"]

    );


    if($uploaded_file != null)
    {


        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO teacher_documents
            (
                teacher_id,
                document_type_id,
                file_name
            )
            VALUES
            (?,?,?)"

        );



        mysqli_stmt_bind_param(

            $stmt,

            "iis",

            $teacher_id,

            $document_type_id,

            $uploaded_file

        );



        mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


    }

}

    // =========================================
    // COMMIT TRANSACTION
    // =========================================

    mysqli_commit($conn);

    echo "<script>

            alert('Teacher Registered Successfully.');

            window.location='manage_teacher.php';

          </script>";

    exit();

}
catch(Exception $e){

    mysqli_rollback($conn);

    die(

        'Registration Failed : '

        . $e->getMessage()

    );

}

}

mysqli_close($conn);

?>