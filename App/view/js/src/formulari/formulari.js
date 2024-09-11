console.log("Todo esta jalando machin");
const Formulari = () => {
  const endpoitn = async (data) => {
    try {
      const conn = await fetch(
        "http://localhost/mvc/App/controller/formulariUser/fromulari.php",
        {
          method: "POST",
          body: JSON.stringify(data),
        }
      );

      if (!conn.ok) {
        throw new Error("Error en la conexion");
      }

      const response = await conn.json();
      return response;
    } catch (error) {
      console.error("Error:", error);
    }
  };

  const handleSubmit = async () => {
    nombre = document.getElementById();
    apellidos = document.getElementById();
    correo = document.getElementById();
    contrasena = document.getElementById();
    comentario = document.getElementById();

    const data = {
      name: nombre,
      lastname: apellidos,
      email: correo,
      password: contrsena,
      comment: comentario,
    };

    const response = await endpoitn(data);

    console.log(response);
  };

  const form = document.getElementById("formularioUsuario");
  form.addEventListener("submit", handleSubmit);
};
