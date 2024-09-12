console.log("Esta conectado el JavaScript");

const LongIn = () => {
  const EndPoint = async (data) => {
    try {
      const respEnd = await fetch(
        "http://localhost/mvc/App/controller/login_rol.php",
        {
          method: "POST",
          body: JSON.stringify(data),
        }
      );

      if (!respEnd.ok) {
        throw new Error("Error en la conexion.");
      }

      const response = await respEnd.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    //variables:
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const data = {
      email: email,
      password: password,
    };

    const response = await EndPoint(data);

    if (response && response.message === "usuario") {
      console.log("Bienvenido");
      location.href = "../../../../html/layout/user/home/home.html";
    } else {
      alert("Cuenta no encontrada");
    }
    console.log(response);
  };

  const form = document.getElementById("loginForm");
  form.addEventListener("submit", handleSubmit);
};

document.addEventListener("DOMContentLoaded", () => {
  LongIn();
});
