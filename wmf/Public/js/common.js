(function() {
    function langSwitcher() {
        function handler(event) {
            event.preventDefault();
            X.setCookie('lang', this.getAttribute('data-lang'), 30);
            window.location.reload();
        }

        [].forEach.call(document.querySelectorAll('.switch-lang'), function (el) {
            el.addEventListener('click', handler);
        });
    }

    document.addEventListener("DOMContentLoaded", langSwitcher);
})();