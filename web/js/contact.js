$(document).ready(function() {

    $("button#btn_submit_email").on("click", function(e) {
        e.preventDefault();

        let data = {},
            form_data = $("#contact-form :input").serializeArray().reduce(function(obj, item) {
            data[item.name] = item.value;
        }, 0),
            message_code = verifForm(data),
            lang = $("html").attr("lang");

        if(message_code === 0) { // Toutes vérifications faites, validité
            $.ajax({
                type:"POST",
                url: Routing.generate("ajax-contact"),
                data:data
            }).done(function(data) {
                showMessage(0);
            }).fail(function() {
            });
        } else {
            showMessage(message_code);
        }


    });

});

function verifForm($form_data) { // Fonction vérifiant la validité du formulaire
    var message_code = 0; // Code qui sera envoyé à la fct showMessage lui permettant de savoir quoi afficher

    /* Codes message :
        - 0 : OK
        - 1 : Email vide
        - 2 : Nom vide
        - 3 : Pas un email
        - 4 : Message vide
    */

    if($form_data["email"].length === 0) { // Message vide
        message_code = 1
    } else if($form_data["nom"].length === 0) {
        message_code = 2;
    } else if(!verifRegexEmail($form_data["email"])) {
        message_code = 3;
    } else if($form_data["message"].length === 0) {
        message_code = 4;
    }

    return message_code;


}

function verifRegexEmail(email) {
    var regex_email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return email.match(regex_email);
}
function showMessage(err_type) {
    var message = "";

    switch(err_type) {
        case 0:

            message = "Le message a été envoyé avec succès.";


        break;
        case 1:
            message = "Le champ \"E-Mail\" est vide.";
        break;
        case 2:
            message = "Le champ \"Nom\" est vide.";
        break;
        case 3:
            message = "Le champ entré n\'est pas un e-mail";
        break;
        case 4:
            message = "Le champ \"Message\" est vide.";
        break;


    }
    $("#popup-message").html(message);
    $("#popup-message").animate(
        {
            opacity:0.85
        }, 2500, function() {
            $("#popup-message").animate(
            {
                opacity:0
            }, 1000, function() {
            });
        });
}
