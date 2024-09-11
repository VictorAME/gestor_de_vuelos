async function crudMostrar() {
  try {
    const reply = await fetch(
      "../../../../../controller/admin/api/vuelos.php",
      {
        method: "GET",
      }
    );
    if (!reply.ok) {
      throw new Error("Error en la conexion");
    }

    const response = await reply.json();
    return response;
  } catch (error) {
    console.error(error);
  }
}

function crudCrear() {
  const crear = async (data) => {
    try {
      const reply = await fetch(
        "../../../../../controller/admin/api/vuelos.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      if (!reply.ok) {
        throw new Error("Error en la conexion");
      }

      const response = await reply.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };
}

function curdEliminar() {
  const eliminar = async (id) => {
    try {
      const reply = await fetch(
        `../../../../../controller/admin/api/vuelos.php?vuelos_id=${id}`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
        }
      );
      if (!reply.ok) {
        throw new Error("Error en la conexion");
      }

      const response = await reply.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };
}

function crudActualizar() {
  const actualizar = async (id) => {
    try {
      const reply = await fetch(
        `../../../../../controller/admin/api/vuelos.php?vuelos_id=${id}`,
        {
          method: "PUT",
        }
      );
      if (!reply.ok) {
        throw new Error("Error en la conexion");
      }

      const response = await reply.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };
}

// function selecionCRUD() {
//   const selectOpcion = async () => {
//     try {
//       const select = document.getElementById("select").value;

//     } catch (error) {
//       console.error(error);
//     }
//   };
// }
document.addEventListener("DOMContentLoaded", () => {
  crudMostrar();
  crudCrear();
  curdEliminar();
  crudActualizar();
});
