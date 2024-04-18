import './MyJs.js';
import $ from 'jquery'; // Importez jQuery en premier
import './select2.js';
// Sélectionnez tous les champs avec la classe select2 et initialisez Select2
$('.select2').select2();

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/MyStyle.css';
import './styles/select2.css';
import './styles/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

