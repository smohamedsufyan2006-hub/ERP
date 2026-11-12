<?php
require "database.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <h1>Student Registration Form</h1>

    <form action="save_student.php" method="POST" enctype="multipart/form-data">

    <!-- ===========================
            PERSONAL INFORMATION
    ============================ -->

    <h2 class="form-section">
        Personal Information
    </h2>

    <label>Student Name</label>

    <input
    type="text"
    name="student_name"
    required>

    <label>Register Number</label>

    <input
    type="text"
    name="register_number"
    required>
    <label>Program Level</label>

<select name="program_level" required>

    <option value="">Select Program</option>

    <option value="1">UG</option>

    <option value="2">PG</option>

</select>

    <label>Email</label>

    <input
    type="email"
    name="email"
    required>

    <label>Phone Number</label>

    <input
    type="tel"
    name="phone"
    pattern="[0-9]{10}"
    required>

    <label>Gender</label>

    <select name="gender" required>
        <option value="">-- Select Gender --</option>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM genders");

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['gender_id']}'>
                    {$row['gender_name']}
                  </option>";
        }
        ?>

    </select>

    <label>Date of Birth</label>

    <input
    type="date"
    name="date_of_birth"
    required>

    <label>Department</label>

        <select name="department" required>

<?php
$result = mysqli_query($conn, "SELECT * FROM departments");

while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='{$row['department_id']}'>
            {$row['department_name']}
          </option>";
}
?>

</select>

    <label>Year of Study</label>

        <select name="year_of_study" required>
    <option value="">-- Select Year --</option>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM years");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['year_id']}'>
                {$row['year_name']}
              </option>";
    }
    ?>
</select>
    <!-- ===========================
            ADDRESS DETAILS
    ============================ -->

    <h2 class="form-section">

        Address Details

    </h2>

    <label for="address">

        Permanent Address

    </label>

    <textarea
    id="address"
    name="address"
    rows="4"
    required></textarea>

    <div class="checkbox-group">

        <input
        type="checkbox"
        id="sameAddress">

        <label for="sameAddress">

            Same as Permanent Address

        </label>

    </div>

    <label for="temporary_address">

        Temporary Address

    </label>

    <textarea
    id="temporary_address"
    name="temporary_address"
    rows="4"></textarea>
<!-- ===========================
        PARENT / GUARDIAN DETAILS
=========================== -->

<h2 class="form-section">

    Parent / Guardian Details

</h2>

<label>Father's Name</label>

<input
type="text"
name="father_name"
required>

<label>Father's Occupation</label>

<input
type="text"
name="father_occupation"
required>

<label>Father's Phone Number</label>

<input
type="tel"
name="father_phone"
pattern="[0-9]{10}"
required>

<label>Mother's Name</label>

<input
type="text"
name="mother_name"
required>

<label>Mother's Occupation</label>

<input
type="text"
name="mother_occupation"
required>

<label>Mother's Phone Number</label>

<input
type="tel"
name="mother_phone"
pattern="[0-9]{10}"
required>

<label class="optional">Guardian Name (Optional)</label>

<input
type="text"
name="guardian_name">

<label class="optional">Guardian Phone Number (Optional)</label>

<input
type="tel"
name="guardian_phone"
pattern="[0-9]{10}">

<!-- ===========================
        SIBLING DETAILS
=========================== -->

<h2 class="form-section">

    Sibling Details

</h2>

<label>

Do you have siblings?

</label>

<div class="gender">

    <label>

        <input
        type="radio"
        id="siblings_yes"
        name="has_siblings"
        value="Yes"
        onclick="toggleSiblingSection()">

        Yes

    </label>

    <label>

        <input
        type="radio"
        id="siblings_no"
        name="has_siblings"
        value="No"
        checked
        onclick="toggleSiblingSection()">

        No

    </label>

</div>
<div id="siblings_section" style="display:none;">

<table class="siblings-table">

    <thead>

        <tr>

            <th>#</th>

            <th>Sibling Name</th>

            <th>Sibling Type</th>

            <th>Action</th>

        </tr>

    </thead>

    <tbody id="siblings_container">

        <tr>

            <td>1</td>

            <td>

                <input
                type="text"
                name="sibling_name[]">

            </td>

            <td>

                <select name="sibling_type[]">

                    <option value="">Select</option>

                    <?php

                    $result = mysqli_query($conn,"
                    SELECT * FROM relationships
                    WHERE relationship_name IN ('Brother','Sister')
                    ");

                    while($row=mysqli_fetch_assoc($result)){

                        echo "<option value='{$row['relationship_id']}'>
                        {$row['relationship_name']}
                        </option>";

                    }

                    ?>

                </select>

            </td>

            <td>

                <button
                type="button"
                class="remove-btn"
                onclick="removeSibling(this)">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </td>

        </tr>

    </tbody>

</table>

<br>

<button
type="button"
class="add-btn"
onclick="addSibling()">

+ Add Another Sibling

</button>

</div>

<!-- ===========================
        EMERGENCY CONTACT
=========================== -->

<h2 class="form-section">

    Emergency Contact

</h2>

<label>Emergency Contact Name</label>

<input
type="text"
name="emergency_name"
required>

<label>Relationship</label>

<select name="relationship" required>
    <option value="">-- Select Relationship --</option>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM relationships");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['relationship_id']}'>
                {$row['relationship_name']}
              </option>";
    }
    ?>
</select>

<label>Emergency Contact Number</label>

<input
type="tel"
name="emergency_phone"
pattern="[0-9]{10}"
required>

<!-- ===========================
        PART 3 STARTS HERE
=========================== -->
<!-- ===========================
        ACADEMIC DETAILS
=========================== -->

<h2 class="form-section">

    Academic Details

</h2>

<label>10th Percentage</label>

<input
type="number"
name="tenth_marks"
step="0.01"
min="0"
max="100"
required>

<label>10th School Name</label>

<input
type="text"
name="tenth_school"
required>

<label>12th Percentage</label>

<input
type="number"
name="twelfth_marks"
step="0.01"
min="0"
max="100"
required>

<label>12th School Name</label>

<input
type="text"
name="twelfth_school"
required>

<label>Board of Education</label>

<select name="board" required>
    <option value="">-- Select Board --</option>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM boards");

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['board_id']}'>
                {$row['board_name']}
              </option>";
    }
    ?>
</select>
<!-- ===========================
        DOCUMENTS
=========================== -->

<h2 class="form-section">

    Documents

</h2>

<label>Upload 10th Marksheet</label>

<input
type="file"
name="tenth_marksheet"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Upload 12th Marksheet</label>

<input
type="file"
name="twelfth_marksheet"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Passport Size Photo</label>

<input
type="file"
name="photo"
accept=".jpg,.jpeg,.png"
required>

<label>Upload Aadhaar Card</label>

<input
type="file"
name="aadhar_card"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Upload Transfer Certificate</label>

<input
type="file"
name="transfer_certificate"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label class="optional">Upload Community Certificate (Optional)</label>

<input
type="file"
name="community_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<!-- ===========================
        ACCOUNT DETAILS
=========================== -->

<h2 class="form-section">

    Account Details

</h2>

<label for="password">

    Password

</label>

<div class="password-box">

    <input
    type="password"
    name="password"
    id="password"
    required>

    <i
    class="fa-solid fa-eye"
    id="togglePassword"></i>

</div>

<label for="confirm_password">

    Confirm Password

</label>

<div class="password-box">

    <input
    type="password"
    name="confirm_password"
    id="confirm_password"
    required>

    <i
    class="fa-solid fa-eye"
    id="toggleConfirmPassword"></i>

</div>

<div class="button-group">

    <button
    type="submit">

        Register Student

    </button>

    <button
    type="reset">

        Reset Form

    </button>

</div>

</form>

</div>

<script src="script.js"></script>

</body>

</html>