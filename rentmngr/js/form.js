function formhash(form, pass) {
    // Create a new element input, this will be out hashed password field.
    var password = document.createElement("input");
    // Add the new element to our form.
    form.appendChild(password);
    password.name = "password";
    password.type = "hidden"
    password.value = hex_sha512(pass.value);
    // Make sure the plaintext password doesn't get sent.
    pass.value = "";
    // Finally submit the form.
    form.submit();
}