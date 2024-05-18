/* FRIENDLY URL */
function friendlyURL(url) {
    var link = "";
    url = url.replace("?", "");
    url = url.split("&");
    cont = 0;
    for (var i = 0; i < url.length; i++) {
    	cont++;
        var aux = url[i].split("=");
        if (cont == 2) {
        	link += "/" + aux[1] + "/";	
        }else{
        	link += "/" + aux[1];
        }
    }
    // console.log(link);
    return link;
}

/* LOAD MENU */
function load_menu() {
    // Menu navbar
    $('<div></div>').attr('class', 'container').appendTo('.navbar')
    .html(`
        <a class="navbar-brand text-brand" href="${friendlyURL('?module=home')}">mira<span class="color-b">casa</span></a>

        <div class='filterSearch_container'>
            <div class='filterSearchOp_container'>
                <select id='filter_search_op_select' name='filter_searchOp' class='filter_searchOp'>
                    <option hidden selected>Transacción</option>
                </select>
            </div>

            <div class='filterSearchTouristcat_container'>
                <select id='filter_search_touristcat_select' name='filter_searchTouristcat' class='filter_searchTouristcat'>
                </select>
            </div>

            <div class='filterSearchCity_container'>
                <input type="text" id='filter_search_city_auto' name='filter_searchCity_auto' class='filter_searchCity_auto' placeholder="Ciudad" value="">
                <div id="search_auto"></div>
            </div>

            <div class='filterSearchButton_container'>
                <img src="view/img/icons/search1.png" id="search_button">
            </div>
        </div>

        <div class="navbarLogin_container">
            <span class="navbarLogin_text">Mi cuenta</span>
            <img src="view/img/icons/user1.png" class="navbarLogin_icon">
            
            <div id="navbarLogin_modal">
                <a class="navbarLogin_items login" href="${friendlyURL('?module=login')}"><span class="navbarLogin_items">Regístrate / Inicia sesión</span></a>
                <span class="navbarLogin_items logout">Cierra sesión</span>
            </div>
        </div>`
    )

    // si detecta un usuario logeado a través de la existencia del access token, lo pinta en el menu
    var token = localStorage.getItem('access_token');
    if (token) {
        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'data_user', 'token': token })
            .then(function(data) {
                console.log(data);
                // return;

                var username = data.username.split('/');

                // highlight usuario
                $('.navbarLogin_icon').hide();
                $('.navbarLogin_avatar').remove();
                $('<img>').attr('class', 'navbarLogin_avatar').attr('src', data.avatar).appendTo('.navbarLogin_container');
                $('.navbarLogin_text')
                    .html(username[0]);
                $('.navbarLogin_container')
                    .css({
                        "background-color": "#f0f0f0",
                    });
                // oculta enlace para login-registro y muestra enlace para logout
                $('.navbarLogin_items.login').hide();
                $('.navbarLogin_items.logout').show();

            }).catch(function() {
                console.log("Error al cargar los datos del user");
                $('.navbarLogin_items.logout').hide();
                $('.navbarLogin_items.login').show();
            });
    } else {
        console.log("No hay token disponible");
        $('.navbarLogin_items.logout').hide();
        $('.navbarLogin_items.login').show();
    }
}

/* MODAL LOGIN */
function modal_login() {
    $('#navbarLogin_modal').hide();
    $('.navbarLogin_container').mouseenter(function() {
        $('#navbarLogin_modal').fadeIn(100);
    });
    // verifica si el cursor ha salido de los 2 contenedores y no está moviéndose dentro de ningún elemento relacionado con ellos
    $('.navbarLogin_container, #navbarLogin_modal').mouseleave(function(e) {
        if (!$(e.relatedTarget).closest(".navbarLogin_container, #navbarLogin_modal").length) {
            $('#navbarLogin_modal').fadeOut(100);
          }
    });
}

/* LOGOUT */
function logout() {
    $('.navbarLogin_items.logout').on('click', function() { 
        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'logout' })
            .then(function(data) {
                console.log(data);
                // return;

                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                //SweetAlert2
                Swal.fire({
                    // position: "top-end",
                    icon: "error",
                    title: "Sesión finalizada",
                    // text: "Your work has been saved",
                    showConfirmButton: false,
                    timer: 1250
                  });
                setTimeout(function(){window.location.href = friendlyURL('?module=home');}, 1500); // redirigimos al home
            }).catch(function() {
                console.log('Something has occured');
            });
    });
}

/* LOAD CONTENT */
function load_content() {
    let path = window.location.pathname.split('/');
    // console.log(path);
    // return;
    
    if (path[2] === 'verify') {
        ajaxPromise(friendlyURL("?module=login"), 'POST', 'JSON', { 'op': 'verify_email', 'token_email': path[3] })
        .then(function(data) {
            console.log(data);

            if (data == "verify") {
                //SweetAlert2
                Swal.fire({
                    // position: "top-end",
                    icon: "success",
                    title: "Email verificado",
                    text: "Ya puede utilizar su cuenta",
                    showConfirmButton: false,
                    // confirmButtonColor: "#2eca6a",
                    // timer: 3000
                });
            } else if (data == "fail") {
                //SweetAlert2
                Swal.fire({
                    // position: "top-end",
                    icon: "error",
                    title: "Verificación fallida",
                    text: "El email de verificación ha caducado. Vuelva ha intentarlo",
                    showConfirmButton: false,
                    // confirmButtonColor: "#2eca6a",
                    // timer: 3000
                });
            }

            setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 2500); // redirigimos al home
        })
        .catch(function() {
          console.log('Error: verify email error');
        });
    } else if (path[2] === 'recover') {
        window.location.href = friendlyURL("?module=login&op=recover_view");
        localStorage.setItem("token_email", path[3]);
    } else if (path[2] === 'recover_view') {
        load_recover_password();
    }
}

$(document).ready(function() {
    load_menu();
    load_content();
    modal_login();
    logout();
});