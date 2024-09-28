console.log("Esta conectado el Login");

const LongIn = () => {
  const EndPoint = async (data) => {
    try {
      const respEnd = await fetch(
        "http://localhost/mvc/app/src/controller/clients/auth/login_role.php",
        {
          method: "POST",
          body: JSON.stringify(data),
        }
      )
        .then((resp) => resp.json())
        .catch((error) => console.error(error));
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

    if (response && response.message === "Eres admin") {
      console.log("Bienvenido Admin");
      location.href = "../../../../html/layout/admin/home/home.html";
    } else if (response && response.message === "Eres usuario") {
      console.log("Bienvenido Usuario");
      location.href = "../../../../html/layout/user/home/home.html";
    } else {
      console.log("Contraseña incorrecta o correo electronico");
    }
    console.log(response);
  };

  const form = document.getElementById("loginForm");
  form.addEventListener("submit", handleSubmit);
};

document.addEventListener("DOMContentLoaded", () => {
  LongIn();
});
