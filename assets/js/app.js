/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "../css/app.scss";

var $ = require("jquery");

global.$ = global.jQuery = $;

require("bootstrap");
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';


// <a href="{{path('admin_comment_delete', {'id' : comment.id})}}" data-action="delete"
//   data-target="#block_"><i class="fas fa-trash mx-1"></i></a>


$(document).ready(function () {
  $('[data-action="delete"]').on("click", function () {
    const target = this.dataset.target;
    $(target).remove();
  });
});
// essaie ajax
$(document).ready(function () {
  //On écoute le "click" sur le bouton ayant la classe "modal-trigger"
  $('.modal-trigger').click(function () {
    //On initialise les modales materialize
    $('.modal').modal();
    //On récupère l'url depuis la propriété "Data-target" de la balise html a
    url = $(this).attr('data-target');
    //on fait un appel ajax vers l'action symfony qui nous renvoie la vue
    $.get(url, function (data) {
      //on injecte le html dans la modale
      $('.modal-content').html(data);
      //on ouvre la modale
      $('#modalReport').modal('open');
    });
  })
});

// let villeChoisie;

// if ("geolocation" in navigator) {
//   navigator.geolocation.watchPosition((position) => {

//     const url = 'https://api.openweathermap.org/data/2.5/weather?lat='
//       + position.coords.latitude + '&lon='
//       + position.coords.longitude + '&appid=dc8c9152e8adaad0ec8bf635818c0d42&units=metric';

//     let requete = new XMLHttpRequest(); // Nous créons un objet qui nous permettra de faire des requêtes
//     requete.open('GET', url); // Nous récupérons juste des données
//     requete.responseType = 'json'; // Nous attendons du JSON
//     requete.send(); // Nous envoyons notre requête

//     // Dès qu'on reçoit une réponse, cette fonction est executée
// requete.onload = function () {
//   if (requete.readyState === XMLHttpRequest.DONE) {
//     if (requete.status === 200) {
//       let reponse = requete.response;
//           // console.log(reponse);
//           let temperature = reponse.main.temp;
//           let ville = reponse.name;
//           // console.log(temperature);
//           document.querySelector('#temperature_label').textContent = temperature;
//           document.querySelector('#ville').textContent = ville;
//         }
//         else {
//           alert('Un problème est intervenu, merci de revenir plus tard.');
//         }
//       }
//     }
//   }, erreur, options);

//   var options = {
//     enableHighAccuracy: true
//   }
// }
// else {
//   villeChoisie = "saint-saulve";
//   recevoirTemperature(villeChoisie);
// }

// let changerDeVille = document.querySelector('#changer');
// changerDeVille.addEventListener('click', () => {
//   villeChoisie = prompt('Quelle ville souhaitez-vous voir ?');
//   recevoirTemperature(villeChoisie);
// });

// function erreur() {
//   villeChoisie = "Nimes";
//   recevoirTemperature(villeChoisie);
// }

// function recevoirTemperature(ville) {
//   const url = 'https://api.openweathermap.org/data/2.5/weather?q=' + ville + '&appid=dc8c9152e8adaad0ec8bf635818c0d42&units=metric';

//   let requete = new XMLHttpRequest(); // Nous créons un objet qui nous permettra de faire des requêtes
//   requete.open('GET', url); // Nous récupérons juste des données
//   requete.responseType = 'json'; // Nous attendons du JSON
//   requete.send(); // Nous envoyons notre requête

//   // Dès qu'on reçoit une réponse, cette fonction est executée
//   requete.onload = function () {
//     if (requete.readyState === XMLHttpRequest.DONE) {
//       if (requete.status === 200) {
//         let reponse = requete.response;
//         // console.log(reponse);
//         let temperature = reponse.main.temp;
//         let ville = reponse.name;
//         // console.log(temperature);
//         document.querySelector('#temperature_label').textContent = temperature;
//         document.querySelector('#ville').textContent = ville;
//       }
//       else {
//         alert('Un problème est intervenu, merci de revenir plus tard.');
//       }
//     }
//   }
// }

$('.carousel').carousel({
  interval: 5000
})