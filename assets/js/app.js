/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require('../css/app.css');

import $ from 'jquery';
import stripe from 'stripe-checkout';

$(document).ready(() => {
    let button = $('#payment-btn');
    if (button) {
        button.click((e) => {
            e.preventDefault();
            pay(button.attr('data-key'), button.attr('data-title'));
        });
    }
});

function pay(key, title) {
    let placeholder = $('#stripe-token');
    let form = $('#payment-form');
    let checkout = stripe();
    checkout({
        key: key,
        locale: 'auto',
        name: title,
        description: title,
        token: (token) => {
            placeholder.val(token.id);
            form.submit();
        }
    });
}
