# Moustapha_Ndiaye_Project

Gestionnaire de Projets (OOP)

Coach : Iliass RAIHANI.

Auteur : Moustapha Ndisye.
Links
    [GitHub Repository](https://github.com/Youcode-Classe-E-2024-2025/Moustapha_Ndiaye_Project.git)
    [ Scrum Board ](https://trello.com/b/dhgLFMRu/gestionnaire-de-projets-oop)
    [Diagramme de cas d'utilisation](https://lucid.app/lucidchart/b8a22d65-5667-4257-ad89-c5659d6d122b/edit?viewport_loc=-351%2C-127%2C1707%2C968%2C0_0&invitationId=inv_1d219001-1e0a-4271-85d1-a005fe1f5d30)
​

Technologies Requises

    Langage : PHP 8 (Programmation Orientée Objet).
    Base de Données : PDO comme driver pour interagir avec la base de données.

​

Configuration et Exécution du Projet
Prérequis

    Node.js | npm 
    Laragon ou XAPP (windows) | nginix ou apache2 (ubuntu) 
    mysql

Étapes d’installation

    Cloner le projet :
        Ouvrir un terminal et exécuter :
        git clone https://github.com/Youcode-Classe-E-2024-2025/Moustapha_Ndiaye_Project.git

    Path du repo :
        /var/www/html/Moustapha_Ndiaye_Project$

    Configurer la base de données :
        sudo mysql (ubuntu)
        phpmyadmin (windowns)
    Installer les dépendances Node.js :
        npm install

    Demarrer le serveur  :
        php -S localhost:8000
    
    Executez tailwincss  :
        npm run dev

    Exécuter le projet :
        http://localhost:8000



Contexte du projet

    Afin de mieux gérer le travail de l'entreprise le CTO vous demande de fournir une interface intuitive pour les membres des équipes, ainsi qu’un tableau de bord pour les chefs de projet, permettant une gestion efficace des tâches, des membres, et des échéances. L'objectif est de créer un environnement où les membres d’équipes peuvent collaborer, suivre les progrès des projets, et atteindre leurs objectifs dans les délais impartis, tout en utilisant des outils performants et ergonomiques.

​

User Stories

​

En tant que chef de projet :

Gestion des projets :

    Je veux pouvoir créer, modifier, et supprimer des projets pour structurer le travail de l’équipe.

​

Gestion des tâches :

    Je veux assigner des tâches aux membres pour une meilleure répartition des responsabilités.
    Catégoriser mes tâche en gérant des catégories.
    Tager mes tâche en gérant des tags.

​

Suivi de l’avancement :

    Je souhaite consulter l’état des tâches pour m'assurer que le projet avance comme prévu.

​
En tant que membre d’équipe :

Inscription et connexion :

    Je veux pouvoir m’inscrire avec mon nom, mon e-mail et un mot de passe pour accéder à mon compte.
    Je souhaite me connecter de manière sécurisée pour consulter et mettre à jour mes tâches.

​

Participation aux projets :

    Je veux accéder aux projets auxquels je suis assigné pour consulter les tâches et échéances.
    Je souhaite mettre à jour le statut de mes tâches pour informer l’équipe de mon avancement.


En tant qu’utilisateur invité :

**- **Je veux pouvoir visualiser les projets publics pour découvrir les activités des équipes.

    Je souhaite m’inscrire si je décide de rejoindre une équipe ou créer mes propres projets.


Livrables

    - Lien du repository GitHub du projet (Code source + script SQL) sous le nom "prenom_nom-project".
    - La gestion des tâches sur un Scrum Board avec toutes les User Stories.
    - Les diagrammes UML :
    * Diagramme de classes.
    * Diagramme de cas d'utilisation.


Critères de performance

    Planification des tâches : Utilisation d’un outil de gestion comme Jira pour planifier et suivre les tâches.
    Elaboration des User Stories : Rédaction claire et précise pour comprendre les besoins.
    Commits journaliers : Fournir des commits réguliers sur GitHub pour un meilleur suivi des modifications.
    Design responsive : Interface adaptée à tous les types d’écrans grâce à un framework CSS.
    Validation des formulaires :
        - Validation Frontale : HTML5 et JavaScript pour minimiser les erreurs utilisateur.
        - Validation Backend : Mesures de sécurité contre XSS et CSRF.
    Structure du projet : Séparation claire de la logique métier et de l’architecture.
    Sécurité :
        - Prévention des injections SQL avec des requêtes préparées.
        - Protection contre le XSS en échappant les données affichées.
        - Gestion des erreurs avec une page 404 dédiée.