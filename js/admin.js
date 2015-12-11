function getContactFormArrangements(formID, subdomain)
{
    subdomain = 'demo'; //DEBUG

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://' + subdomain + '.recras.nl/api2.php/contactformulieren/' + formID + '/arrangementen');
    xhr.responseType = 'json';
    xhr.send(null);
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4) {
            if (!xhr.response) {
                alert('Could not connect to your Recras'); //TODO
            } else {
                var response = JSON.parse(xhr.response);
                if (response.success) {
                    //TODO
                    var contactFormArrangements = [];
                    xhr.responseText.forEach(function(item){
                        contactFormArrangements.push(item.arrangement_id);
                    });
                    disableUnsetArrangements(contactFormArrangements);
                } else if (response.error) {
                    alert('Error');
                    //TODO
                    console.log(response);
                } else {
                    alert('Error');
                    //TODO
                    console.log(response);
                }
            }
        }
    };
}

function disableUnsetArrangements(arrangementIDs)
{
    var options = document.getElementById('arrangement_id').getElementsByTagName('option');
    console.log(arrangementIDs);
    for (var i = 0; i < options.length; i++) {
        options[i].disabled = (arrangementIDs.indexOf(parseInt(options[i].value,10)) > -1);
    }
}
