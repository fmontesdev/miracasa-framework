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

function launch_search() {
    load_operation();
    // setTimeout(function(){ 
    //     load_touristCategory();
    // }, 10);
    
    $(".filterSearch_container").on("change", "#filter_search_op_select", function(){
        localStorage.removeItem('filter_op');
        localStorage.setItem('filter_op', this.value);
        // load_touristCategory();
        $('#filter_search_city_auto').val('');
    });
    // $(".filterSearch_container").on("change", "#filter_search_touristcat_select", function(){
    //     $('#filter_search_city_auto').val('');
    // });
}

$(document).ready(function () {
    launch_search();
    // autocomplete();
    // button_search();
});