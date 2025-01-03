document.addEventListener("DOMContentLoaded", () => {
    const inputs = document.querySelectorAll("input");

    inputs.forEach((input) => {
      const label = input.nextElementSibling;

      const checkInput = () => {
        if (input.value.trim() === "") {
          label.classList.remove("label-top");
          label.classList.add("label-default");
        } else {
          label.classList.add("label-top");
          label.classList.remove("label-default");
        }
      };

      // Initialisation de l'état du label
      checkInput();

      // Ajout des écouteurs d'événements
      input.addEventListener("input", checkInput);
      input.addEventListener("blur", checkInput);
    });
  });

