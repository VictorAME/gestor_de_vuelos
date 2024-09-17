console.log("Estas conectado al archivo Js Home");

function HomeAdmin() {
  const showAllVuelos = async () => {
    try {
      const set = await fetch("http://localhost/mvc/App/model/api/vuelos.php", {
        method: "GET",
      });
      if (!set.ok) {
        throw new Error("Error en la conexión.");
      }

      const data = await set.json();
      return data;
    } catch (error) {
      console.error("Error en la solicitud:", error);
      return { items: [] };
    }
  };

  const deleteVuelos = async (id) => {
    try {
      const deleteVuelo = await fetch(
        `http://localhost/mvc/App/model/api/vuelos.php?vuelo_id=${id}`,
        {
          method: "DELETE",
        }
      );

      if (!deleteVuelo.ok) {
        throw new Error("Error en la conexion de la peticion");
      }

      const response = await deleteVuelo.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };

  const updateVuelos = async (id) => {
    try {
      const deleteVuelo = await fetch(
        `http://localhost/mvc/App/model/api/vuelos.php?vuelo_id=${id}`,
        {
          method: "PUT",
        }
      );

      if (!deleteVuelo.ok) {
        throw new Error("Error en la conexion de la peticion");
      }

      const response = await deleteVuelo.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };

  const handleVuelos = async (event) => {
    const vuelos = await showAllVuelos();
    const items = vuelos.items || [];
    const tableHead = document.getElementById("head-items");
    const tableBody = document.getElementById("items-row");

    tableHead.innerHTML = "";
    tableBody.innerHTML = "";

    //###################################################################
    //#                         BUSCADOR DE VUELOS                      #
    //###################################################################
    // const trSearch = document.createElement("tr");
    // const inpSearch = document.createElement("input");
    // inpSearch.type = "search";
    // inpSearch.value = "Buscar Vuelos";
    // trSearch.appendChild(inpSearch);
    // tableHead.appendChild(trSearch);

    //###################################################################
    //#                         CABEZERA                                #
    //###################################################################
    const trHead = document.createElement("tr");

    const thUsuario = document.createElement("th");
    thUsuario.textContent = "Usuario";
    trHead.appendChild(thUsuario);

    const thdestino = document.createElement("th");
    thdestino.textContent = "Destino";
    trHead.appendChild(thdestino);

    const thOrigen = document.createElement("th");
    thOrigen.textContent = "Origen";
    trHead.appendChild(thOrigen);

    const thFechaIda = document.createElement("th");
    thFechaIda.textContent = "Fecha de Ida";
    trHead.appendChild(thFechaIda);

    const thFechaRegreso = document.createElement("th");
    thFechaRegreso.textContent = "Fecha de Regreso";
    trHead.appendChild(thFechaRegreso);

    const thHoraSalida = document.createElement("th");
    thHoraSalida.textContent = "Hora de Salida";
    trHead.appendChild(thHoraSalida);

    const thHoraLlegada = document.createElement("th");
    thHoraLlegada.textContent = "Hora de Llegada";
    trHead.appendChild(thHoraLlegada);

    const thPrecio = document.createElement("th");
    thPrecio.textContent = "Precio";
    trHead.appendChild(thPrecio);

    const thOpciones = document.createElement("th");
    thOpciones.textContent = "Opciones";
    trHead.appendChild(thOpciones);

    tableHead.appendChild(trHead);

    //###################################################################
    //#                       CUERPO DE LA TABLA                        #
    //###################################################################
    items.forEach((item) => {
      const tr = document.createElement("tr");

      const tdUsuarioId = document.createElement("td");
      tdUsuarioId.textContent = item.usuario;
      tr.appendChild(tdUsuarioId);

      const tdDestino = document.createElement("td");
      tdDestino.textContent = item.destino;
      tr.appendChild(tdDestino);

      const tdOrigen = document.createElement("td");
      tdOrigen.textContent = item.origen;
      tr.appendChild(tdOrigen);

      const tdFechaIda = document.createElement("td");
      tdFechaIda.textContent = item.fechaIda;
      tr.appendChild(tdFechaIda);

      const tdFechaRegreso = document.createElement("td");
      tdFechaRegreso.textContent = item.fechaRegreso;
      tr.appendChild(tdFechaRegreso);

      const tdHoraSalida = document.createElement("td");
      tdHoraSalida.textContent = item.horaSalida;
      tr.appendChild(tdHoraSalida);

      const tdHoraLlegada = document.createElement("td");
      tdHoraLlegada.textContent = item.horaLlegada;
      tr.appendChild(tdHoraLlegada);

      const tdPrecio = document.createElement("td");
      tdPrecio.textContent = "$" + item.precio;
      tr.appendChild(tdPrecio);

      // Boton de ELIMINAR:
      const imgDelete = document.createElement("img");
      imgDelete.src = "../../../../img/admin/usuario_iconos/basura.png";
      imgDelete.alt = "Eliminar";

      const tdDelete = document.createElement("button");
      tdDelete.style.border = "none";
      tdDelete.style.background = "none";
      tdDelete.style.cursor = "pointer";
      tdDelete.addEventListener("click", (event) => {
        event.preventDefault();
        handleDeleteClick(item.id);
      });
      tdDelete.appendChild(imgDelete);
      tr.appendChild(tdDelete);

      // Boton de ACTUALIZAR:
      const imgUpdate = document.createElement("img");
      imgUpdate.src = "../../../../img/admin/usuario_iconos/lapiz.png";
      imgUpdate.alt = "Actualizar";

      const tdUpdate = document.createElement("button");
      tdUpdate.style.border = "none";
      tdUpdate.style.background = "none";
      tdUpdate.style.cursor = "pointer";
      tdUpdate.addEventListener("click", (event) => {
        event.preventDefault();
        handleUpdateClick(item.id);
      });
      tdUpdate.appendChild(imgUpdate);
      tr.appendChild(tdUpdate);

      tableBody.appendChild(tr);
    });
  };

  //Aqui ira para mostrar todos los hoteles creados.
  const showAllHotel = async () => {
    try {
    } catch (error) {
      console.error(error);
    }
  };

  const handleHoteles = async () => {
    const hoteles = await showAllHotel();
    const items = hoteles.items || [];
    const tableHead = document.getElementById("head-items");
    tableHead.innerHTML = "";

    const trHead = document.createElement("tr");

    const thUsuario = document.createElement("th");
    thUsuario.textContent = "Usuario";
    trHead.appendChild(thUsuario);

    const thdestino = document.createElement("th");
    thdestino.textContent = "Destino";
    trHead.appendChild(thdestino);

    const thOrigen = document.createElement("th");
    thOrigen.textContent = "Origen";
    trHead.appendChild(thOrigen);

    const thFechaIda = document.createElement("th");
    thFechaIda.textContent = "Fecha de Ida";
    trHead.appendChild(thFechaIda);

    const thFechaRegreso = document.createElement("th");
    thFechaRegreso.textContent = "Fecha de Regreso";
    trHead.appendChild(thFechaRegreso);

    const thHoraSalida = document.createElement("th");
    thHoraSalida.textContent = "Hora de Salida";
    trHead.appendChild(thHoraSalida);

    const thHoraLlegada = document.createElement("th");
    thHoraLlegada.textContent = "Hora de Llegada";
    trHead.appendChild(thHoraLlegada);

    const thPrecio = document.createElement("th");
    thPrecio.textContent = "Precio";
    trHead.appendChild(thPrecio);

    tableHead.appendChild(trHead);
  };

  const handleDeleteClick = (id) => {
    // Mostrar el modal
    const deleteModal = new bootstrap.Modal(
      document.getElementById("deleteModal")
    );
    deleteModal.show();

    // Configurar el botón de confirmación de eliminación
    document.getElementById("confirmDelete").onclick = async () => {
      deleteVuelos(id);
      deleteModal.hide();
    };
  };

  const handleUpdateClick = (id) => {
    // Mostrar el modal
    const updateModal = new bootstrap.Modal(
      document.getElementById("updateModal")
    );
    updateModal.show();

    // Configurar el botón de confirmación de eliminación
    document.getElementById("confirmUpdate").onclick = async () => {
      // await updateUser(id);
      updateModal.hide();
    };
  };

  const handleSumit = async (event) => {
    event.preventDefault();

    const seleccionar = document.getElementById("seleccionar");

    seleccionar.addEventListener("change", () => {
      var optionSelect = seleccionar.options[seleccionar.selectedIndex];

      if (optionSelect.text === "Vuelos") {
        console.log("Opcion: ", optionSelect.text);
        handleVuelos();
      } else if (optionSelect.text === "Hoteles") {
        console.log("Opcion: ", optionSelect.text);
        handleHoteles();
      }
    });
  };

  document.addEventListener("DOMContentLoaded", handleSumit);
}

HomeAdmin();
