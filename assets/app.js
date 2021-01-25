/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */


// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
// app.js

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

document.querySelector("#watchlist").addEventListener('click', addToWatchlist);

function addToWatchlist(event) {

    event.preventDefault();

// Get the link object you click in the DOM
    let watchlistLink = event.currentTarget;
    let link = watchlistLink.href;
// Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
        // Extract the JSON from the response
        .then(res => res.json())
        // Then update the icon
        .then(function(res) {
            let watchlistIcon = watchlistLink.firstElementChild;
            if (res.isInWatchlist) {
                watchlistIcon.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
                watchlistIcon.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
            } else {
                watchlistIcon.classList.remove('fas'); // Remove the .fas (full heart) from classes in <i> element
                watchlistIcon.classList.add('far'); // Add the .far (empty heart) from classes in <i> element
            }
        });
}


console.log('Hello Webpack Encore! Edit me in assets/app.js');
