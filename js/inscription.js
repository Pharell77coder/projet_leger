function validateForm(){
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm-password").value;
    let messageError = document.getElementById("#error-msg");

    if (password !== confirmPassword) {
        messageError.textContent = "Les mots de passe ne correspondent pas.";
        messageError.style.color = "red";
        return false;
    }
    return true;
}
