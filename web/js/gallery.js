$(document).ready(function() {

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // Sur mobile, l'affichage sera différent (brightness baissée, titre visible de suite)
        $(".image-gallery").addClass("image-gallery-mobile");
        $(".image-title").addClass("title-mobile");
    } else { // Sur PC, les titres seront affichés par un hover qui assombrira l'image

        $("div.presentation-image").hover(function(e) { // Entrée dans le hover d'une image de la galerie => assombrissement de l'image + affichage du nom du projet
            let $target = $(e.target).parent(),
                $title = $target.find('h2');

            $title.fadeTo(200,1); // On affiche le titre


        }, function(e) { // Sortie du hover
            let $target = $(e.target).parent(),
                $title = $target.find('h2'),
                $img = $target.find('img');

            $title.fadeTo(200,0); // On efface le titre

            $img.animate({ "-webkit-filter":"brightness(100%)"}, 200, "swing", function() {
            });
        });
    }

    const images_in_gallery = document.querySelectorAll(".image-gallery");
    const config = {
        rootMargin: "20px",
        threshold:0.2
    }


    let observer = new IntersectionObserver(onIntersection, config);
    images_in_gallery.forEach(image => {
       observer.observe(image);
    });

    function onIntersection(entries) {
        entries.forEach(entry => {
            if(entry.intersectionRatio > 0) {
                observer.unobserve(entry.target);
                preloadImage(entry.target);
            }
        })
    }

    function preloadImage(image) {
        let src = image.dataset.src;

        recuperationImage(src).then(() => {
            appliquerImage(image, src);
        });


    }

    function recuperationImage(url) {
        return new Promise((resolve, reject) => {
           const image = new Image();
           image.src = url;
           image.onload = resolve;
           image.onerror = reject;

        });
    }

    function appliquerImage(image, src) {

        image.classList.remove("loading");
        image.src = src;
    }



});