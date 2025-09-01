<?php
require_once __DIR__ . '/config/database.php'; // correct path to database config
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Todo List</title>
    <link rel="stylesheet" href="styles.css"> <!-- reuse your existing styles -->
</head>
<body>
    <div class="form-box">
        <h2>Forgot Password</h2>
        <p>Enter your email to reset your password.</p>

        <form id="forgot-form">
            <input type="email" name="email" placeholder="Enter your email" required />
            <div id="forgot_msg"></div>
            <button type="submit" id="forgot-submit">Reset Password</button>
        </form>
    </div>

    <script src="js/jquery.min.js"></script>
    <script>
        jQuery('#forgot-form').on('submit', function(e) {
            e.preventDefault();
            const formData = jQuery(this).serialize();
            jQuery("#forgot-submit").attr("disabled", true).html('Processing...');

            jQuery.ajax({
                type: "POST",
                url: "ajax/tododata.php", // make sure your ajax handler path is correct
                data: "action=forgot&" + formData,
                dataType: "json",
                success: function(response) {
                    jQuery("#forgot-submit").removeAttr("disabled").html('Reset Password');
                    jQuery("#forgot_msg")
                        .css("color", response.status === "success" ? "green" : "red")
                        .html(response.message);
                },
                error: function() {
                    jQuery("#forgot-submit").removeAttr("disabled").html('Reset Password');
                    jQuery("#forgot_msg").css("color", "red").html("Something went wrong. Please try again.");
                }
            });
        });
    </script>
</body>
</html>
