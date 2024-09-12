const Home = () => {
  async function endpoint() {
    try {
      const response = await fetch(
        "http://localhost/mvc/App/controller/user/login.php",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const result = await response.json();

      displayItems(result.items);
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }

  function displayItems(items) {
    const itemsContainer = document.getElementById("items-container");
    itemsContainer.innerHTML = "";

    items.forEach((item) => {
      const messageDiv = document.createElement("div");
      messageDiv.className = "Bienvenido-message";
      messageDiv.innerHTML = `<h1>Bienvenido, ${item.name_u}!</h1>`;
      itemsContainer.appendChild(messageDiv);
    });

    async function GestorVuelos() {
      try {
        const response = await fetch("", {
          method: "POST",
        });
      } catch (error) {}
    }
  }

  endpoint();
};

document.addEventListener("DOMContentLoaded", () => {
  Home();
});
