// The photos of an album open in a lightbox in which you can go to the next and previous photo
// (association/photos/show.blade.php). Magnific Popup needs jQuery, which only this page loads
import $ from 'jquery';
import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';

$('#album-photos').magnificPopup({
    delegate: '.photo-wall__link',
    type: 'image',
    gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [1, 3],
    },
});
