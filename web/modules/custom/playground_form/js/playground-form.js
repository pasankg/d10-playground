/**
 * @file
 * Playground Form resources behaviors.
 */

(function ($, Drupal) {

  'use strict';

  function fieldwatcher(context) {
    $(once('fieldwatcher', '#edit-field-address-0-value', context)).each(function () {
      $(this).keyup(function () {
        // Change '3' to the number of characters after which you want to
        // trigger the ajax.
        if ($(this).val().length > 10) {
          $(this).trigger('overminlength');
        }
      });
    });
  }

  Drupal.behaviors.fieldwatcherScript = {
    attach: function (context, settings) {
      fieldwatcher(context);
    },
  };
}(jQuery, Drupal));
