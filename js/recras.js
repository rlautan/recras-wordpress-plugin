function removeElsWithClass(className)
{
    var els = document.querySelectorAll('.' + className);
    for (var i = 0; i < els.length; i++) {
        els[i].parentNode.removeChild(els[i]);
    }
}

function submitRecrasForm(formID, subdomain, basePath)
{
    removeElsWithClass('recras-error');

    var formEl = document.getElementById('recras-form' + formID);
    var formElements = formEl.querySelectorAll('input, textarea, select');
    var payload = {
        elements: {},
        formID: formID,
        subdomain: subdomain
    };
    for (var i = 0; i < formElements.length; i++) {
        if (formElements[i].type !== 'submit') {
            if (formElements[i].value === '' && formElements[i].required === false) {
                formElements[i].value = null;
            }
            payload['elements'][formElements[i].name] = formElements[i].value;
        }
    }

    formEl.querySelector('[type="submit"]').parentNode.insertAdjacentHTML('beforeend', '<img src="' + basePath + 'editor/loading.gif" alt="' + recras_l10n.loading + '" class="recras-loading">');

    var xhr = new XMLHttpRequest();
    xhr.open('POST', basePath + 'submit.php');
    xhr.send(JSON.stringify(payload));
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4) {
            removeElsWithClass('recras-loading');
            var response = JSON.parse(xhr.response);
            if (response.success) {
                formEl.querySelector('[type="submit"]').parentNode.insertAdjacentHTML('beforeend', '<p class="recras-success">' + recras_l10n.sent_success + '</p>');
                //alert(recras_l10n.sent_success);
            } else if (response.error) {
                var errors = response.error.messages;
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        formEl.querySelector('[name="' + key + '"]').parentNode.insertAdjacentHTML('beforeend', '<span class="recras-error">' + errors[key] + '</span>');
                    }
                }
                formEl.querySelector('[type="submit"]').parentNode.insertAdjacentHTML('beforeend', '<p class="recras-error">' + recras_l10n.sent_error + '</p>');
                //alert(recras_l10n.sent_error);
            } else {
                console.log('Unknown response: ', response);
            }
        }
    };
    return false;
}
