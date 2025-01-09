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


   // Fonctions pour gérer les modales
   function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Remplir le formulaire de mise à jour avec les données de la tâche
function fillUpdateTaskForm(task) {
    document.getElementById('updateTaskId').value = task.taskId;
    document.getElementById('updateTaskTitle').value = task.taskTitle;
    document.getElementById('updateTaskDescrip').value = task.taskDescrip;
    document.getElementById('updateStartAt').value = task.startAt;
    document.getElementById('updateEndAt').value = task.endAt;
    document.getElementById('updateIdProject').value = task.idProject;
    document.getElementById('updateStatus').value = task.status;
    document.getElementById('updateAssignedTo').value = task.assignedTo;
}

// Confirmer la suppression d'une tâche
function confirmDeleteTask(taskId) {
    return confirm(`Are you sure you want to delete task ${taskId}?`);
}