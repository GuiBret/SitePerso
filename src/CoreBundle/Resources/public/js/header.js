var tab_perf = []; // Tableau évaluant performances
var $navbar = $(".navbar");

$(document).ready(function() {

    $(window).scroll(scrollThenFix);

    $("#dropdown-projets").on("show.bs.dropdown", function(e) {
        e.stopPropagation();
        $(this).find(".dropdown-menu").first().stop(true, true).slideDown({duration: 250, easing: "swing"});
    });

    $("#dropdown-projets").on("hide.bs.dropdown", function(e) {
        e.stopPropagation();
        $(this).find(".dropdown-menu").first().stop(true, true).slideUp(200);
    });


    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // Sur mobile, on déclenchear l'affichage du panneau par un clic
        $("#curr-language").on("click", function(e) {
            e.preventDefault();

            if($("#language-selector").css("display") === "block") {
                $("#language-selector").hide();
            } else {
                $("#language-selector").show();
            }
        });
    } else { // Sur PC, on déclenchera l'affichage du panneau par un hober

        $(".item-container-langue").mouseenter(function(e) {
            var $item = $(e.target),
                $link = $item.find('a');

            $item.addClass("toggled-element");
            $link.addClass("toggled-link");
            $("#language-selector").slideToggle(200, function() {
            });
        });

    }



    /* Double navbar */

    $( '.dropdown-menu a.dropdown-toggle' ).on( 'click', function ( e ) {
        var $el = $( this );
        var $parent = $( this ).offsetParent( ".dropdown-menu" );
        if ( !$( this ).next().hasClass( 'show' ) ) {
            $( this ).parents( '.dropdown-menu' ).first().find( '.show' ).removeClass( "show" );
        }
        var $subMenu = $( this ).next( ".dropdown-menu" );
        $subMenu.toggleClass( 'show' );

        $( this ).parent( "li" ).toggleClass( 'show' );

        $( this ).parents( 'li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function ( e ) {
            $( '.dropdown-menu .show' ).removeClass( "show" );
        } );

        if ( !$parent.parent().hasClass( 'navbar-nav' ) ) {
            $el.next().css( { "top": "0", "left": $parent.outerWidth() - 4 } );
        }

        return false;
    } );



});

function scrollThenFix() { // Fonction créant scroll then fixed (

    let scrollToTop = $(this).scrollTop(); // Fonction renvoyant le nb de pixels entre le haut de l'écran et le haut de la page

    if(scrollToTop > ($(window).height() * .35) + 45 + 150) { /* 35% : hauteur du header, 45px : hauteur de la navbar */
        if(!$navbar.hasClass("nav-fixed")) { // Léger gain de perf, proportionnel au temps
            $navbar.addClass("nav-fixed");
        }

    } else {
        if($navbar.hasClass("nav-fixed")) {
            $navbar.removeClass("nav-fixed");
        }

    }

    function hideLanguagePanel(e) {
        "use strict";

        let $langSelectElem = $("#language-selector");
        $langSelectElem.hide(200);

        $("#language-selector").off("mouseenter");
        $("#language-selector").off("mouseleave");
    }

}
