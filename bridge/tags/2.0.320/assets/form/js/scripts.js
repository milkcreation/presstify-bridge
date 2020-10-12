'use strict';

import jQuery from 'jquery';
import 'inputmask/dist/jquery.inputmask.min';
import 'inputmask/lib/extensions/inputmask.date.extensions';

jQuery(document).ready(function ($) {
  // Gestion des masques de saisie
  // @see https://github.com/RobinHerbots/Inputmask
  $(':input[data-inputmask], :input[data-inputmask-mask], :input[data-inputmask-alias], :input[data-inputmask-regex]')
      .each(function () {
        $(this).inputmask();
      });
});