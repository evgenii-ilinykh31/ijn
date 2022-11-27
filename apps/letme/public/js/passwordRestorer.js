function isJson(text) {
    try {
        JSON.parse(text);
    } catch (error) {
        return false;
    }
    return true;
}

const err = 'error';

function getServerError(languageCode) {
    if (languageCode === 'ru') {
        return 'Ошибка сервера, перезагрузите страницу';
    } else if (languageCode === 'es') {
        return 'Error del servidor, vuelva a cargar la página, por favor';
    } else {
        return 'Server error, reload page, please';
    }
}

let attemptsCurrentNumber = 0;
let attemptsLimit = 3;

class PostSender {

    static url() {
        return 'letme.ijn.su';
    }

    static postText(request) {

        let fetchDataObj = {
            method: 'POST',
            body: request,
            headers: {'Content-Type': 'application/x-www-form-urlencoded',},
            credentials: 'include',
        };

        return fetch(this.url(), fetchDataObj).then(response => response.text()).then((serverResponse) => {
            return serverResponse;

        }).catch(error => {
            if (attemptsCurrentNumber < attemptsLimit) {
                attemptsCurrentNumber++;
                console.log('postText error' + error);
                this.postText(err, [new Error().stack, error]);
            } else {
                attemptsCurrentNumber = 0;
                return JSON.stringify([err, getServerError(getLanguageCode())]);
            }

        });
    }
}

function getLanguageCode() {
    return document.documentElement.lang;
}


function validateEmail(email) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        return true;
    }
    return false;
}

function getEmailError(languageCode) {
    if (languageCode === 'ru') {
        return 'Некорректный email';
    } else if (languageCode === 'es') {
        return 'Email incorrecto';
    } else {
        return 'Incorrect email';
    }
}


function validatePassword(password) {
    if (password.length >= 4) {
        return true;
    }
    return false;
}


function getPasswordError(languageCode) {
    if (languageCode === 'ru') {
        return 'Длина пароля - минимум 4 символа';
    } else if (languageCode === 'es') {
        return 'Password length - 4 symbols minimum';
    } else {
        return 'Longitud de la contraseña: mínimo 4 símbolos';
    }
}


let cssError = 'error';
let cssSuccess = 'success';

let languageCode = getLanguageCode();

let emailNode = document.getElementById('email');
let passwordNode = document.getElementById('newPassword');
let send = document.getElementById('send');
let message = document.getElementById('message');


send.addEventListener('click', async () => {
    message.classList.toggle(cssError, true);
    message.classList.toggle(cssSuccess, false);

    message.innerText = '';

    let emailText = emailNode.value;
    let passwordText = passwordNode.value;
    let getToken = new URLSearchParams(window.location.search).get('passwordRestore');

    if (!validateEmail(emailText)) {
        message.classList.toggle(cssError);
        message.innerText = getEmailError(languageCode);

        return;
    }

    if (!validatePassword(passwordText)) {
        message.classList.toggle(cssError, true);
        message.innerText = getPasswordError(languageCode);

        return;
    }

    let response = await PostSender.postText(
        'status' + '=' + 'passwordPostRestoreChanger' +
        '&' + 'token' + '=' + getToken +
        '&' + 'email' + '=' + emailText +
        '&' + 'password' + '=' + passwordText
    );

    if (!isJson(response)) {
        console.log(response);
        message.classList.toggle(cssError, true);
        message.innerText = getServerError(languageCode);

        return;
    }

    response = JSON.parse(response);

    console.log(response);

    if (response[0] === err) {
        message.classList.toggle(cssError, true);
        message.innerText = response[1];

        return;
    }


    message.classList.toggle(cssError, false);
    message.classList.toggle(cssSuccess, true);
    message.innerText = response[1];

    setTimeout(() => {
        console.log('timeout send');
        window.open('https://letme.ijn.su', '_self');
    }, 1500);

})