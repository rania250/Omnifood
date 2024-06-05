document.addEventListener("DOMContentLoaded", function() {
    console.log("Script loaded and DOM fully parsed.");
    const pwShowHide = document.querySelectorAll(".eye-icon");

    pwShowHide.forEach((eyeIcon) => {
        eyeIcon.addEventListener("click", () => {
            console.log("Eye icon clicked");
            let pwFields = eyeIcon.closest('.input-field').querySelectorAll(".password");

            pwFields.forEach((password) => {
                if (password.type === "password") {
                    password.type = "text";
                    eyeIcon.classList.replace("bx-hide", "bx-show");
                    console.log("Password field changed to text");
                } else {
                    password.type = "password";
                    eyeIcon.classList.replace("bx-show", "bx-hide");
                    console.log("Password field changed to password");
                }
            });
        });
    });
});
