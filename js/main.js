$(document).ready(function() {
    $('#registrationForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serializeArray();

        const email = formData.find(field => field.name === "email").value;
        const password = formData.find(field => field.name === "password").value;
        const repeatPassword = formData.find(field => field.name === "repeatPassword").value;

        if (!email.includes('@')) {
            showAlert("Invalid email!", "danger");
            return;
        }

        if (password !== repeatPassword) {
            showAlert("Passwords do not match!", "danger");
            return;
        }

        $.ajax({
            type: "POST",
            url: "process.php",
            data: formData,
            success: function(data) {
                if (data.success) {
                    showAlert(data.message, "success");
                    $('#registrationForm').hide();
                } else {
                    showAlert(data.message, "danger");
                }
            }
        });
    });
});

function showAlert(message, type) {
    $('#alertBox').removeClass().addClass(`alert alert-${type}`).text(message).show();
}
