console.log("Estoy conectado al JS");

function Usuarios() {
  const endpoint = async () => {
    try {
      const response = await fetch(
        "../../../../../controller/admin/api/usuarios.php",
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
      const response = await fetch(
        `../../../../../controller/admin/api/usuarios.php?user_id=${id}`,
        {
          method: "DELETE",
        }
      );

      if (!response.ok) {
        throw new Error("Error en la conexión");
      }

      const data = await response.json();
      console.log("Usuario eliminado:", data);
      handleSubmit();
    } catch (error) {
      console.error("Error al eliminar el usuario:", error);
    }
  };

  const handleSubmit = async () => {
    const usuarios = await endpoint();
    const items = usuarios.items || [];

    const tableBody = document.getElementById("items-row");
    tableBody.innerHTML = "";

    items.forEach((item) => {
      const tr = document.createElement("tr");

      const tdNombre = document.createElement("td");
      tdNombre.textContent = item.name;
      tr.appendChild(tdNombre);

      const tdApellido = document.createElement("td");
      tdApellido.textContent = item.lastname;
      tr.appendChild(tdApellido);

      const tdCorreo = document.createElement("td");
      tdCorreo.textContent = item.email;
      tr.appendChild(tdCorreo);

      const tdDelete = document.createElement("button");
      tdDelete.textContent = "Eliminar";
      tdDelete.addEventListener("click", () => deleteUser(item.id));
      tr.appendChild(tdDelete);

      tableBody.appendChild(tr);
    });
  };

  document.addEventListener("DOMContentLoaded", handleSubmit);
}

Usuarios();
