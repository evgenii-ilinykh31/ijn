//__ERROR CONSTANTS__

const success = 'success';
const err = 'error';
const errServer = 'server';
const errAuthorization = 'authorization';

//__USER ERROR CONSTANTS__
const errorEmailNotValid = 'errorEmailNotValid';

const userErrors = new Map([
    [errorEmailNotValid, 'Нужен корректный email'],
    ['errorEmailExist', 'Такой email уже есть']
]);



//__POST CONSTANTS__

const postStatus = 'status';

const postRegistration = 'registration';

const postAuthorization = 'authorization';

const postLogout = 'logout';

const postGetNotes = 'getNotes';

const postFindNotes = 'findNotes';

const postTakeNote = 'takeNote';

const postChangepassword = 'changepassword';

const postRestorePassword = 'restorePassword';

const postDeleteNote = 'deleteNote';

const postUndoDeleteNode = 'undoDeleteNode';

const postEditNote = 'editNote';

const postSaveTempNote = 'saveTempNote';

const postEmail = 'email';

const postPassword = 'password';

const postText = 'text';

const postId = 'id';

const csrfToken = 'csrf-token';

const csrfContent = 'content';


//_CSS CONSTANTS

const cssHidden = 'hidden';
const cssVisible = 'visible';
const cssInvisible = 'invisible';
const cssLogin = 'login';
const cssJob = 'job';
const cssNote = 'note';
const cssActive = 'active';
const cssEditDelteElement = 'editDeleteElement';
const cssPushElement = 'pushElement';

//__WORKERS SECTION__


function isJson(text) {
    try {
        JSON.parse(text);
    } catch (error) {
        return false;
    }
    return true;
}

function validateEmail(email) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        return true;
    }
    return false;
}

function validatePassword(password) {
    if (password.length >= 4) {
        return true;
    }
    return false;
}


function switchOnCheckbox(checkbox) {

    switchOffAllCheckboxes();

    checkbox.checked = true;

}


function switchOffAllCheckboxes() {
    [...document.querySelectorAll("input[type='checkbox']")].forEach(element => {
        element.checked = false;
    });
}


function dropClass(nodes, cssClass) {
    nodes.forEach(node => {
        node.classList.toggle(cssClass, false);
    });
}


function refillChangeLanguageNodes(nodesList, contentsList) {
    for (let i = 0; i < nodesList.length; i++) {
        nodesList[i].classList.add(cssInvisible);
    }

    setTimeout(() => {
        for (let i = 0; i < nodesList.length; i++) {
            nodesList[i].innerHTML = contentsList[i];
        }

        setAuthorizeButtonListener();
        setRegisterButtonListener();
        setSettingsListeners();
        setTakeNoteListeners();
        setFindNodesListeners();
        setChangepasswordListener();
        setForgotPasswordListener();
        setEditNoteSetListeners();
        setUndoDeleteListeners();

    }, 405);

    setTimeout(() => {
        for (let i = 0; i < nodesList.length; i++) {
            nodesList[i].classList.add(cssVisible);
            nodesList[i].classList.remove(cssInvisible);
        }
    }, 600);

    setTimeout(() => {
        for (let i = 0; i < nodesList.length; i++) {
            nodesList[i].classList.remove(cssVisible);
        }
    }, 1005);

}


function isMobile() {
    var match = window.matchMedia || window.msMatchMedia;
    if (match) {
        var mq = match("(pointer:coarse)");
        return mq.matches;
    }
    return false;
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
                return errServer;
            }

        });
    }
}




//___PULL TO REFRESH SECTION___

let touchStartY = 0;
let touchEndY = 0;

let refreshNode = document.getElementById('refresh');
refreshNode.style.opacity = 0;
refreshNode.style.transition = 'opacity 0.2s';
let refreshCurrentOpacity = 0.01;


document.addEventListener('touchstart', event => {
    touchStartY = event.targetTouches[0].screenY;
});



document.addEventListener('touchmove', event => {
    if (window.scrollY <= 0) {
        refreshNode.style.opacity = Math.abs(window.scrollY) / 100;
        refreshCurrentOpacity += 0.01;
    }
});


document.addEventListener('touchend', event => {
    touchEndY = event.changedTouches[0].screenY;

    refreshNode.style.opacity = 0;

    if (window.scrollY <= 0 && (touchEndY - touchStartY) > 100) {
        setTimeout(() => {
            document.location.reload();
        }, 500);
    } else {
        refreshNode.classList.toggle(cssActive, false);
    }
});





//__APP MODES SECTION__

let appNode = document.getElementById('app');

function switchToJob() {
    document.body.style.overflow = 'hidden';

    document.querySelectorAll('.' + cssLogin).forEach(element => {
        element.classList.remove(cssLogin);
        element.classList.add(cssJob);
    });

    setTimeout(() => {
        document.body.style.overflow = 'visible';
    }, 1200);
}

function switchToLogin() {
    document.querySelectorAll('.' + cssJob).forEach(element => {
        element.classList.remove(cssJob);
        element.classList.add(cssLogin);
    });
}

//__LOGIN WARNING SECTION__
let loginWarning = document.getElementById('loginWarning');

function loginWarningOn(text) {
    loginWarning.innerText = text;
    loginWarning.classList.remove(cssHidden);
}

function loginWarningOff() {
    loginWarning.value = '';
    if (!loginWarning.classList.contains(cssHidden)) {
        loginWarning.classList.add(cssHidden);
    }
}

//___HEADER SECTION___
function changeTitleSettingsIcon(title) {
    document.getElementById('settings').setAttribute('title', title);
}


//___EXAMPLE SECTION___
function changeExampleLanguage(srcToImg) {
    let example = document.getElementById('example');
    example.classList.add(cssInvisible);

    setTimeout(() => {
        example.style.backgroundImage = 'url(' + srcToImg + ')';
    }, 405);

    setTimeout(() => {
        example.classList.add(cssVisible);
    }, 410);

    setTimeout(() => {
        example.classList.remove(cssInvisible, cssVisible);
    }, 1005);

}

//__CSRF SECTION__


//__NOTE SECTION__

const flowJob = 'flowJob';
const pathToDeleteSign = "/public/images/delete.svg";
const pathToEditSign = "/public/images/edit.svg";
const pathToTopSign = "/public/images/toTop.svg";
const pathToBottomSign = "/public/images/toBottom.svg";
const pathToMoveSign = "/public/images/move.svg";

function createAndReturnNote(id, text, titleDelete, titleEdit, titleToTop, titleToBottom, titleMove) {
    let div = document.createElement('div');
    div.classList.add(cssNote);

    let span = document.createElement('span');
    span.innerHTML = text;

    div.append(span);

    [
        [pathToDeleteSign, titleDelete, 'delete', noteDeleteListener, 'click'],
        [pathToEditSign, titleEdit, 'edit', noteEditorPopupListener, 'click'],
        [pathToTopSign, titleToTop, 'toTop', toTopListener, 'click'],
        [pathToBottomSign, titleToBottom, 'toBottom', toBottomListener, 'click'],
        [pathToMoveSign, titleMove, 'move', moveListener, (isMobile()) ? 'touchstart' : 'mousedown']
    ].forEach(properties => {
        let img = createImgForNote(div, id, properties[0], properties[1], properties[2], properties[3], properties[4]);
        div.append(img);
    });

    addShowEditDeleteListenerToNote(div);

    div.id = id;

    return div;
}

function createNote(id, text, titleDelete, titleEdit, titleToTop, titleToBottom, titleMove) {
    let flowJobVar = document.getElementById(flowJob);
    flowJobVar.append(createAndReturnNote(id, text, titleDelete, titleEdit, titleToTop, titleToBottom, titleMove));
}

function addJsToNotesAfterStaticLoad() {
    let map = new Map([
        ['delete', noteDeleteListener],
        ['edit', noteEditorPopupListener],
        ['toBottom', toBottomListener],
        ['toTop', toTopListener],
        ['move', moveListener]
    ]);

    [...document.getElementsByClassName(cssNote)].forEach(div => {
        [...div.getElementsByTagName('img')].forEach(img => {
            if (img.getAttribute('data-type') === 'move') {
                if (isMobile()) {
                    img.addEventListener('touchstart', () => {
                        map.get(img.getAttribute('data-type'))(div, div.id);
                    });
                } else {
                    img.addEventListener('mousedown', () => {
                        map.get(img.getAttribute('data-type'))(div, div.id);
                    });
                }
            } else {
                img.addEventListener('click', () => {
                    map.get(img.getAttribute('data-type'))(div, div.id);
                });
            }
        });

        addShowEditDeleteListenerToNote(div);
    });
}


let isSetTouchMovePrevent = false;

function preventDefault(event) {
    event.preventDefault();
    event.stopPropagation();
}


function addShowEditDeleteListenerToNote(note) {

    let touchstartX = 0;
    let touchendX = 0;
    let touchstartY = 0;
    let touchendY = 0;

    note.addEventListener('touchstart', function (startEvent) {
        touchstartX = startEvent.targetTouches[0].screenX;
        touchstartY = startEvent.targetTouches[0].screenY;
    }, false);


    note.addEventListener('touchmove', function (touchmoveEvent) {
        touchendX = touchmoveEvent.changedTouches[0].screenX;
        touchendY = touchmoveEvent.changedTouches[0].screenY;

        if (Math.abs(touchendY - touchstartY) < 20 && touchendX < touchstartX - 35) {
            note.classList.toggle(cssActive, true);
            let x = window.scrollX;
            let y = window.scrollY;
            if (!isSetTouchMovePrevent) {
                document.body.addEventListener('touchmove', preventDefault, {passive: false});
                isSetTouchMovePrevent = true;
            }
        }
    }, false);

    document.addEventListener('touchstart', event => {
        let hold = 0;

        if (event.target.classList.contains(cssEditDelteElement)) {
            hold = 150;
        }

        if (isSetTouchMovePrevent) {
            document.body.removeEventListener('touchmove', preventDefault, {passive: false});
            isSetTouchMovePrevent = false;
        }

        setTimeout(() => {
            note.classList.toggle(cssActive, false);
        }, hold);

    });
}

function toTopListener(div, id) {
    console.log('to top');
}

function toBottomListener(div, id) {
    console.log('to bottom');
}

function moveListener() {
    console.log('move');
}

function createImgForNote(div, id, src, title, dataType, listener, listenerType) {
    let img = document.createElement('img');
    img.setAttribute('src', src);
    img.setAttribute('title', title);
    img.setAttribute('data-type', dataType);
    img.classList.add(cssEditDelteElement);
    img.addEventListener(listenerType, () => {
        listener(div, id);
    });
    return img;
}

document.body.addEventListener('contextmenu', function (event) {
    if (isMobile()) {
        event.preventDefault();
    }
});


//DELETE NOTE BLOCK

let deletedNoteId = '';
let deletedNoteText = '';
let deletedNoteDeleteTitle = '';
let deletedNoteEditTitle = '';
let undoDeleteView = document.getElementById('undoDelete');
let undoDeleteTimeout = false;

async function noteDeleteListener(div, id) {
    //send request to server
    let response = await PostSender.postText(
        postStatus + '=' + postDeleteNote + '&' + postId + '=' + id + '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent)
    );

    deletedNoteId = id;
    deletedNoteText = div.innerText;
    deletedNoteDeleteTitle = div.getElementsByTagName('img')[0].title;
    deletedNoteDeleteTitle = div.getElementsByTagName('img')[1].title;

    undoDeleteView.classList.toggle(cssActive, true);
    undoDeleteView.classList.toggle('changeOpacity', true);
    let addNoOpacityTimeout = setTimeout(() => {
        undoDeleteView.classList.toggle('changeOpacity', false);
    }, 450);

    clearTimeout(undoDeleteTimeout);
    undoDeleteTimeout = setTimeout(() => {
        undoDeleteView.classList.toggle(cssActive, false);
    }, 4000);


    //delete from flowJob
    div.remove();
}


document.getElementById('content-wrapper').addEventListener('click', () => {

    let preventReactionOnDelClick = setTimeout(() => {
        if (!undoDeleteView.classList.contains('changeOpacity')) {
            clearTimeout(undoDeleteTimeout);
            undoDeleteView.classList.toggle(cssActive, false);
        }
    }, 200);

});


undoDeleteView.addEventListener('mouseenter', () => {
    // console.log('mouseenter');
    clearTimeout(undoDeleteTimeout);
});

undoDeleteView.addEventListener('mouseleave', () => {
    // console.log('mouseleave');
    undoDeleteTimeout = setTimeout(() => {
        undoDeleteView.classList.toggle(cssActive, false);
    }, 4000);
});

//UNDO DELETE NOTE BLOCK

function setUndoDeleteListeners() {
    document.getElementById('undoDeleteButton').addEventListener('click', async () => {
        //добавить удаление инфы после восстановления и удаление кнопки
        //почему b не сохраняется как символ
        let undoCreatedNote = createAndReturnNote(deletedNoteId, deletedNoteText, deletedNoteDeleteTitle, deletedNoteEditTitle);
        let undoNotesArray = [...document.getElementsByClassName(cssNote)];
        let noteBeforeUndoWouldBeInserted = false;

        let response = await PostSender.postText(
            postStatus + '=' + postUndoDeleteNode +
            '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent) +
            '&' + postId + '=' + deletedNoteId
        );

        if (!isJson(response)) {
            console.log(response);
            return;
        }

        if (response[0] === err) {
            jobWarningOn(response[1]);
            return;
        }

        for (let i = 0; i < undoNotesArray.length - 1; i++) {
            let noteBefore = undoNotesArray[i];
            let noteAfter = undoNotesArray[i + 1];

            if (parseInt(deletedNoteId) < parseInt(noteBefore.id)) {
                noteBeforeUndoWouldBeInserted = noteBefore;
                break;
            } else if (parseInt(deletedNoteId) > parseInt(noteBefore.id) && parseInt(deletedNoteId) < parseInt(noteAfter.id)) {
                noteBeforeUndoWouldBeInserted = noteAfter;
                break;
            }
        }

        if (noteBeforeUndoWouldBeInserted) {
            document.getElementById(flowJob).insertBefore(undoCreatedNote, noteBeforeUndoWouldBeInserted);
        } else {
            document.getElementById(flowJob).appendChild(undoCreatedNote);
        }

        deletedNoteId = '';
        deletedNoteText = '';
        deletedNoteDeleteTitle = '';
        deletedNoteEditTitle = '';
        clearTimeout(undoDeleteTimeout);
        undoDeleteView.classList.toggle(cssActive, false);

    });

}

setUndoDeleteListeners();


//EDIT NOTE BLOCK

let editNoteView = document.getElementById('editNote-view');
let editNoteCheckbox = document.getElementById('popup-input-editNote');
let editNoteTextId = 'popupEditNote-text';
let editNoteButtonId = 'popupEditNote-button';

function noteEditorPopupListener(div, id) {
    //проверить, что id предыдущей заметки отличается от id ранее редактирвоаной заметки,
    // скопировать данные из редактируемой заметки и вставить в окно editNote
    // console.log(div);
    // console.log(id);
    if (parseInt(editNoteView.getAttribute('data-processingId')) !== parseInt(id)) {
        // console.log(div.innerText);
        let editNoteText = document.getElementById(editNoteTextId);

        editNoteText.innerText = div.getElementsByTagName('span')[0].innerText;

        setTimeout(() => {
            editNoteText.focus();
        }, 200);

        //установить data-previousId в id редактируемой заметки - на случай, если пользователь наберет текст и случайно
        //закроет заметку - данные сохранятся, при условии, что он нажмет на редактирование той же заметки
        editNoteView.setAttribute('data-processingId', id);
    }

    //открыть окно editNote
    editNoteCheckbox.checked = true;
}

function setEditNoteSetListeners() {

    document.getElementById(editNoteButtonId).addEventListener('click', async () => {
        //id редактируемой заметки получаем из элемента editNoteView по атрибуту data-processingId
        let noteId = editNoteView.getAttribute('data-processingId');
        let noteText = document.getElementById(editNoteTextId).innerText;

        //Отправить новый текст заметки на сервер и получить ответ
        let response = await PostSender.postText(
            postStatus + '=' + postEditNote +
            '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent) +
            '&' + postId + '=' + noteId +
            '&' + postText + '=' + noteText
        );

        //Проверить ответ
        if (!isJson(response)) {
            console.log(response);

            return;
        }


        response = JSON.parse(response);


        // если ответ err - вывести окно предупреждения с ошибкой
        if (response[0] === err) {
            jobWarningOn(response[1]);

            return;
        }

        //Вставить напечатанный текст в редактируемую заметку
        document.getElementById(noteId).getElementsByTagName("span")[0].innerText = noteText;

        //закрыть окно
        switchOffAllCheckboxes();

        //очистить окно и обнулить data-processingId
        setTimeout(() => {
            document.getElementById(editNoteTextId).innerText = '';
            editNoteView.setAttribute('data-processingId', '');
        }, 10);
    });

}

setEditNoteSetListeners();


//CHANGE TITLE LANGUAGES OF NOTES

function changeTitleLanguagesOfNotes(deleteTitle, editTitle, toTopTitle, toBottomTitle, moveTitle) {
    [...document.getElementsByClassName(cssNote)].forEach(div => {
        div.getElementsByTagName('img')[0].setAttribute('title', deleteTitle);
        div.getElementsByTagName('img')[1].setAttribute('title', editTitle);
        div.getElementsByTagName('img')[2].setAttribute('title', toTopTitle);
        div.getElementsByTagName('img')[3].setAttribute('title', toBottomTitle);
        div.getElementsByTagName('img')[4].setAttribute('title', moveTitle);
    });
}




function deleteAllNotes() {
    [...document.getElementsByClassName(cssNote)].forEach(note => {
        note.remove();
    });
}


//__AUTHORIZATION SECTION__

let email = document.getElementById('email');
let password = document.getElementById('password');
let authorizeButtonId = 'authorizeButton';


function setAuthorizeButtonListener() {

    email = document.getElementById('email');
    password = document.getElementById('password');

    document.getElementById(authorizeButtonId).addEventListener('click', async () => {

        // console.log('authorize');

        loginWarningOff();

        // if (!validateEmail(email.value)) {
        //     loginWarningOn(userErrors.get(errorEmailNotValid));
        //     return;
        // }

        //send request
        let response = await PostSender.postText(
            postStatus + '=' + postAuthorization + '&' + postEmail + '=' + email.value + '&' + postPassword + '=' + password.value
        );

        if (!isJson(response)) {
            console.log(response);
        }

        response = JSON.parse(response);

        //expects:
        //array(
        // csrfToken, listOfNotes, flowLoginView, SettingsView, ControlView, TakenoteView, FindnoteView, languageCode
        // )

        if (response[0] === err) {
            loginWarningOn(response[1]);
            return;
        }

        //set csrfToken
        document.getElementById(csrfToken).setAttribute(csrfContent, response[0]);

        //turn login to job
        switchToJob();

        let noteList = response[1];

        //create notes
        if (Array.isArray(noteList) && noteList.length > 0) {

            for (let i = 0; i < noteList.length; i++) {
                createNote(noteList[i][0], noteList[i][1]);
            }

        }

        refillChangeLanguageNodes(
            [flowLoginView, settingsView, controlView, takenoteView, findnoteView, restorePasswordView, editNoteView, undoDeleteView,],
            [response[2], response[3], response[4], response[5], response[6], response[7], response[8], response[13]]
        );

        languages_dropActive();

        setActiveSameLanguageCode(response[9]);

        changeTitleLanguagesOfNotes(response[10], response[11], response[15], response[16], response[17]);
        changeTitleSettingsIcon(response[12]);
    });
}

setAuthorizeButtonListener();



function setRegisterButtonListener() {
    document.getElementById('registerButton').addEventListener('click', async () => {

        loginWarningOff();

        // if (!validateEmail(email.value)) {
        //     loginWarningOn(userErrors.get(errorEmailNotValid));
        //     return;
        // }

        let date = Date.now();

        //send request
        let response = await PostSender.postText(
            postStatus + '=' + postRegistration + '&' + postEmail + '=' + email.value + '&' + postPassword + '=' + password.value
        );

        // console.log(Date.now() - date);

        // console.log(response);

        if (!isJson(response)) {
            console.log(response);
        }

        response = JSON.parse(response);

        if (response[0] === err) {
            loginWarningOn(response[1]);
            return;
        }

        //set csrfToken
        document.getElementById(csrfToken).setAttribute(csrfContent, response[0]);

        //turn login to job
        switchToJob();

        //create first note
        createNote(response[1], response[2], response[3], response[4], response[6], response[7], response[8]);

    });
}

setRegisterButtonListener();



//RESTORE PASSWORD SETTINGS
let restorePasswordView = document.getElementById('restorePassword');

function setForgotPasswordListener() {

    restorePasswordView = document.getElementById('restorePassword');


    let forgotPasswordButton = document.getElementById('forgot-password');

    forgotPasswordButton.addEventListener('click', () => {
        switchOnCheckbox(document.getElementById('popup-input-restorePassword'));
    });


    let restorePasswordButton = document.getElementById('restorePassword-button');

    restorePasswordButton.addEventListener('click', async () => {

        let restorePasswordEmail = document.getElementById('restorePassword-email');

        if (!validateEmail(restorePasswordEmail.value)) {
            jobWarningOn(userErrors.get(errorEmailNotValid));
            return;
        }

        let response = await PostSender.postText(
            postStatus + '=' + postRestorePassword + '&' +
            postEmail + '=' + restorePasswordEmail.value
        );

        response = JSON.parse(response);

        if (response[0] === err) {
            jobWarningOn(response[1]);
            return;
        }

        jobWarningOn(response);

        restorePasswordEmail.value = '';
    });

}

setForgotPasswordListener();


//__POPUP SETTINGS SECTION__


let controlCheckbox = document.getElementById('popup-input-control');
let languageCheckbox = document.getElementById('popup-input-language');
let changepasswordCheckBox = document.getElementById('popup-input-changepassword');
let warningCheckbox = document.getElementById('jobWarning-text');

let settings = document.getElementById('settings');

settings.addEventListener('click', () => {
    settings.classList.toggle(cssActive, true);
});

document.body.addEventListener('click', () => {
    setSettingsWheelBack();
});

document.body.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        setSettingsWheelBack();
    }
});


function setSettingsWheelBack() {
    setTimeout(() => {
        if (
            !document.getElementById('popup-input-settings').checked &&
            !document.getElementById('popup-input-control').checked &&
            !document.getElementById('popup-input-language').checked &&
            !document.getElementById('popup-input-changepassword').checked
        ) {
            settings.classList.toggle(cssActive, false);
        }
    }, 200);
}


function setSettingsListeners() {

    controlCheckbox = document.getElementById('popup-input-control');
    languageCheckbox = document.getElementById('popup-input-language');
    changepasswordCheckBox = document.getElementById('popup-input-changepassword');
    warningCheckbox = document.getElementById('jobWarning-text');


    document.getElementById('m-ijn').addEventListener('click', () => {

        window.open('http://www.ijn.su/letme');

    });


    document.getElementById('control').addEventListener('click', () => {

        switchOnCheckbox(controlCheckbox);

    });


    document.getElementById('language-settings').addEventListener('click', () => {

        switchOnCheckbox(languageCheckbox);

    });

    document.getElementById('changepassword').addEventListener('click', () => {

        switchOnCheckbox(changepasswordCheckBox);

    });


    document.getElementById('exit').addEventListener('click', async () => {

        let response = await PostSender.postText(
            postStatus + '=' + postLogout + '&' + csrfToken + '=' + document.getElementById(csrfToken).content
        );


        if (parseInt(response) !== 200) {

            switchOnCheckbox(warningCheckbox);

            document.getElementById('jobWarning-text').innerText = response;

            return;
        }

        deleteStorageTempNote();

        window.open('/', '_self');

    });

}


setSettingsListeners();


//POPUP INPUT-CHANGEPASSWORD SECTION


function setChangepasswordListener() {

    changepasswordCheckBox = document.getElementById('popup-input-changepassword');

    document.getElementById('changepassword-button').addEventListener('click', async () => {
        let oldPassword = document.getElementById('oldpassword');
        let newPassword = document.getElementById('newpassword');

        if (!validatePassword(oldPassword.value) || !validatePassword(newPassword.value)) {
            jobWarningOn(userErrors.get(errorPasswordUnsafe));
            return;
        }

        let response = await PostSender.postText(
            postStatus + '=' + postChangepassword + '&' +
            'oldpassword' + '=' + oldPassword.value + '&' +
            'newpassword' + '=' + newPassword.value + '&' +
            csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent)
        );

        response = JSON.parse(response);

        if (response[0] === err) {
            jobWarningOn(response[1]);
            return;
        }

        jobWarningOn(response);

        oldPassword.value = '';
        newPassword.value = '';

    });



}


setChangepasswordListener();



//__POPUP INPUT-TAKENOTE SECTION__

let takeNoteCheckbox = document.getElementById('popup-input-takeNote');

let takeNoteTextNode = document.getElementById('takeNote-text');

const storageTempNote = 'storageTempNote';

function setTakeNoteListeners() {

    takeNoteCheckbox = document.getElementById('popup-input-takeNote');

    takeNoteTextNode = document.getElementById('takeNote-text');

    takeNoteTextNode.addEventListener('input', () => {
        localStorage.setItem(storageTempNote, takeNoteTextNode.innerText);
    });

    document.getElementById('takeNote-button').addEventListener('click', async () => {

        let text = takeNoteTextNode.innerText;

        let response = await PostSender.postText(postStatus + '=' + postTakeNote + '&' + postText + '=' + text + '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent));

        // console.log(response);

        response = JSON.parse(response);

        if (response[0] === err) {
            jobWarningOn(response[1]);
            return;
        }

        createNote(response[1], text, response[2], response[3], response[4], response[5], response[6]);

        takeNoteCheckbox.checked = false;

        takeNoteTextNode.innerText = '';

        deleteStorageTempNote();

    });
}

setTakeNoteListeners();


function getStorageTempNote() {
    return localStorage.getItem(storageTempNote);
}

function deleteStorageTempNote() {
    localStorage.removeItem(storageTempNote);
}

function insertStorageTempNote() {
    takeNoteTextNode.innerText = localStorage.getItem(storageTempNote);
}


//__POPUP INPUT-FINDNOTES SECTION__

let findNoteCheckbox = document.getElementById('popup-input-findNotes');

let findNoteText = document.getElementById('findNote-text');


function setFindNodesListeners() {

    findNoteCheckbox = document.getElementById('popup-input-findNotes');

    findNoteText = document.getElementById('findNote-text');

    document.getElementById('findNote-button').addEventListener('click', async () => {

        let response = await PostSender.postText(postStatus + '=' + postFindNotes + '&' + postText + '=' + findNoteText.innerText + '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent));

        console.log('findNote');

        response = JSON.parse(response);

        if (response[0] === err) {
            jobWarningOn(response[1]);
            return;
        }

        response = response[1];

        deleteAllNotes();

        response.forEach(noteInfo => {
            createNote(noteInfo[0], noteInfo[1]);
        });

        findNoteCheckbox.checked = false;

        findNoteText.innerText = '';

    });
}

setFindNodesListeners();


//LANGUAGE BLOCK
const language = 'language';

const languageHolder = document.getElementById('language-holder');

const languageCover = document.getElementById('language-cover');

// const languages = Array.from(languageHolder.getElementsByClassName(language));
//из-за слияния дву представлений - заплатка вместо стандартной languages - общая для всех классов language:
const languages = Array.from(document.getElementsByClassName(language));

const languageCode = 'languageCode';

//front-end decoration

function languages_dropActive() {
    dropClass(languages, cssActive);
}

languageHolder.addEventListener('click', () => {
    languageHolder.classList.toggle(cssActive);
});

languageCover.addEventListener('click', () => {
    languageHolder.classList.toggle(cssActive, false);
});

document.body.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            languageHolder.classList.toggle(cssActive, false);
        }
    }
);



languages.forEach(node => {
    node.addEventListener('click', () => {
        if (node.classList.contains(cssActive)) {
            return;
        }
        languages_dropActive();
        node.classList.add(cssActive);
        //ниже - заплатка для решения проблемы слияния двух представлений
        setActiveSameLanguageCode(node.getAttribute('data-languagecode'));

        languageServerCommunication(node);
    });
});

//для решения проблемы слияния двух представлений
function setActiveSameLanguageCode(languageCode) {
    languages.forEach(node => {
        if (node.getAttribute('data-languagecode') === languageCode) {
            node.classList.toggle(cssActive, true);
        }
    });
}

//server communication
//flowLoginView, settingsView, controlView, takenoteView, findnoteView

const flowLoginView = document.getElementById('flowLogin');
const settingsView = document.getElementById('settings-view');
const controlView = document.getElementById('control-view');
const takenoteView = document.getElementById('takenote-view');
const findnoteView = document.getElementById('findnote-view');
const changepasswordView = document.getElementById('changepassword-view');


async function languageServerCommunication(node) {

    let response = await PostSender.postText(postStatus + '=' + 'changeLanguage' + '&' + languageCode + '=' + node.getAttribute('data-' + languageCode) + '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent));

    if (!isJson(response)) {
        console.log('not json');

        console.log(response);

        return;
    }

    response = JSON.parse(response);

    if (response[0] === errServer) {

        switchOnCheckbox(warningCheckbox);

        document.getElementById('jobWarning-text').innerText = response[1];

        return;
    }

    refillChangeLanguageNodes(
        [flowLoginView, settingsView, controlView, takenoteView, findnoteView, changepasswordView, restorePasswordView, editNoteView, undoDeleteView],
        [response[0], response[1], response[2], response[3], response[4], response[5], response[6], response[7], response[11]]
    );

    changeTitleLanguagesOfNotes(response[8], response[9], response[13], response[14], response[15]);

    changeTitleSettingsIcon(response[10]);

    changeExampleLanguage(response[12]);

    languageCheckbox.checked = false;

}





//__POPUP JOBWARNING SECTION__

let jobWarningCheckbox = document.getElementById('popup-input-jobWarning');

let jobWarningText = document.getElementById('jobWarning-text');

function jobWarningOn(text) {
    jobWarningCheckbox.checked = true;

    jobWarningText.innerText = text;
}

function jobWarningOff(text) {
    jobWarningCheckbox.checked = false;

    jobWarningText.innerText = '';
}





//__MAIN METHOD SECTION__
addJsToNotesAfterStaticLoad();

insertStorageTempNote();

document.body.classList.remove('preload');


//__APP CLICK LISTENER__
var bodyClickHolder = 0;

document.getElementById('main').addEventListener('click', async event => {

    if (appNode.classList.contains(cssLogin)) {
        return;
    }

    bodyClickHolder++;

    let tempHolder = bodyClickHolder;

    setTimeout(() => {
        if (bodyClickHolder !== tempHolder) {
            return;
        }

        if (tempHolder === 2) {

            switchOnCheckbox(takeNoteCheckbox);

            setTimeout(() => {
                document.getElementById('takeNote-text').focus();
            }, 200);

        } else if (tempHolder >= 3) {

            switchOnCheckbox(findNoteCheckbox);

            setTimeout(() => {
                document.getElementById('findNote-text').focus();
            }, 200);

        }

        bodyClickHolder = 0;

    }, 350);


});


let isSendScroll = false;

document.addEventListener('scroll', async () => {


    if (document.documentElement.clientHeight + document.documentElement.scrollTop >= document.documentElement.scrollHeight) {

        if (isSendScroll) {
            return;
        }

        isSendScroll = true;

        let response = await PostSender.postText(postStatus + '=' + postGetNotes + '&' + postText + '=' + [...document.getElementById(flowJob).children].length + '&' + csrfToken + '=' + document.getElementById(csrfToken).getAttribute(csrfContent));

        // console.log(response);

        response = JSON.parse(response);

        // console.log(response);

        if (response[0] === err) {
            jobWarningOn(response[1]);
            return;
        }

        response = response[1];

        if (!response.length) {
            return;
        }

        response.forEach(noteInfo => {
            createNote(noteInfo[0], noteInfo[1]);
        });

        isSendScroll = false;

    }

});

let beforePressedKey = '';

document.body.addEventListener('keydown', function (e) {

        if (e.key === 'Escape') {

            let array = [...document.querySelectorAll("input[type='checkbox']")];

            for (let i = 0; i < array.length; i++) {
                let element = array[i];

                if (element.id === 'popup-input-jobWarning' && element.checked) {
                    element.checked = false;
                    return;
                }
            }

            array.forEach(element => {
                element.checked = false;
            });

            languageHolder.classList.toggle(cssActive, false);

        } else if (e.key === 'Enter') {

            if (beforePressedKey === 'Shift') {
                return;
            }

            e.preventDefault();

            let pushElements = [...document.getElementsByClassName(cssPushElement)];
            let authorizeButton = document.getElementById(authorizeButtonId);

            for (let i = 0; i < pushElements.length; i++) {
                let element = pushElements[i];

                if (getComputedStyle(element).visibility === 'visible') {
                    // console.log(element);
                    element.click();
                    return;
                }
            }

            if (getComputedStyle(authorizeButton).visibility === 'visible') {
                // console.log('authorizeButton');
                authorizeButton.click();
            }

        } else {
            beforePressedKey = e.key;
        }
    }
);

document.body.addEventListener('keyup', function (e) {
    if (e.key === 'Shift') {
        beforePressedKey = '';
    }
});