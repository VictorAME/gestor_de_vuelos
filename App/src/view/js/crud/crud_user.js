console.log("Estoy conectado al JS");

function Usuarios() {
  const endpoint = async () => {
    try {
      const response = await fetch(
        "http://localhost/mvc/app/src/model/clients/user.php",
        {
          method: "GET",
        }
      );

      if (!response.ok) {
        throw new Error("Error en la conexión.");
      }

      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error en la solicitud:", error);
      return { items: [] };
    }
  };

  const deleteUser = async (id) => {
    try {
      await fetch(
        `http://localhost/mvc/App/model/api/usuarios.php?usuario_id=${id}`,
        {
          method: "DELETE",
        }
      );
      // Recargar la tabla después de eliminar
      handleSubmit();
    } catch (error) {
      console.error("Error en la solicitud:", error);
    }
  };

  const handleDeleteClick = (id) => {
    // Mostrar el modal
    const deleteModal = new bootstrap.Modal(
      document.getElementById("deleteModal")
    );
    deleteModal.show();

    // Configurar el botón de confirmación de eliminación
    document.getElementById("confirmDelete").onclick = async () => {
      await deleteUser(id);
      deleteModal.hide();
    };
  };

  const updateUser = async (id) => {
    try {
      await fetch(
        `http://localhost/mvc/App/model/api/usuarios.php?usuario_id=${id}`,
        {
          method: "PUT",
        }
      );
      handleSubmit();
    } catch (error) {
      console.error("Error en la solicitud:", error);
    }
  };

  const handleUpdateClick = (id) => {
    // Mostrar el modal
    const updateModal = new bootstrap.Modal(
      document.getElementById("updateModal")
    );
    updateModal.show();

    // Configurar el botón de confirmación de eliminación
    document.getElementById("confirmUpdate").onclick = async () => {
      await updateUser(id);
      updateModal.hide();
    };
  };

  const handleSubmit = async () => {
    const usuarios = await endpoint();
    const items = usuarios.items || [];

    const tableBody = document.getElementById("items-row");
    tableBody.innerHTML = "";

    items.forEach((item) => {
      const tr = document.createElement("tr");

      const tdId = document.createElement("td");
      tdId.textContent = item.id;
      tr.appendChild(tdId);

      const tdNombre = document.createElement("td");
      tdNombre.textContent = item.nombre;
      tr.appendChild(tdNombre);

      const tdApellido = document.createElement("td");
      tdApellido.textContent = item.apellidos;
      tr.appendChild(tdApellido);

      const tdTelefono = document.createElement("td");
      tdTelefono.textContent = item.telefono;
      tr.appendChild(tdTelefono);

      const tdCorreo = document.createElement("td");
      tdCorreo.textContent = item.correo;
      tr.appendChild(tdCorreo);

      /* 
      ####################################################
        Botón de Eliminar
      ####################################################
      */
      const imgDelete = document.createElement("img");
      imgDelete.src = "../../../../img/admin/usuario_iconos/basura.png";
      imgDelete.alt = "Eliminar"; // Texto alternativo para la accesibilidad

      const tdDelete = document.createElement("button");
      tdDelete.style.border = "none";
      tdDelete.style.background = "none";
      tdDelete.style.cursor = "pointer";
      tdDelete.addEventListener("click", (event) => {
        event.preventDefault();
        handleDeleteClick(item.id);
      });
      // Añadir la imagen al botón de eliminación
      tdDelete.appendChild(imgDelete);
      // Asegúrate de añadir el botón de eliminación a la fila de la tabla
      tr.appendChild(tdDelete);

      /* 
      ####################################################
        Botón de Actualizar
      ####################################################
      */
      const imgUpdate = document.createElement("img");
      imgUpdate.src = "../../../../img/admin/usuario_iconos/lapiz.png";
      imgUpdate.alt = "Actualizar"; // Texto alternativo para la accesibilidad

      const tdUpdate = document.createElement("button");
      tdUpdate.style.border = "none"; // Opcional: elimina el borde predeterminado del botón
      tdUpdate.style.background = "none"; // Opcional: elimina el fondo del botón
      tdUpdate.style.cursor = "pointer"; // Opcional: cambia el cursor al pasar sobre el botón
      tdUpdate.addEventListener("click", (event) => {
        event.preventDefault();
        handleUpdateClick(item.id);
      });
      // Añadir la imagen al botón
      tdUpdate.appendChild(imgUpdate);
      // Añadir el botón a la fila de la tabla
      tr.appendChild(tdUpdate);

      tableBody.appendChild(tr);
    });
  };

  document.addEventListener("DOMContentLoaded", handleSubmit);
}

Usuarios();
