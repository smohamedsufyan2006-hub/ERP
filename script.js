// Wait until the page loads
document.addEventListener("DOMContentLoaded", function () {

    // Get the form
    const form = document.querySelector("form");

    // Run when the form is submitted
    form.addEventListener("submit", function (event) {

        // Get all values
        let studentName = document.getElementsByName("student_name")[0].value.trim();

        let registerNumber = document.getElementsByName("register_number")[0].value.trim();

        let email = document.getElementsByName("email")[0].value.trim();

        let phone = document.getElementsByName("phone")[0].value.trim();

        let password = document.getElementsByName("password")[0].value;

        let confirmPassword = document.getElementsByName("confirm_password")[0].value;

        // Student Name Validation
        if(studentName === ""){

            alert("Please enter your name.");

            event.preventDefault();

            return;

        }

        // Register Number Validation
        if(registerNumber === ""){

            alert("Please enter your register number.");

            event.preventDefault();

            return;

        }

        // Phone Validation
        if(phone.length !== 10){

            alert("Phone number must contain 10 digits.");

            event.preventDefault();

            return;

        }

        // Password Validation
        if(password.length < 6){

            alert("Password must be at least 6 characters.");

            event.preventDefault();

            return;

        }

        // Confirm Password
        if(password !== confirmPassword){

            alert("Passwords do not match.");

            event.preventDefault();

            return;

        }

        // Success
        alert("Validation Successful.");

    });

});
// Password

const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", function(){

    if(password.type === "password"){

        password.type = "text";
        this.classList.replace("fa-eye","fa-eye-slash");

    }else{

        password.type = "password";
        this.classList.replace("fa-eye-slash","fa-eye");

    }

});

// Confirm Password

const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
const confirmPassword = document.getElementById("confirm_password");

toggleConfirmPassword.addEventListener("click", function(){

    if(confirmPassword.type === "password"){

        confirmPassword.type = "text";
        this.classList.replace("fa-eye","fa-eye-slash");

    }else{

        confirmPassword.type = "password";
        this.classList.replace("fa-eye-slash","fa-eye");

    }

});
// ==========================
// SAME AS PERMANENT ADDRESS
// ==========================

const sameAddress = document.getElementById("sameAddress");

const permanentAddress = document.getElementById("address");

const temporaryAddress = document.getElementById("temporary_address");

sameAddress.addEventListener("change", function(){

    if(this.checked){

        temporaryAddress.value = permanentAddress.value;

        temporaryAddress.readOnly = true;

    }else{

        temporaryAddress.readOnly = false;

        temporaryAddress.value = "";

    }

});

permanentAddress.addEventListener("input", function(){

    if(sameAddress.checked){

        temporaryAddress.value = this.value;

    }

});
// ==========================
// SIBLING DETAILS
// ==========================

function toggleSiblingSection() {

    const yes = document.getElementById("siblings_yes").checked;

    const section = document.getElementById("siblings_section");

    if (yes) {

        section.style.display = "block";

    } else {

        section.style.display = "none";

        document.getElementById("siblings_container").innerHTML = `
            <div class="sibling-card">

                <label>Sibling Name</label>

                <input type="text" name="sibling_name[]">

                <br><br>

                <label>Sibling Type</label>

                <select name="sibling_type[]">
                    <option value="">Select</option>
                    <option value="Brother">Brother</option>
                    <option value="Sister">Sister</option>
                </select>

            </div>
        `;

    }

}

function addSibling() {

    let tbody = document.getElementById("siblings_container");

    let rowNumber = tbody.rows.length + 1;

    let row = tbody.insertRow();

    row.innerHTML = `

        <td>${rowNumber}</td>

        <td>

            <input
            type="text"
            name="sibling_name[]"
            required>

        </td>

        <td>

            <select name="sibling_type[]">

                <option value="">Select</option>

                <option value="4">Brother</option>

                <option value="5">Sister</option>

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

    `;

}
function removeSibling(button) {

    // Remove the selected row
    button.closest("tr").remove();

    // Renumber the rows
    updateSiblingNumbers();

}
function updateSiblingNumbers() {

    let rows = document.querySelectorAll("#siblings_container tr");

    rows.forEach(function(row, index) {

        row.cells[0].innerHTML = index + 1;

    });

}