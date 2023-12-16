<script>
    "use strict";
    // Class definition
    var KTModalNewTarget = function() {
        var submitButton;
        var cancelButton;
        var editButton;
        var addButton;
        var validator;
        var form;
        var modal;
        var modalEl;
        var quill;

        // Init form inputs
        var initForm = function() {
            var Delta = Quill.import('delta');
            if ($('#kt_docs_quill_basic').length > 0) {
                quill = new Quill('#kt_docs_quill_basic', {
                    modules: {
                        toolbar: [
                            [{
                                header: [1, 2, 3, 4, 5, 6, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['image', 'code-block'],
                            ['link'],
                            [{
                                'script': 'sub'
                            }, {
                                'script': 'super'
                            }],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['clean']
                        ]
                    },
                    placeholder: 'Type your text here...',
                    theme: 'snow' // or 'bubble'
                });

                var change = new Delta();
                quill.on('text-change', function(delta) {
                    let value = quill.root.innerHTML;
                    if (value == '<p><br></p>' || value == '<p></p>' || value == '') {
                        value = "";
                    }
                    $('[name=content]').val(value);
                    validator.revalidateField('content');
                });
            }

            // Tags. For more info, please visit the official plugin site: https://yaireo.github.io/tagify/
            var tags = new Tagify(form.querySelector('[name="tags"]'), {
                whitelist: {!! getTags() !!},
                // whitelist: ["Important", "Urgent", "High", "Medium", "Low"],
                maxTags: 5,
                dropdown: {
                    maxItems: 10, // <- mixumum allowed rendered suggestions
                    enabled: 0, // <- show suggestions on focus
                    closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                }
            });
            tags.on("change", function() {
                // Revalidate the field when an option is chosen
                validator.revalidateField('tags');
            });

            // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
            var dueDate = $(form.querySelector('[type="date"]'));
            dueDate.flatpickr({
                enableTime: true,
                dateFormat: "d, M Y, H:i",
            });

            // Team assign. For more info, plase visit the official plugin site: https://select2.org/
            $(form.querySelector('select')).on('change', function(e) {
                const value = e.target.value
                const name = $(this).attr('name')
                $(this).val(value)
                // Revalidate the field when an option is chosen
                validator.revalidateField(name);
            });
        }

        // Handle form validation and submittion
        var handleForm = function() {
            // Stepper custom navigation
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            validator = FormValidation.formValidation(
                form, {!! $formHelper['validator'] !!}
            );

            $('#deleteForm').bind('submit', function(e) {
                e.preventDefault()
                console.log(e)
            })
            $('.editform').bind('click', function(e) {
                e.preventDefault()

                $('[name=_method]').val('PATCH')
                const url = $(this).data('url')
                console.log(url)
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        submit(url)
                        $.each(response, function(key, val) {
                            if (key == "role") {
                                $('#role').val(val)
                            } else if (key == "photo" || key == "image" || key ==
                                "thumbnail") {
                                $(`.photoForm.${key}`).attr("src", val);
                                $(`.imageForm.${key}`).attr("src", val);
                            } else if (key == "parent_id") {
                                $(`option[value=${id}]`).attr('hidden', true)
                            } else if (key == "status") {
                                val ? $('input[type=checkbox]').attr('checked',
                                    true) : $(
                                    'input[type=checkbox]').removeAttr(
                                    'checked');
                                $(`input[name=status]`).val(val)
                            } else if (key == 'start_at' || key == 'end_at') {
                                $(`input[name=${key}]`).val(getDate(val))
                            } else if (key == 'content') {
                                $(`[name=${key}]`).val(val)
                                if ($('#kt_docs_quill_basic').length > 0) {
                                    quill.root.innerHTML = val
                                }
                            } else {
                                $(`input[name=${key}]`).val(val)
                                $(`select[name=${key}]`).val(val)
                                $(`[name=${key}]`).trigger('change')
                                $(`textarea[name=${key}]`).html(val)
                            }
                        });
                    }
                });
            });

            $('.addButton').on('click', function(e) {
                e.preventDefault()

                $('[name=_method]').val('POST')
                var inputElement = document.getElementsByTagName("input");
                for (var ii = 0; ii < inputElement.length; ii++) {
                    const name = inputElement[ii].name
                    const value = inputElement[ii].value
                    const type = inputElement[ii].type
                    if (name == "_token") {
                        inputElement[ii].value = value
                    } else if (name == "_method") {
                        inputElement[ii].value = 'POST'
                    } else {
                        inputElement[ii].value = "";
                    }
                    if (type == "checkbox") {
                        inputElement[ii].value = 1
                    }
                }

                var selectElement = document.getElementsByTagName("select");
                for (var ii = 0; ii < selectElement.length; ii++) {
                    const name = selectElement[ii].name
                    selectElement[ii].value = 0;
                }

                $(".photoForm").fadeIn("slow").attr('src', "");
                $(".imageForm").fadeIn("slow").attr('src', "");
                submit("{{ url()->current() }}")
            })

            function submit(url) {
                // Action buttons
                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    {!! $formHelper['getAjaxForm'] !!}
                    // Validate form before submit
                    if (validator) {
                        validator.validate().then(function(status) {
                            submitButton.setAttribute('data-kt-indicator', 'on');
                            // Disable button to avoid multiple click
                            submitButton.disabled = true;
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-Token': $('[name=_token]').val()
                                }
                            });

                            $.ajax({
                                type: "POST",
                                url: url,
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    console.log(response)
                                    submitButton.removeAttribute(
                                        'data-kt-indicator');
                                    // Enable button
                                    submitButton.disabled = false;
                                    if (response.code == 500) {
                                        Swal.fire({
                                            text: "OOooppss Terjadi kesalahan, Periksa Form!",
                                            icon: response.type,
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    } else if (response.type == 'error') {
                                        Swal.fire({
                                            text: "OOooppss Terjadi kesalahan, Periksa Form!",
                                            icon: response.type,
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    } else if (response.error) {
                                        Swal.fire({
                                            text: "OOooppss Terjadi kesalahan," +
                                                response.error + "!",
                                            icon: 'error',
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            text: "Form has been successfully submitted!",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function(result) {
                                            if (result.isConfirmed) {
                                                modal.hide()
                                                location.reload();
                                            }
                                        });
                                    }
                                },
                                error: function(response) {
                                    console.log(response)
                                    Swal.fire({
                                        text: "Oooopss Terjadi Kesalahan.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });
                                    submitButton.removeAttribute(
                                        'data-kt-indicator');
                                    // Enable button
                                    submitButton.disabled = false;
                                }
                            });
                        });
                    }
                });
            }

            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    text: "Are you sure you would like to cancel?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, cancel it!",
                    cancelButtonText: "No, return",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then(function(result) {
                    if (result.value) {
                        form.reset(); // Reset form
                        modal.hide(); // Hide modal
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Your form has not been cancelled!.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        });
                    }
                });
            });
        }

        return {
            // Public functions
            init: function() {
                // Elements
                modalEl = document.querySelector('#addModal');

                if (!modalEl) {
                    return;
                }

                modal = new bootstrap.Modal(modalEl);

                form = document.querySelector('#addModal_form');
                submitButton = document.getElementById('addModal_submit');
                cancelButton = document.getElementById('addModal_cancel');
                editButton = document.querySelector('#editform');
                addButton = document.querySelector('.addButton');

                initForm();
                handleForm();
            }
        };
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        KTModalNewTarget.init();
    });
</script>
