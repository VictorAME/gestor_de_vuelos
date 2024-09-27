console.log("Estas en el archivo Buscar Vuelos");

function buscador_vuelos() {
  const ajax_get = async () => {
    return await fetch(
      "http://localhost/mvc/App/controller/admin/buscadorVuelos/buscador.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    )
      .then((res) => res.json())
      .then((json) => {
        json.forEach((element) => {
          console.log(element);
        });
      })
      .catch((err) => console.error(err));
  };

  const buscarOrigen = () => {
    let input = document.getElementById("buscarOrigen");
    let lista = document.getElementById("listaOrigen");

    const estados = [
      {
        origen: ["Veracruz", "Mexico", "Monterrey", "Puebla"],
      },
    ];

    input.addEventListener("keyup", () => {
      // Limpia la lista antes de añadir nuevas opciones
      lista.innerHTML = "";

      const searchText = input.value.toLowerCase(); // Convertir el texto ingresado a minúsculas para comparar

      if (searchText) {
        estados.forEach((estado) => {
          // Filtrar las ciudades que comienzan con la letra ingresada
          const resultados = estado.origen.filter((ciudad) =>
            ciudad.toLowerCase().startsWith(searchText)
          );

          resultados.forEach((ciudad) => {
            const option = document.createElement("option");
            option.textContent = ciudad;
            lista.appendChild(option);
          });
        });
      }
    });
  };

  const buscarDestino = () => {
    let input = document.getElementById("buscarDestino");
    let lista = document.getElementById("listaDestino");

    const estados = [
      {
        destino: ["Veracruz", "Mexico", "Monterrey", "Puebla"],
      },
    ];

    input.addEventListener("keyup", () => {
      // Limpia la lista antes de añadir nuevas opciones
      lista.innerHTML = "";

      const searchText = input.value.toLowerCase(); // Convertir el texto ingresado a minúsculas para comparar

      if (searchText) {
        estados.forEach((estado) => {
          // Filtrar las ciudades que comienzan con la letra ingresada
          const resultados = estado.destino.filter((ciudad) =>
            ciudad.toLowerCase().startsWith(searchText)
          );

          resultados.forEach((ciudad) => {
            const option = document.createElement("option");
            option.textContent = ciudad;
            lista.appendChild(option);
          });
        });
      }
    });
  };

  const handleSubmit = () => {
    buscarDestino();
    buscarOrigen();
  };

  // Cambia esto:
  document.addEventListener("DOMContentLoaded", handleSubmit); // Aquí pasamos la referencia a la función
}

buscador_vuelos();
