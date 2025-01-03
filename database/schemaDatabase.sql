-- Table Users
CREATE TABLE User (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    fullName VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passWord VARCHAR(255) NOT NULL,
    role ENUM('Visitor', 'TeamMember', 'ProjectManager') NOT NULL DEFAULT 'Visitor'
);

-- Table Project
CREATE TABLE Project (
    idProject INT PRIMARY KEY AUTO_INCREMENT,
    projectTitle VARCHAR(100) NOT NULL,
    projectDescrip TEXT,
    category VARCHAR(50),
    startAt DATE NOT NULL,
    endAt DATE,
    isPublic TINYINT(1) NOT NULL DEFAULT 1, 
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
    taskId INT PRIMARY KEY AUTO_INCREMENT,
    taskTitle VARCHAR(100) NOT NULL,
    taskDescrip TEXT,
    startAt DATE NOT NULL,
    endAt DATE,
    projectId INT NOT NULL,
    status ENUM('Todo', 'In Progress', 'Done') NOT NULL DEFAULT 'Todo',
    FOREIGN KEY (projectId) REFERENCES Project(id_project) ON DELETE CASCADE,
    FOREIGN KEY (assignedTo) REFERENCES Users(id_users) ON DELETE SET NULL
);

CREATE TABLE UserTask (
    userId INT,
    taskId INT,
    PRIMARY KEY (userId, taskId),
    FOREIGN KEY (userId) REFERENCES Users(id_users) ON DELETE CASCADE,
    FOREIGN KEY (taskId) REFERENCES Task(idTask) ON DELETE CASCADE
);

-- Table Tag
CREATE TABLE Tag (
    idTag INT PRIMARY KEY AUTO_INCREMENT,
    nameTag VARCHAR(100) NOT NULL UNIQUE
);

-- Table de liaison Task-Tag (pour associer des tags à une tâche)
CREATE TABLE TaskTag (
    taskId INT,
    tagId INT,
    PRIMARY KEY (taskId, tagId),
    FOREIGN KEY (taskId) REFERENCES Task(id_task) ON DELETE CASCADE,
    FOREIGN KEY (tagId) REFERENCES Tag(id_tag) ON DELETE CASCADE
);