const button = document.querySelector('#button');
const mail = document.querySelector('#inputEmail');
const password = document.querySelector('#inputPassword');
const form = document.querySelector('#loginForm');


button.addEventListener('click', (e) => {
    e.preventDefault();

    if (mail.value == "") {
        mail.placeholder = "Veuillez saisir votre adresse mail";
    }
    
    if (password.value == ""){
        password.placeholder = "Veuillez saisir votre mot de passe";
    }

    if (mail.value != "" && password.value != ""){
        form.submit();
    }

})