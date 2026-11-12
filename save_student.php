<?php

// =========================================
// ENABLE ERROR REPORTING (IMPORTANT)
// =========================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =========================================
// DATABASE CONNECTION
// =========================================
require "database.php";
// =========================================
// DOCUMENT UPLOAD FUNCTION
// =========================================

function uploadStudentDocument($input_name, $upload_folder)
{
    if (
        !isset($_FILES[$input_name]) ||
        $_FILES[$input_name]["error"] != 0
    ) {
        return "";
    }

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    $original_name = basename($_FILES[$input_name]["name"]);

    $new_file_name = time() . "_" . $original_name;

    $destination = $upload_folder . $new_file_name;

    if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $destination)) {

        return $new_file_name;

    }

    return "";
}

// =========================================
// CHECK REQUEST
// =========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =========================================
    // STUDENT DETAILS
    // =========================================
    $student_name = trim($_POST["student_name"]);
    $register_number = trim($_POST["register_number"]);
    $program_id = $_POST["program_level"]; // FIXED
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $gender_id = $_POST["gender"];
    $date_of_birth = $_POST["date_of_birth"];
    $department_id = $_POST["department"];
    $year_id = $_POST["year_of_study"];

    // =========================================
    // ADDRESS
    // =========================================
    $address = trim($_POST["address"]);
    $temporary_address = trim($_POST["temporary_address"]);

    // =========================================
    // FAMILY
    // =========================================
    $father_name = trim($_POST["father_name"]);
    $father_occupation = trim($_POST["father_occupation"]);
    $father_phone = trim($_POST["father_phone"]);

    $mother_name = trim($_POST["mother_name"]);
    $mother_occupation = trim($_POST["mother_occupation"]);
    $mother_phone = trim($_POST["mother_phone"]);

    $guardian_name = trim($_POST["guardian_name"]);
    $guardian_phone = trim($_POST["guardian_phone"]);

    // =========================================
    // SIBLINGS
    // =========================================
    $has_siblings = $_POST["has_siblings"] ?? "No";
    $sibling_names = $_POST["sibling_name"] ?? [];
    $sibling_types = $_POST["sibling_type"] ?? [];

    // =========================================
    // EMERGENCY
    // =========================================
    $emergency_name = trim($_POST["emergency_name"]);
    $relationship_id = $_POST["relationship"];
    $emergency_phone = trim($_POST["emergency_phone"]);

    // =========================================
    // ACADEMIC
    // =========================================
    $tenth_marks = $_POST["tenth_marks"];
    $tenth_school = trim($_POST["tenth_school"]);

    $twelfth_marks = $_POST["twelfth_marks"];
    $twelfth_school = trim($_POST["twelfth_school"]);

    $board_id = $_POST["board"];
    $photo = uploadStudentDocument(
    "photo",
    "uploads/student/photos/"
);

$tenth_marksheet = uploadStudentDocument(
    "tenth_marksheet",
    "uploads/student/tenth_marksheets/"
);

$twelfth_marksheet = uploadStudentDocument(
    "twelfth_marksheet",
    "uploads/student/twelfth_marksheets/"
);

$aadhar_card = uploadStudentDocument(
    "aadhar_card",
    "uploads/student/aadhar_cards/"
);

$transfer_certificate = uploadStudentDocument(
    "transfer_certificate",
    "uploads/student/transfer_certificates/"
);

$community_certificate = uploadStudentDocument(
    "community_certificate",
    "uploads/student/community_certificates/"
);

    // =========================================
    // PASSWORD
    // =========================================
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // =========================================
    // CHECK DUPLICATES
    // =========================================
    $check = mysqli_query($conn,
        "SELECT user_id FROM user
         WHERE email='$email' OR register_number='$register_number'"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
            alert('Email or Register Number already exists');
            window.history.back();
        </script>";
        exit();
    }

    // =========================================
    // INSERT USER
    // =========================================
    $sql = "INSERT INTO user (register_number, email, password, role_id)
            VALUES ('$register_number', '$email', '$password', 1)";

    if (!mysqli_query($conn, $sql)) {
        die("User Error: " . mysqli_error($conn));
    }

    $user_id = mysqli_insert_id($conn);

    // =========================================
    // INSERT STUDENT
    // =========================================
    $sql = "INSERT INTO students (
        user_id,
        program_id,
        student_name,
        phone,
        gender_id,
        date_of_birth,
        department_id,
        year_id
    ) VALUES (
        '$user_id',
        '$program_id',
        '$student_name',
        '$phone',
        '$gender_id',
        '$date_of_birth',
        '$department_id',
        '$year_id'
    )";

    if (!mysqli_query($conn, $sql)) {
        die("Student Error: " . mysqli_error($conn));
    }

    $student_id = mysqli_insert_id($conn);

    // =========================================
    // ADDRESS
    // =========================================
    mysqli_query($conn,
        "INSERT INTO student_addresses
        (student_id, permanent_address, temporary_address)
        VALUES ('$student_id', '$address', '$temporary_address')"
    );

    // =========================================
    // FAMILY
    // =========================================
    mysqli_query($conn,
        "INSERT INTO family_details
        (student_id, relationship_id, name, occupation, phone)
        VALUES ('$student_id', 1, '$father_name', '$father_occupation', '$father_phone')"
    );

    mysqli_query($conn,
        "INSERT INTO family_details
        (student_id, relationship_id, name, occupation, phone)
        VALUES ('$student_id', 2, '$mother_name', '$mother_occupation', '$mother_phone')"
    );

    if ($guardian_name != "" || $guardian_phone != "") {
        mysqli_query($conn,
            "INSERT INTO family_details
            (student_id, relationship_id, name, occupation, phone)
            VALUES ('$student_id', 3, '$guardian_name', '', '$guardian_phone')"
        );
    }

    // =========================================
    // EMERGENCY
    // =========================================
    mysqli_query($conn,
        "INSERT INTO emergency_contacts
        (student_id, emergency_name, relationship_id, emergency_phone)
        VALUES ('$student_id', '$emergency_name', '$relationship_id', '$emergency_phone')"
    );

    // =========================================
    // ACADEMIC (FIXED board_id ONLY)
    // =========================================
    mysqli_query($conn,
        "INSERT INTO academic_details
        (student_id, qualification, school_name, percentage, board_id)
        VALUES ('$student_id', '10th', '$tenth_school', '$tenth_marks', '$board_id')"
    );

    mysqli_query($conn,
        "INSERT INTO academic_details
        (student_id, qualification, school_name, percentage, board_id)
        VALUES ('$student_id', '12th', '$twelfth_school', '$twelfth_marks', '$board_id')"
    );

    // =========================================
    // DOCUMENTS
    // =========================================
    mysqli_query($conn,
        "INSERT INTO student_documents
        (student_id, document_type_id, file_path)
        VALUES ('$student_id', 1, '$photo')"
    );

    mysqli_query($conn,
        "INSERT INTO student_documents
        (student_id, document_type_id, file_path)
        VALUES ('$student_id', 2, '$tenth_marksheet')"
    );

    mysqli_query($conn,
        "INSERT INTO student_documents
        (student_id, document_type_id, file_path)
        VALUES ('$student_id', 3, '$twelfth_marksheet')"
    );

    mysqli_query($conn,
        "INSERT INTO student_documents
        (student_id, document_type_id, file_path)
        VALUES ('$student_id', 4, '$aadhar_card')"
    );

    mysqli_query($conn,
        "INSERT INTO student_documents
        (student_id, document_type_id, file_path)
        VALUES ('$student_id', 5, '$transfer_certificate')"
    );

    if (!empty($community_certificate)) {
        mysqli_query($conn,
            "INSERT INTO student_documents
            (student_id, document_type_id, file_path)
            VALUES ('$student_id', 6, '$community_certificate')"
        );
    }

    // =========================================
    // SIBLINGS
    // =========================================
    if ($has_siblings == "Yes") {
        for ($i = 0; $i < count($sibling_names); $i++) {

            $name = trim($sibling_names[$i]);
            $rel = $sibling_types[$i];

            if ($name != "" && $rel != "") {
                mysqli_query($conn,
                    "INSERT INTO siblings
                    (student_id, sibling_name, relationship_id)
                    VALUES ('$student_id', '$name', '$rel')"
                );
            }
        }
    }

    // =========================================
    // SUCCESS
    // =========================================
    echo "<script>
        alert('Student registered successfully');
        window.location.href = 'index.php';
    </script>";
}
?>