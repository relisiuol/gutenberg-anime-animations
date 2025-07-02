import anime from "animejs";

document.addEventListener( 'DOMContentLoaded', () => {
    const els = document.querySelectorAll( '[data-gaa-animation]' );
    els.forEach( ( el ) => {
        switch ( el.dataset.gaaAnimation ) {
            case 'fadeIn':
                anime({
                    targets: el,
                    opacity: [ 0, 1 ],
                    duration: 1000,
                    easing: 'easeOutQuad'
                });
                break;
            case 'slideUp':
                anime({
                    targets: el,
                    translateY: [ 50, 0 ],
                    opacity: [ 0, 1 ],
                    duration: 1000,
                    easing: 'easeOutQuad'
                });
                break;
            case 'scaleIn':
                anime({
                    targets: el,
                    scale: [ 0.8, 1 ],
                    opacity: [ 0, 1 ],
                    duration: 1000,
                    easing: 'easeOutBack'
                });
                break;
        }
    } );
} );
