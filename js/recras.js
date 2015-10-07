function submitRecrasForm(formID, subdomain, submitScript)
{
    var formElements = document.getElementById('recras-form' + formID).querySelectorAll('input, textarea, select');
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
    var xhr = new XMLHttpRequest();
    xhr.open('POST', submitScript);
    xhr.send(JSON.stringify(payload));
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4) {
            var response = JSON.parse(xhr.response);
            if (response.success) {
                alert(recras_l10n.sent_success);
            } else if (response.error) {
                alert(recras.l10n.sent_error);
                console.log(response.error.messages);
            } else {
                console.log('Unknown response: ', response);
            }
        }
    };
    return false;
}
