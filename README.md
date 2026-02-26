# EasyColoc - Application de Gestion de Colocation

Une application Laravel pour gérer les dépenses partagées entre colocataires.

## Fonctionnalités

- **Authentification Utilisateur** : Inscription, connexion et gestion du profil
- **Gestion de Colocation** : Créer et gérer des espaces de vie partagés
- **Suivi des Dépenses** : Ajouter, modifier et catégoriser les dépenses partagées
- **Calcul des Soldes** : Calcul automatique de qui doit quoi à qui
- **Système d'Invitation** : Inviter des colocataires par email
- **Tableau de Bord Admin** : Supervision administrative pour les super admins

## Installation

1. Cloner le dépôt
2. Installer les dépendances : `composer install`
3. Copier le fichier d'environnement : `cp .env.example .env`
4. Générer la clé d'application : `php artisan key:generate`
5. Exécuter les migrations : `php artisan migrate`
6. Démarrer le serveur de développement : `php artisan serve`

## Utilisation

1. Inscrire un nouveau compte
2. Créer une colocation ou accepter une invitation
3. Ajouter des dépenses pour les coûts partagés
4. Voir les calculs de soldes pour voir qui doit quoi
5. Inviter des colocataires à rejoindre votre colocation

## Structure du Projet

- `app/Models/` - Modèles Eloquent
- `app/Http/Controllers/` - Contrôleurs d'application
- `app/Services/` - Services logique métier
- `resources/views/` - Templates Blade
- `database/migrations/` - Migrations de base de données

## Technologies Utilisées

- Laravel 12.x
- PHP 8.5+
- PostgreSQL
- Tailwind CSS
- Material Icons

## Notes

- Le premier utilisateur inscrit devient automatiquement super admin
- Les utilisateurs peuvent appartenir à une seule colocation active à la fois
- Toutes les dépenses sont partagées également entre les membres actifs
- Les invitations par email expirent après 7 jours
