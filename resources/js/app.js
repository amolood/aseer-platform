import $ from 'jquery';
import favico from 'favico.js';
import toastr from 'toastr';
import { Fancybox } from "@fancyapps/ui";

import * as FilePond from 'filepond';
import 'filepond/dist/filepond.css';
import ar_AR from 'filepond/locale/ar-ar.js';

import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

import Sortable from 'sortablejs/modular/sortable.complete.esm.js';
import '/public/js/bootstrap.bundle.min.js';
import '/public/js/main.js';
// import '/public/js/validatorjs.min.js';
// import '/public/assets/js/theme.js';
// import '/public/assets/js/plugins.js';

// Register FilePond Plugins
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
);

// Set FilePond locale options
FilePond.setOptions({
    ...ar_AR
});

// Expose to global scope
window.$ = window.jQuery = $;
window.Favico = favico;
window.Fancybox = Fancybox;
window.toastr = toastr;
window.FilePond = FilePond;
window.Sortable = Sortable;
