// Solo intenta traer el nombre del usuario

function Menu() {
  const ajax_get = async () => {
    return await fetch(
      "http://localhost/mvc/App/model/api/admin/usuarios.php",
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

    items.forEach((item) => {
      const header = document.createElement("header");

      const h1 = document.createElement("h1");
      h1.innerText = `Bienvenido "${item.nombre}"`;
      header.appendChild(h1);

      titulo.appendChild(header);
    });
  };

  // Llamamos a nombreTitulo para mostrar los nombres
  nombreTitulo();
}

document.addEventListener("DOMContentLoaded", () => {
  Menu();
});
