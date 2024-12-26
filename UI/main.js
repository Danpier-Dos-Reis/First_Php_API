const anchor = document.getElementsByClassName("importexcel")[0];
const inpfile = document.getElementById("fileUpload");

anchor.addEventListener("click", async (event) => {
    event.preventDefault(); // Evitar comportamiento predeterminado del enlace

    inpfile.click();
					
	// Va a esperar a que el carguemos un archivo
	await new Promise((resolve)=>{
		inpfile.addEventListener("change", () => {
        resolve();
    }, { once: true }); // El listener se ejecuta solo una vez
	});
	
	// Crear un objeto FormData para enviar el archivo
    const formData = new FormData();
    formData.append("file", inpfile.files[0]);

    try {
        // Enviar la solicitud con fetch
        const response = await fetch("http://192.168.1.139:8080", { // URL ajustada para tu servidor API PHP
            method : "POST",
            body : formData
        });

        // Manejar la respuesta
        if (response.ok) {
            const jsonResponse = await response.json(); // Obtener la respuesta como JSON
            console.log("Respuesta del servidor:", jsonResponse); // Imprimir en consola el JSON
        } else {
            console.error("Error en la respuesta del servidor:", response.statusText);
            alert("Ocurrió un error en el servidor. Revisa la consola para más detalles.");
        }
        
    } catch (error) {
        console.error("Error de red o del servidor:", error);
        alert("Error inesperado. Revisa la consola para más detalles.");
    }
});
