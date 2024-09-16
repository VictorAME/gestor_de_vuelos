function VuelosAPI() {
  const showAll = async () => {
    try {
      const set = await fetch("http://localhost/mvc/App/model/api/vuelos.php", {
        method: "GET",
      });
      if (!set.ok) {
        // Cambiar `response.ok` a `set.ok`
        throw new Error("Error en la conexión.");
      }

      const data = await set.json(); // Cambiar `response.json()` a `set.json()`
      return data;
    } catch (error) {
      console.error("Error en la solicitud:", error);
      return { items: [] };
    }
  };

  const create = async (data) => {
    try {
      const set = await fetch("http://localhost/mvc/App/model/api/vuelos.php", {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json", // Asegurarse de que el encabezado de contenido sea JSON
        },
      });
      if (!set.ok) {
        // Cambiar `response.ok` a `set.ok`
        throw new Error("Error en la conexión.");
      }

      const response = await set.json(); // Cambiar `response.json()` a `set.json()`
      return response;
    } catch (error) {
      console.error("Error en la solicitud:", error);
      return { items: [] };
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    const usuarioId = document.getElementById("usuario");
    const destino = document.getElementById("destino");
    const origen = document.getElementById("origen");
    const fechaIda = document.getElementById("fechaIda");
    const fechaRegreso = document.getElementById("fechaRegreso");
    const horaSalida = document.getElementById("horaSalida");
    const horaLlegada = document.getElementById("horaLlegada");
    const precio = document.getElementById("precio");

    const data = {
      usuario: usuarioId.value,
      destino: destino.value,
      origen: origen.value,
      fechaIda: fechaIda.value,
      fechaRegreso: fechaRegreso.value,
      horaSalida: horaSalida.value,
      horaLlegada: horaLlegada.value,
      precio: precio.value,
    };

    const response = await create(data);

    console.log(response);
  };

  // Cambiar `handleSumit` a `handleSubmit`
  document.addEventListener("DOMContentLoaded", handleSubmit);
}

VuelosAPI();
