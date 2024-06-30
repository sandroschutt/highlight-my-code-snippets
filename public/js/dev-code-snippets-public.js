(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  document.addEventListener("DOMContentLoaded", () => {
    const containerHeaders = document.querySelectorAll(
      ".code-snippet-container .render-content .snippet-header button"
    );
    const snippets = document.querySelectorAll(".code-snippet-container pre");
    const snippetsCode = [];

    snippets.forEach((snippet) => {
      snippetsCode.push(snippet.textContent);
      let lines = snippet.textContent.split("\n");

      if (lines[0].trim() === "") {
        lines.shift();
      }

      if (lines.length > 0) {
        lines[0] = lines[0].trimStart();
      }

      snippet.textContent = lines.join("\n");
      hljs.highlightElement(snippet);
    });

    containerHeaders.forEach((header, index) => {
      header.addEventListener("click", () => {
        navigator.clipboard.writeText(snippetsCode[index]).then(
          function () {
            alert("Código copiado para sua área de transferência.");
          },
          function (err) {
            alert("Não foi possível copiar o código: ", err);
          }
        );
      });
    });
  });
})(jQuery);
