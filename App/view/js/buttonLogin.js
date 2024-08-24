document.addEventListener("DOMContentLoaded", function () {
    let boton = document.getElementById('button');

    boton.addEventListener('click', function (event) {
        event.preventDefault
        console.log('Hola Mundo');
    });
});