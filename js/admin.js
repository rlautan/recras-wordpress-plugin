function getContactFormArrangements(formID, subdomain)
{
    /*var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://lokaal' + '.recras.nl/api2.php/contactformulieren/' + formID + '/arrangementen'); //DEBUG
    xhr.send(null);
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4) {
            var response = JSON.parse(xhr.response);
            if (response.success) {
                //TODO
                console.log(response);
            } else if (response.error) {
                //TODO
                console.log(response);
            } else {
                //TODO
                console.log(response);
            }
        }
    };*/

    ///// DEBUG \\\\\
    var responseBody = [{"id":6,"arrangement_id":2,"Arrangement":{"id":2,"arrangement":"Klimmen en bbq","ontvangstlocatie":"","onlineboeking_contactformulier_id":"2","onlineboeking_minimaal_vooruit":"PT00H00M0S","onlineboeking_maximaal_vooruit":null,"onlineboeking_standaardbijlagen_meesturen":false,"onlineboeking_pdf_template_id":null,"bedrijf_id":"1","created_by":null,"updated_by":null,"version":null,"deleted_at":null}},{"id":5,"arrangement_id":3,"Arrangement":{"id":3,"arrangement":"Kinderfeest Klimmen","ontvangstlocatie":"","onlineboeking_contactformulier_id":"3","onlineboeking_minimaal_vooruit":"PT00H00M0S","onlineboeking_maximaal_vooruit":null,"onlineboeking_standaardbijlagen_meesturen":false,"onlineboeking_pdf_template_id":"3","bedrijf_id":"1","created_by":null,"updated_by":null,"version":null,"deleted_at":null}},{"id":8,"arrangement_id":4,"Arrangement":{"id":4,"arrangement":"Voor elk wat wils en een bbq","ontvangstlocatie":"","onlineboeking_contactformulier_id":"2","onlineboeking_minimaal_vooruit":"PT00H00M0S","onlineboeking_maximaal_vooruit":null,"onlineboeking_standaardbijlagen_meesturen":false,"onlineboeking_pdf_template_id":null,"bedrijf_id":"1","created_by":null,"updated_by":null,"version":null,"deleted_at":null}},{"id":7,"arrangement_id":5,"Arrangement":{"id":5,"arrangement":"Solex plus","ontvangstlocatie":"","onlineboeking_contactformulier_id":null,"onlineboeking_minimaal_vooruit":"PT0S","onlineboeking_maximaal_vooruit":null,"onlineboeking_standaardbijlagen_meesturen":false,"onlineboeking_pdf_template_id":null,"bedrijf_id":"1","created_by":null,"updated_by":null,"version":null,"deleted_at":null}},{"id":10,"arrangement_id":7,"Arrangement":{"id":7,"arrangement":"Actieve Familiedag","ontvangstlocatie":"","onlineboeking_contactformulier_id":null,"onlineboeking_minimaal_vooruit":"PT00H00M0S","onlineboeking_maximaal_vooruit":null,"onlineboeking_standaardbijlagen_meesturen":false,"onlineboeking_pdf_template_id":null,"bedrijf_id":null,"created_by":null,"updated_by":"2","version":"10","deleted_at":null}},{"id":11,"arrangement_id":8,"Arrangement":{"id":8,"arrangement":"2 daags vergader arrangement","ontvangstlocatie":"","onlineboeking_contactformulier_id":null,"onlineboeking_minimaal_vooruit":"PT00H00M0S","onlineboeking_maximaal_vooruit":null,"onlineboeking_standaardbijlagen_meesturen":false,"onlineboeking_pdf_template_id":null,"bedrijf_id":"1","created_by":null,"updated_by":"2","version":"7","deleted_at":null}}];
    ///// DEBUG \\\\\

    var contactFormArrangements = [];
    responseBody.forEach(function(item){
        contactFormArrangements.push(item.arrangement_id);
    });
    disableUnsetArrangements(contactFormArrangements);
}

function disableUnsetArrangements(arrangementIDs)
{
    var options = document.getElementById('arrangement_id').getElementsByTagName('option');
    console.log(arrangementIDs);
    for (var i = 0; i < options.length; i++) {
        options[i].disabled = (arrangementIDs.indexOf(parseInt(options[i].value,10)) > -1);
    }
}
