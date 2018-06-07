var locale = window.location.pathname.split("/")[1];

$(":checkbox:checked.check-specifictechnology").prop("checked", false); // We uncheck all technology checkboxes in case of a "soft" refresh (Hello, Firefox...)

$("input[name='check-all']").prop("checked", true) // Then, we ensure the "All" chexkbox is checked

$(function() {

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

        $.get(`/php/functions.php?locale=${locale}`, {"technos": arr_technos}, function(res) { // arr_technos will be empty if the "All" checkbox is checked
            let res_json = JSON.parse(res);

            let $ul_projets = $("#projets-concernes"),
                $li_projet;
            $ul_projets.html("");

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
