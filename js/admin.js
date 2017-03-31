function getContactFormArrangements(formID, subdomain)
{
    if (!formID) {
        return false;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://' + subdomain + '.recras.nl/api2.php/contactformulieren/' + formID + '/arrangementen');
    xhr.responseType = 'json';
    xhr.send();
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4) {
            if (!xhr.response) {
                alert(recras_l10n.no_connection);
            } else {
                var contactFormArrangements = [];
                xhr.response.forEach(function(item){
                    contactFormArrangements.push(item.arrangement_id);
                });
                disableNotAllowed(contactFormArrangements);
            }
        }
    };
}

/**
 * Disable arrangements that are not allowed for this contact form
 *
 * @param {Array} arrangementIDs
 */
function disableNotAllowed(arrangementIDs)
{
    var options = document.getElementById('arrangement_id').getElementsByTagName('option');
    for (var i = 0; i < options.length; i++) {
        options[i].disabled = (arrangementIDs.indexOf(parseInt(options[i].value,10)) === -1);
    }
}
