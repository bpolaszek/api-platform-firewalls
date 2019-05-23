/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
import AxiosFactory from 'axios';

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

const dataset = document.getElementById('content').dataset;

(async () => {

    const axios = AxiosFactory.create({
        headers: {
            Authorization: `Bearer ${dataset.jwt}`,
        }
    });


    const orders = await axios.get(dataset.entrypoint)
        .then(response => response.data.order)
        .then(uri => axios.get(uri))
        .then(response => response.data)
    ;

    console.log(orders);

})();
