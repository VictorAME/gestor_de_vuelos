function ajax_get() {
  const ajax_get_flight = async () => {
    return await fetch("", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la conexion " + response.statusText);
        }
        return response.json();
      })
      .catch((error) => {
        console.error(error);
      });
  };
  ajax_get_flight();
}

function search_ajax_flight() {
  const handleSubmit = () => {
    const boton = document.getElementById("btn");
    boton.addEventListener("click", (e) => {
      e.preventDefault();
      ajax_get();
    });
  };
  handleSubmit();
}

document.addEventListener("DOMContentLoaded", () => {
  search_ajax_flight();
});
