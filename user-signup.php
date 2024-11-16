<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="libs/admin.css">
    <link rel="stylesheet" href="libs/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="login">
    <div>
        <form method="POST" action="Data/signup.php" class="sign-up-form">
            <img src="images/CalbayogGO.png" alt="logo">
            <h2>Sign Up</h2>
            <div class="between">
                <div class="input-group">
                    <input type="text" id="firstname" name="firstname" class="input" required>
                    <label for="firstname" class="placeholder">Firstname</label>
                </div>
                <div class="input-group">
                    <input type="text" id="lastname" name="lastname" class="input" required>
                    <label for="lastname" class="placeholder">Lastname</label>
                </div>
            </div>
            <div class="input-group">
                <input type="email" id="email" name="email" class="input" required>
                <label for="email" class="placeholder">Email</label>
            </div>
            <div class="input-group">
                <input type="text" id="username" name="username" class="input" required>
                <label for="username" class="placeholder">Username</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" class="input" required>
                <label for="password" class="placeholder">Password</label>
                <i class="fa-sharp fa-regular fa-eye-slash toggle-password" onclick="togglePasswordVisibility('password')"></i>
            </div>
            <div class="input-group">
                <input type="password" id="confirm-password" name="confirm-password" class="input" required>
                <label for="confirm-password" class="placeholder">Confirm Password</label>
                <i class="fa-sharp fa-regular fa-eye-slash toggle-password" onclick="togglePasswordVisibility('confirm-password')"></i>
            </div>
            <div id="message-container" class="signup-succ-message"></div>
            <div class="already">
                <a href="user-login.php">Sign In</a>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        
    </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.toggle-password');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        $(document).ready(function() {
            $('.sign-up-form').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting traditionally

                const formData = $(this).serialize(); // Serialize form data for AJAX submission

                $.ajax({
                    url: 'Data/signup.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        const messageContainer = $('#message-container');

                        // Show the message
                        if (response.status === 'success') {
                            messageContainer.removeClass('error').addClass('success').text(response.message).fadeIn();
                            $('.sign-up-form')[0].reset(); // Optional: Reset form fields after successful signup
                        } else {
                            messageContainer.removeClass('success').addClass('error').text(response.message).fadeIn();
                        }

                        // Hide the message after a few seconds
                        setTimeout(() => {
                            messageContainer.fadeOut();
                        }, 5000); // Adjust the time as needed
                    },
                    error: function() {
                        const messageContainer = $('#message-container');
                        messageContainer.removeClass('success').addClass('error').text("An error occurred while processing your request. Please try again.").fadeIn();

                        // Hide the message after a few seconds
                        setTimeout(() => {
                            messageContainer.fadeOut();
                        }, 5000);
                    }
                });
            });
        });

    </script>
</body>
</html>
