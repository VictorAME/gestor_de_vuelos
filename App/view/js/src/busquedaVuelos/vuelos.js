const busquedaVuelos = () => {
  const endpoint = async () => {
    try {
      const reply = await fetch("", {
        method: "POST",
      });

      if (!reply.ok) {
        throw new Error("Error en la conexion");
      }

      const response = await reply.json();
      return response;
    } catch (error) {
      console.error(error);
    }
  };

  const handleSearch = async () => {};
};
