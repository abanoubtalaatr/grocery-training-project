/**
 * axios.js
 * Configures the global Axios instance used for AJAX requests.
 */
import axios from "axios";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
