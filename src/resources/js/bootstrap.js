// import 'bootstrap'
import "vue-resource";
/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */
import axios from "axios";
window._ = require("lodash");

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require("vue");
axios.defaults.headers.common["X-CSRF-TOKEN"] = window.Filevuer.csrfToken;
