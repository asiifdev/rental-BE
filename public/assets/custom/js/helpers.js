function hapus_field() {
    var inputElement = document.getElementsByTagName("input");
    for (var ii = 0; ii < inputElement.length; ii++) {
        const name = inputElement[ii].name
        const value = inputElement[ii].value
        const type = inputElement[ii].type
        if (name == "_token") {
            inputElement[ii].value = value
        } else if (name == "_method") {
            inputElement[ii].value = value
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
    $('#dynamicForm').html('');
    // OVERRIDE REMOVE FIELD NAME
    // $('input[name=name]').val("")
}

function edit(url, id) {
    $('[name=edit_url]').val(url)
    $('[name=_method]').val('PATCH')
    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            console.log(response)
            $.each(response, function (key, val) {
                if (key == "role") {
                    $('#role').val(val)
                } else if (key == "photo" || key == "image" || key == "thumbnail") {
                    $('.photoForm').attr("src", val)
                    $('.imageForm').attr("src", val)
                } else if (key == "parent_id") {
                    $(`option[value=${id}]`).attr('hidden', true)
                } else if (key == "status") {
                    val ? $('input[type=checkbox]').attr('checked', true) : $(
                        'input[type=checkbox]').removeAttr('checked');
                    $(`input[name=status]`).val(val)
                } else if (key == 'start_at' || key == 'end_at') {
                    $(`input[name=${key}]`).val(getDate(val))
                } else {
                    $(`input[name=${key}]`).val(val)
                    $(`select[name=${key}]`).val(val)
                    $(`textarea[name=${key}]`).html(val)
                }
            });
            $('#addModal').attr('action', url);
        }
    });
}

$("input[type=file]").change(function (e) {
    var tmppath = URL.createObjectURL(event.target.files[0]);
    $(".photoForm").fadeIn("slow").attr('src', URL.createObjectURL(event.target.files[0]));
    $(".imageForm").fadeIn("slow").attr('src', URL.createObjectURL(event.target.files[0]));
});

const getDate = (date) => {
    var now = new Date(date);

    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    // var today = (month) + "/" + (day) + "/" + now.getFullYear();
    return today;
}
