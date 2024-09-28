console.log("El archivo singin.js está cargado y ejecutándose.");

const SingIn = () => {
  const endpoint = async (setData) => {
    try {
      const respEnd = await fetch(
        "http://localhost/mvc/app/src/model/clients/user.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(setData),
        }
      );

      if (!respEnd.ok) {
        throw new Error("Error en el EndPoint");
      }

      const response = await respEnd.json();
      return response;
    } catch (error) {
      console.error("Error: ", error);
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellidos").value;
    const telefono = document.getElementById("telefono").value;
    const correo = document.getElementById("correo").value;
    const contrasena = document.getElementById("contrasena").value;

    const setData = {
      nombre: nombre,
      apellidos: apellidos,
      telefono: telefono,
      correo: correo,
      contrasena: contrasena,
    };

    console.log("Formulario enviado con los datos:", setData);

    const response = await endpoint(setData);

    // console.log(response);

    if (response && response.message === "Datos insertados con exito") {
      alert("Datos insertados con exito");
    } else {
      console.error("Error");
    }
  };

  const form = document.getElementById("signupForm");
  form.addEventListener("submit", handleSubmit, () => {
    window.location.href = "../../../../html/user/autenticacion/login.html";
  });
};

document.addEventListener("DOMContentLoaded", () => {
  SingIn();
});
