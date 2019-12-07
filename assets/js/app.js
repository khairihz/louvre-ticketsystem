/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require('../css/app.css');
require('bootstrap');
require('bootstrap-datepicker');

import $ from 'jquery';

let currentYear = (new Date()).getUTCFullYear();

const NotOpenedDates = (year) => {
    let newYearDay = new Date(Date.UTC(year, 0, 1));
    let laborDay = new Date(Date.UTC(year, 4, 1));
    let armistice1945 = new Date(Date.UTC(year, 4, 8));
    let nationalDay = new Date(Date.UTC(year, 6, 14));
    let assumption = new Date(Date.UTC(year, 7, 15));
    let allSaintsDay = new Date(Date.UTC(year, 10, 1));
    let armistice1918 = new Date(Date.UTC(year, 10, 11));
    let christmasDay = new Date(Date.UTC(year, 11, 25));

    let G = year % 19;
    let C = Math.floor(year / 100);
    let H = (C - Math.floor(C / 4) - Math.floor((8 * C + 13) / 25) + 19 * G + 15) % 30;
    let I = H - Math.floor(H / 28) * (1 - Math.floor(H / 28) * Math.floor(29 / (H + 1)) * Math.floor((21 - G) / 11));
    let J = (year + Math.floor(year / 4) + I + 2 - C + Math.floor(C / 4)) % 7;
    let L = I - J;
    let easterMonth = 3 + Math.floor((L + 40) / 44);
    let easterDay = L + 28 - 31 * Math.floor(easterMonth / 4);
    let easterDate = new Date(Date.UTC(year, easterMonth - 1, easterDay));
    let easterMonday = new Date(Date.UTC(year, easterMonth - 1, easterDay + 1));
    let ascensionDay = new Date(Date.UTC(year, easterMonth - 1, easterDay + 39));
    let whitMonday = new Date(Date.UTC(year, easterMonth - 1, easterDay + 50));

    return [newYearDay, easterDate, easterMonday, laborDay, armistice1945, ascensionDay, whitMonday, nationalDay, assumption, allSaintsDay, armistice1918, christmasDay];
};

let dates = NotOpenedDates(currentYear)
    .concat(NotOpenedDates(currentYear + 1))
    .concat(NotOpenedDates(currentYear + 2));

$(document).ready(function () {
    $('.js-datepicker').datepicker({
        language: 'fr',
        startDate: 'today',
        endDate: '+18m',
        todayHighlight: true,
        autoclose: true,
        datesDisabled: dates,
        daysOfWeekDisabled: '[0, 2]'
    });
});
