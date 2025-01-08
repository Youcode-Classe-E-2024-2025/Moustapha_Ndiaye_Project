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


// Functions to handle modals
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Fill the update form with project data
function fillUpdateForm(project) {
    document.getElementById('updateProjectTitle').value = project.projectTitle;
    document.getElementById('updateProjectDescrip').value = project.projectDescrip;
    document.getElementById('updateCategory').value = project.category;
    document.getElementById('updateStartAt').value = project.startAt;
    document.getElementById('updateEndAt').value = project.endAt;
    document.getElementById('updateIsPublic').value = project.isPublic;
    document.getElementById('updateStatus').value = project.status;
    document.getElementById('updateProjectId').value = project.idProject;
}

// Confirm project deletion
function confirmDelete(projectId) {
    return confirm(`Are you sure you want to delete project ${projectId}?`);
}


 // Smooth scrolling for sidebar links
 document.querySelectorAll('.sidebar a').forEach(link => {
  link.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent default anchor behavior
      const targetId = this.getAttribute('href').substring(1); // Get the target section ID
      const targetSection = document.getElementById(targetId); // Find the target section
      if (targetSection) {
          targetSection.scrollIntoView({ behavior: 'smooth' }); // Smooth scroll to the section
      }
  });
});