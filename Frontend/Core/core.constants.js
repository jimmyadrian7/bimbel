import moment from "moment";
import toastr from "toastr";
import flatpickr from "flatpickr";
import Modal from "bootstrap/js/dist/modal";
import autoComplete from "@tarekraafat/autocomplete.js";

(() => {
    "use strict";

    angular.module('app.core')
        .constant('moment', moment)
        .constant('toastr', toastr)
        .constant('flatpickr', flatpickr)
        .constant('Modal', Modal)
        .constant('autoComplete', autoComplete);
})()