console.log("El archivo singin.js está cargado y ejecutándose.");

const SingIn = () => {
  const endpoint = async (setData) => {
    try {
      return await fetch(
        "http://localhost/mvc/app/src/model/clients/user.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(setData),
        }
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error en la solicitud");
          }
          return response.json();
        })
        .catch((error) => {
          console.error("Error:", error.message);
        });
    } catch (error) {
      console.error("Error: ", error);
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    const name = document.getElementById("name").value;
    const lastname = document.getElementById("lastname").value;
    const phone = document.getElementById("phone").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const setData = {
      name: name,
      lastname: lastname,
      phone: phone,
      email: email,
      password: password,
    };

    console.log("Formulario enviado con los datos:", setData);

    const response = await endpoint(setData);

    if (response && response.message === "Datos insertados con exito") {
      alert("Datos insertados con exito");
      location.href = "../../../html/clients/auth/login_role.html";
    }
  };

  const form = document.getElementById("signupForm");
  form.addEventListener("submit", handleSubmit);
};

document.addEventListener("DOMContentLoaded", () => {
  SingIn();
});
