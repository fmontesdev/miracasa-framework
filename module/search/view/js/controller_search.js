function load_operation() {
    ajaxPromise(friendlyURL('?module=search'), 'POST', 'JSON', { 'op': 'search_operation' })
        .then(function(data) {
            // console.log(data);
            // return;
            for (row in data) {
                $('<option></option>').attr('value', `${data[row].name_op}`).html(`${data[row].name_op}`).appendTo('#filter_search_op_select')
                };
            }).catch(function() {
                window.location.href='index.php?page=503';
            });
}

function load_touristCategory() {
    $('#filter_search_touristcat_select').empty();

    var operation = 'null';
    if (localStorage.getItem('filter_op')) { // recupera del localstorage
        var operation = localStorage.getItem('filter_op');
    }
    // console.log(operation);
    ajaxPromise(friendlyURL('?module=search'), 'POST', 'JSON', { 'op': 'search_touristCategory', 'operation': operation })
        .then(function (data) {
            // console.log(data);
            // return;
            if (data != 'error') { // para gestionar la falta de resultados
                $('<option></option>').attr('selected', true).attr('hidden', true).html('Zona turística').appendTo('#filter_search_touristcat_select');
                for (row in data) {
                    $('<option></option>').attr('value', `${data[row].name_touristcat}`).html(`${data[row].name_touristcat}`).appendTo('#filter_search_touristcat_select')
                    };
            } else {
                $('<option></option>').attr('selected', true).attr('hidden', true).html('Zona turística').appendTo('#filter_search_touristcat_select');
                $('<option></option>').attr('disabled', true).html('Sin resultados').appendTo('#filter_search_touristcat_select');
            }  
        }).catch(function () {
            window.location.href='index.php?page=503';
        });
}

function launch_search() {
    load_operation();
    setTimeout(function(){ 
        load_touristCategory();
    }, 10);
    
    $(".filterSearch_container").on("change", "#filter_search_op_select", function(){
        localStorage.removeItem('filter_op');
        localStorage.setItem('filter_op', this.value);
        load_touristCategory();
        $('#filter_search_city_auto').val('');
    });
    $(".filterSearch_container").on("change", "#filter_search_touristcat_select", function(){
        $('#filter_search_city_auto').val('');
    });
}

$(document).ready(function () {
    launch_search();
    // autocomplete();
    // button_search();
});