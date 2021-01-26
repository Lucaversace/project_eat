#TruEat By Lucas Bento-Versace & Victor Tollemer


# Etapes de Mise en place :
(Si vous souhaitez des explications sur les choix techniques, référez-vous au document envoyé avec le projet)

1.  git clone 'https://github.com/Lucaversace/project_eat.git'

Pré-requis :
- Assurrez-vous d'avoir PHP installé sur votre machine
- Assurrez-vous d'avoir un serveur MySQL

Créer un fichier d'environnement de base de données à la racine du projet (.env.local)
Ligne à insérer dans le fichier .env.local : DATABASE_URL=mysql://NomDuUSER:MotDePasse@Adresse:Port/project-eat?serverVersion=5.7

Installer les dépendances
2. composer install

Initier la Base de données
3. php bin/console make:migration
4. php bin/console doctrine:migrations:migrate

Ajouter les fausses données 
5. php bin/console doctrine:fixtures:load

Démarrer le serveur
6. php bin/console server:run


