console.log("Estoy conectado al Js de Vuelos");

function VuelosAPI() {
  const create = async (data) => {
    return await fetch(
      "http://localhost/mvc/app/src/model/travel/flights.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la conexion: " + response.statusText);
        }

        return response.json();
      })
      .catch((error) => {
        console.error(error);
      });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    const origen = document.getElementById("origen").value;
    const destino = document.getElementById("destino").value;
    const fechaIda = document.getElementById("fechaIda").value;
    const fechaRegreso = document.getElementById("fechaRegreso").value;
    const horaSalida = document.getElementById("horaSalida").value;
    const horaLlegada = document.getElementById("horaLlegada").value;
    const precio = document.getElementById("precio").value;

    const data = {
      origen: origen,
      destino: destino,
      fechaIda: fechaIda,
      fechaRegreso: fechaRegreso,
      horaSalida: horaSalida,
      horaLlegada: horaLlegada,
      precio: precio,
    };

    console.log("Formulario enviado con los datos:", data);

    const response = await create(data);

    if (response && response.message === "Datos insertados") {
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
