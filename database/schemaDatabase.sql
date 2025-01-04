CREATE DATABASE IF NOT EXISTS projectManagePOO;

USE projectManagePOO;

-- Table Users
CREATE TABLE IF NOT EXISTS User (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    fullName VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passWord VARCHAR(255) NOT NULL,
    role ENUM('Visitor', 'TeamMember', 'ProjectManager') NOT NULL DEFAULT 'Visitor'
);

-- Table Project
CREATE TABLE IF NOT EXISTS Project (
    idProject INT PRIMARY KEY AUTO_INCREMENT,
    projectTitle VARCHAR(100) NOT NULL,
    projectDescrip TEXT,
    category VARCHAR(50),
    startAt DATE NOT NULL,
    endAt DATE,
    isPublic TINYINT(1) NOT NULL DEFAULT 1
);


-- Table de liaison UserProject
CREATE TABLE IF NOT EXISTS UserProject (
    userId INT,
    projectId INT,
    PRIMARY KEY (userId, projectId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (projectId) REFERENCES Project(idProject) ON DELETE CASCADE
);

-- Table Task
CREATE TABLE IF NOT EXISTS Task (
    taskId INT PRIMARY KEY AUTO_INCREMENT,
    taskTitle VARCHAR(100) NOT NULL,
    taskDescrip TEXT,
    startAt DATE NOT NULL,
    endAt DATE,
    projectId INT NOT NULL,
    status ENUM('Todo', 'In Progress', 'Done') NOT NULL DEFAULT 'Todo',
    FOREIGN KEY (projectId) REFERENCES Project(idProject) ON DELETE CASCADE
);


-- Table UserTask
CREATE TABLE IF NOT EXISTS UserTask (
    userId INT,
    taskId INT,
    PRIMARY KEY (userId, taskId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (taskId) REFERENCES Task(taskId) ON DELETE CASCADE
);

-- Table Tag
CREATE TABLE IF NOT EXISTS Tag (
    idTag INT PRIMARY KEY AUTO_INCREMENT,
    nameTag VARCHAR(100) NOT NULL UNIQUE
);

-- Table de liaison Task-Tag (pour associer des tags à une tâche)
CREATE TABLE IF NOT EXISTS TaskTag (
    taskId INT,
    idTag INT,
    PRIMARY KEY (taskId, idTag),
    FOREIGN KEY (taskId) REFERENCES Task(taskId) ON DELETE CASCADE,
    FOREIGN KEY (idTag) REFERENCES Tag(taskId) ON DELETE CASCADE
);
