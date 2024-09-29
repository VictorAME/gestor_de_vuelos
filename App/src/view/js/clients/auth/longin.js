console.log("Esta conectado el Login");

const LongIn = () => {
  const EndPoint = async (data) => {
    try {
      return await fetch(
        "http://localhost/mvc/app/src/controller/clients/auth/login_role.php",
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
            throw new Error("Error en la solicitud");
          }
          return response.json();
        })
        .catch((error) => {
          console.error("Error:", error.message);
        });
    } catch (error) {
      console.error(error);
    }
  };

  const getClient = async () => {
    try {
      return await fetch(
        "http://localhost/mvc/app/src/model/clients/user.php",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
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
      throw new Error("Error: ", error);
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
    const client = await getClient();
    const items = client.items || [];

    items.forEach((item) => {
      if (response && response.message === "Welcome User") {
        alert("Bienvenido " + item.nombre);
        location.href = "../../../html/clients/home/client_user_home.html";
      } else if (response && response.message === "Welcome Admin") {
        alert("Bienvenido " + item.nombre);
        location.href = "../../../html/clients/home/client_user_home.html";
      }
    });
  };

  const form = document.getElementById("loginForm");
  form.addEventListener("submit", handleSubmit);
};

document.addEventListener("DOMContentLoaded", () => {
  LongIn();
});
