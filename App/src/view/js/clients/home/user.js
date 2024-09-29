console.log("Estas en el JS de Home");

function Menu() {
  const ajax_get = async () => {
    return await fetch(
      "http://localhost/mvc/app/src/controller/clients/auth/login_role.php",
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      }
    )
      .then((res) => res.json())
      .catch((err) => console.error(err));
  };

  const nombreTitulo = async () => {
    const endpoint = await ajax_get(); // Esperamos a que ajax_get se resuelva
    const items = endpoint.items || []; // Usamos la respuesta directa como el arreglo de items
    const titulo = document.getElementById("items-container");
    titulo.innerHTML = "";

    // items.map((item) => {
    const header = document.createElement("header");

    const h1 = document.createElement("h1");
    h1.innerText = `Hola ${endpoint.username} ¿A donde quieres ir?`;
    header.appendChild(h1);

    titulo.appendChild(header);
    // });
  };

  // Llamamos a nombreTitulo para mostrar los nombres
  nombreTitulo();
}

document.addEventListener("DOMContentLoaded", () => {
  Menu();
});
