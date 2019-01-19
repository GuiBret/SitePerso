


var locale = window.location.pathname.split("/")[1];

$(":checkbox:checked.check-specifictechnology").prop("checked", false); // We uncheck all technology checkboxes in case of a "soft" refresh (Hello, Firefox...)

$("input[name='check-all']").prop("checked", true) // Then, we ensure the "All" chexkbox is checked

$(function() {

    //const routes = require('../../public/js/fos_js_routes.json');


    Routing.setRoutingData(JSON.parse('{"base_url":"","routes":{"ajax-competences":{"tokens":[["text","\/ajax-competences"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]}},"prefix":"","host":"localhost","port":"","scheme":"http"}'));
    // Routing.generate('rep_log_list');

    $('a[data-toggle="tooltip"]').tooltip({
        animated:"fade",
        placement:"left",
        html: true
    });
    $(".check-techno").on("change", function() {
        let arr_technos = []; // Array which will be sent to the PHP in order to select the correct projects

        if($(this).attr("name") === "check-all") { /* If the "All" checkbox is clicked, we uncheck all other checkboxes (with class "check-specifictechnology */
            if($(this).is(":checked")) {

                $('.check-specifictechnology').prop("checked", false);
            }
        } else {
            if($("input[name='check-all']").is(":checked")) {
                $("input[name='check-all']").prop("checked", false);
            }
            if($(".check-specifictechnology:checked").length === 0) { // If all technologies are unchecked, we check the "All" so that we get all existing projects
                $("input[name='check-all']").prop("checked", "true");
            }

            $(".check-specifictechnology:checked").each(function() {
                arr_technos.push(`\"${$(this).prop('name')}\"`);
            });
        }


        let lang = $("html").attr("lang");
        $.get("/" + lang + Routing.generate('ajax-competences', {"technos": arr_technos}), function(res_json) {
            res_json = JSON.parse(res_json);


            let $ul_projets = $("#projets-concernes"),
                $li_projet;
            $ul_projets.html("");

            // Pour chaque projet, on va créer un <li> et <a>
            for(let projet of res_json) {
                $li_projet = $(`<li><a data-toggle="tooltip" title="<img src='/img/screen-${projet.nom_short}.png')' class='tooltip-image' /><div class='tooltip-overlay'><span class='tooltip-title'>${projet.nom}</span><p>${projet.texte}...</p></div>" href='/fr/projets/${projet.nom_short}'>${projet.nom}</a></li>`);$
                $ul_projets.append($li_projet);

            }

            $('a[data-toggle="tooltip"]').tooltip({
                animated:"fade",
                placement:"left",
                html: true
            })

        });

    });

});
