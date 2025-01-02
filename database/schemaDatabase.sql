-- Table Users
CREATE TABLE Users (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    fullName VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passWord VARCHAR(255) NOT NULL,
    role ENUM('Visitor', 'TeamMember', 'ProjectManager') NOT NULL
);

-- Table Project
CREATE TABLE Project (
    idProject INT PRIMARY KEY AUTO_INCREMENT,
    projectTitle VARCHAR(100) NOT NULL,
    projectDescrip TEXT,
    category VARCHAR(50),
    startAt DATE NOT NULL,
    endAt DATE,
    createdBy INT, -- Référence à l'utilisateur qui a créé le projet
    FOREIGN KEY (createdBy) REFERENCES Users(id_users) ON DELETE SET NULL
);

-- Table de liaison UserProject
CREATE TABLE UserProject (
    userId INT,
    projectId INT,
    PRIMARY KEY (userId, projectId),
    FOREIGN KEY (userId) REFERENCES Users(id_users) ON DELETE CASCADE,
    FOREIGN KEY (projectId) REFERENCES Project(id_project) ON DELETE CASCADE
);

-- Table Task
CREATE TABLE Task (
    id_task INT PRIMARY KEY AUTO_INCREMENT,
    task_title VARCHAR(100) NOT NULL,
    task_descrip TEXT,
    startAt DATE NOT NULL,
    endAt DATE,
    projectId INT NOT NULL,
    assignedTo INT, -- Référence à l'utilisateur assigné à la tâche
    FOREIGN KEY (projectId) REFERENCES Project(id_project) ON DELETE CASCADE,
    FOREIGN KEY (assignedTo) REFERENCES Users(id_users) ON DELETE SET NULL
);

-- Table Tag
CREATE TABLE Tag (
    id_tag INT PRIMARY KEY AUTO_INCREMENT,
    name_tag VARCHAR(100) NOT NULL UNIQUE
);

-- Table de liaison Task-Tag (pour associer des tags à une tâche)
CREATE TABLE TaskTag (
    taskId INT,
    tagId INT,
    PRIMARY KEY (taskId, tagId),
    FOREIGN KEY (taskId) REFERENCES Task(id_task) ON DELETE CASCADE,
    FOREIGN KEY (tagId) REFERENCES Tag(id_tag) ON DELETE CASCADE
);