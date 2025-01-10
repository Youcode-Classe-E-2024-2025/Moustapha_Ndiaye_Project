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

      input.addEventListener("input", checkInput);
      input.addEventListener("blur", checkInput);
    });
  });


  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block'; 
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none'; 
    }
}

function fillUpdateForm(project) {
  document.getElementById('updateidProject').value = project.idProject;
  document.getElementById('updateProjectTitle').value = project.projectTitle;
  document.getElementById('updateProjectDescrip').value = project.projectDescrip;
  document.getElementById('updateCategory').value = project.category;
  document.getElementById('updateStartAt').value = project.startAt;
  document.getElementById('updateEndAt').value = project.endAt;
  document.getElementById('updateIsPublic').value = project.isPublic ? '1' : '0';
  document.getElementById('updateStatus').value = project.status;
}
// Confirm project deletion
function confirmDelete(projectId) {
    return confirm(`Are you sure you want to delete project ${projectId}?`);
}


 // Smooth scrolling for sidebar links
 document.querySelectorAll('.sidebar a').forEach(link => {
  link.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent default anchor behavior
      const targetId = this.getAttribute('href').substring(1); 
      const targetSection = document.getElementById(targetId); 
      if (targetSection) {
          targetSection.scrollIntoView({ behavior: 'smooth' }); 
      }
  });
});


   function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

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

function confirmDeleteTask(taskId) {
    return confirm(`Are you sure you want to delete task ${taskId}?`);
}

document.querySelectorAll('.edit-button').forEach(button => {
  button.addEventListener('click', () => {
      const project = JSON.parse(button.getAttribute('data-project'));
      fillUpdateForm(project);
      openModal('updateProjectModal');
  });
});