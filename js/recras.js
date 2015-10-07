function submitRecrasForm(formID, subdomain, submitScript)
{
    var i;

    var errors = document.querySelectorAll('.recras-error');
    for (i = 0; i < errors.length; i++) {
        errors[i].parentNode.removeChild(errors[i]);
    }

    var formElements = document.getElementById('recras-form' + formID).querySelectorAll('input, textarea, select');
    var payload = {
        elements: {},
        formID: formID,
        subdomain: subdomain
    };
    for (i = 0; i < formElements.length; i++) {
        if (formElements[i].type !== 'submit') {
            if (formElements[i].value === '' && formElements[i].required === false) {
                formElements[i].value = null;
            }
            payload['elements'][formElements[i].name] = formElements[i].value;
        }
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', submitScript);
    xhr.send(JSON.stringify(payload));
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4) {
            var response = JSON.parse(xhr.response);
            if (response.success) {
                alert(recras_l10n.sent_success);
            } else if (response.error) {
                var errors = response.error.messages;
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        document.querySelector('[name="' + key + '"]').parentNode.insertAdjacentHTML('beforeend', '<span class="recras-error">' + errors[key] + '</span>');
                    }
                }
                alert(recras_l10n.sent_error);
            } else {
                console.log('Unknown response: ', response);
            }
        }
    };
    return false;
}
