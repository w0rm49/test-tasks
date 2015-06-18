(function() {

    function inputValidation(form) {
        var handler = function () {
            var config = JSON.parse(this.getAttribute('data-validator')) || [];
            var result = Validators.validate(this.value, config, form );

            var formRow = this.parentElement;
            var errorMessage = formRow.querySelector('.form-row--error-message');

            if (result.valid === false) {
                this.classList.add('form-row--input--error');
                errorMessage.innerHTML = result.errors[0];
            } else {
                this.classList.remove('form-row--input--error');
                errorMessage.innerHTML = '';
            }
        };

        var inputs = form.querySelectorAll('.need-validation');
        [].forEach.call(inputs, function (el) {
            el.addEventListener('keyup', handler);
            el.addEventListener('change', handler);
            el.addEventListener('blur', handler);
        });
    }

    function password2Validation(form) {
        var passInput2 = form.querySelector('[name="password2"]');
        var passInput = form.querySelector('[name="password"]')
        var handler = function () {
            X.triggerEvent(passInput, 'change');
        };

        passInput2.addEventListener('keyup', handler);
        passInput2.addEventListener('change', handler);
        passInput2.addEventListener('blur', handler);
    }

    function datePicker(rootElement) {
        var yearSelect = rootElement.querySelector('.date-year'),
            monthSelect = rootElement.querySelector('.date-month'),
            daySelect = rootElement.querySelector('.date-day'),
            dateInput = rootElement.querySelector('.date-value');

        var lastDays = [
            {date: 29, el: daySelect.querySelector('[value="29"]')},
            {date: 30, el: daySelect.querySelector('[value="30"]')},
            {date: 31, el: daySelect.querySelector('[value="31"]')}
        ];

        function fixLastDays(date) {
            var daysInMonth = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
            if (date.getDate() > daysInMonth) {
                date.setDate(daysInMonth);
            }
            lastDays.forEach(function (val) {
                if (val.date > daysInMonth) {
                    val.el.setAttribute('disabled', 'disabled');
                } else {
                    val.el.removeAttribute('disabled');
                }
            });
        }

        function changeSelects() {
            var date = new Date(yearSelect.value, monthSelect.value, daySelect.value);
            fixLastDays(date);
            dateInput.value = date.getFullYear() + '-' +
            ('0' + date.getMonth()).substr(-2, 2) + '-' +
            ('0' + date.getDate()).substr(-2, 2);
            daySelect.value = date.getDate();
        }

        yearSelect.addEventListener('change', changeSelects);
        monthSelect.addEventListener('change', changeSelects);
        daySelect.addEventListener('change', changeSelects);

        var initialDate = dateInput.value.match(/(\d{4})-(\d{2})-(\d{2})/);
        if (initialDate) {
            yearSelect.value = initialDate[1];
            monthSelect.value = parseInt(initialDate[2]);
            daySelect.value = parseInt(initialDate[3]);
        } else {
            changeSelects();
        }
    }

    function photoPreview(rootElement) {
        var find = function(selector) { return rootElement.querySelector(selector); };
        var fileInput = find('.photo-upload');
        var clearFile = find('.photo-clear');
        var thumb = find('.file-block--image');
        var filenameSpan = find('.photo-filename');
        var errorMessage = find('.form-row--error-message');

        fileInput.addEventListener('change', function (event) {
            var file = event.target.files[0];
            var config = JSON.parse(this.getAttribute('data-validator')) || [];
            var result = Validators.validate(file, config);

            errorMessage.innerHTML = (result.valid === false) ? result.errors[0] : '';

            var fileReader = new FileReader();
            fileReader.onload = (function (file, validImage) {
                return function (e) {
                    filenameSpan.innerHTML = file.name + ' (' + X.readableSize(file.size) + ')';
                    if (validImage) {
                        thumb.classList.remove('file-block--image-hidden');
                        thumb.src = e.target.result;
                    } else {
                        thumb.classList.add('file-block--image-hidden');
                        thumb.src = '';
                    }
                };
            })(file, result.valid);

            if (file) {
                fileReader.readAsDataURL(file);
            }
        });

        clearFile.addEventListener('click', function (event) {
            event.preventDefault();
            fileInput.innerHTML = fileInput.innerHTML; //hack: reset input form
            thumb.classList.add('file-block--image-hidden');
            filenameSpan.innerHTML = '';
            errorMessage.innerHTML = ''
        });
    }

    function passwordStrength(passInput, label) {

        var tests = [
            { weight: 2, regexp: /.*[0-9].*[0-9]/ },
            { weight: 3, regexp: /.*[A-ZА-Я].*[A-ZА-Я]/ },
            { weight: 2, regexp: /.*[a-zа-я].*[a-zа-я]/ },
            { weight: 3, regexp: /.*[!@#$&*:\^]/},
            { weight: 2, regexp: /.{8,10}/},
            { weight: 3, regexp: /.{11,14}/},
            { weight: 5, regexp: /.{15,}/}
        ];

        var messages = JSON.parse(label.getAttribute('data-messages'));

        function getStrength(pass) {
            var strength = tests.reduce(function(level, test) {
                var passed = !!pass.match(test.regexp);
                return level + ((!!passed) ? test.weight : 0);
            }, 0);
            if (strength >= 15) { return 'strong'; }
            if (strength >= 8) { return 'medium'; }
            return 'weak';
        }

        function handle () {
            var strength = getStrength(passInput.value);
            label.innerHTML = messages[strength];
            label.classList.remove('pass-strength-weak');
            label.classList.remove('pass-strength-strong');
            label.classList.remove('pass-strength-medium');
            label.classList.add('pass-strength-' + strength);
        }

        passInput.addEventListener('keyup', handle);
        passInput.addEventListener('change', handle);
        passInput.addEventListener('blur', handle);
    }

    document.addEventListener("DOMContentLoaded", function () {
        var form = document.querySelector('.register-form');
        inputValidation(form);
        password2Validation(form);
        datePicker(form.querySelector('.date-row'));
        photoPreview(form.querySelector('.file-row'));
        passwordStrength(form.querySelector('[name="password"]'), form.querySelector('.pass-strength'));
    });

})();