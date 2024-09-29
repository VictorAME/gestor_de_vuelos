console.log("Esta en el archivo JS de client_user_profile");

function userProfile() {
  const deleteUser = async () => {
    return await fetch("", {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((Response) => {
        if (!response.ok) {
          throw new Error("Error in connection " + response.statusText);
        }

        response.json();
      })
      .catch((error) => {
        console.error(error);
      });
  };

  const modalDelete = () => {};
}

document.addEventListener("DOMContentLoaded", () => {
  userProfile();
});
