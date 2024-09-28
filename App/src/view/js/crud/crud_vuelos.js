console.log("Estoy conectado al Js de Vuelos");

function VuelosAPI() {
  const create = async (data) => {
    try {
      const set = await fetch(
        "http://localhost/mvc/app/src/model/travel/flights.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );
      if (!set.ok) {
        // Cambiar `response.ok` a `set.ok`
        throw new Error("Error en la conexión.");
      }

      const response = await set.json(); // Cambiar `response.json()` a `set.json()`
      return response;
    } catch (error) {
      console.error("Error en la solicitud:", error);
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    const usuario = document.getElementById("usuario").value;
    const destino = document.getElementById("destino").value;
    const origen = document.getElementById("origen").value;
    const fechaIda = document.getElementById("fechaIda").value;
    const fechaRegreso = document.getElementById("fechaRegreso").value;
    const horaSalida = document.getElementById("horaSalida").value;
    const horaLlegada = document.getElementById("horaLlegada").value;
    const precio = document.getElementById("precio").value;

    const data = {
      usuario: usuario,
      destino: destino,
      origen: origen,
      fechaIda: fechaIda,
      fechaRegreso: fechaRegreso,
      horaSalida: horaSalida,
      horaLlegada: horaLlegada,
      precio: precio,
    };

    console.log("Formulario enviado con los datos:", data);

    const response = await create(data);

    if (response && response.message === "Datos insertados con exito") {
      alert("Datos insertados con exito");
    } else {
      console.error("Error");
    }
  };

  const form = document.getElementById("formVuelos");
  form.addEventListener("submit", handleSubmit);
}

document.addEventListener("DOMContentLoaded", () => {
  VuelosAPI();
});
