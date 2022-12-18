<a name="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/CarlNoe/eManga">
    <img src="/public/img/Logo.png" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">eManga</h3>
</div>

<!-- GETTING STARTED -->

## Getting Started

Suivre les étapes ci-dessous pour lancer le projet en local.

### Prérequis

- Node (npm)
- Composer

### Installation

1. Cloner le repo
    ```sh
    git clone https://github.com/CarlNoe/eManga.git
    ```
2. Installer les dépendances
    ```sh
    composer install
    ```
    et
    ```sh
    npm i
    ```
3. Importer le fichier e-manga.sql dans phpmyadmin

4. Ensuite il faut configurer le fichier database.php qui se situe dans le fichier config
  'DATABASE_CONFIG' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'e_manga',
        'user' => 'Mettre votre nom de Bdd',
        'password' => 'Mettre votre mot de passe',
        'host' => '127.0.0.1',
    ],

5. Lancer la commande suivante pour créer les tables dans la base de données:
    ```sh
    php doctrine.php orm:schema-tool:update --force --dump-sql
    ```

6. Remplir la base de données en exécutant le fichier `insertData.php` situé dans `\eManga\src\App\utils`. Il y a deux utilisateurs déjà créés:
    - Identifiant admin:
        - Email: admin@admin.com
        - Mot de passe: admin1234
    - Identifiant user:
        - Email: JohnDoe@gmail.com
        - Mot de Passe: john1234

### Utilisation

Pour lancer le projet, ouvrez une invite de commande et naviguez jusqu'au dossier `public`. Ensuite, lancez le serveur PHP avec la commande suivante:

```sh
php -S localhost:{port}
```
Remplacez {port} par le numéro de port de votre choix (par exemple, 8000). Vous pourrez alors accéder au projet en ouvrant votre navigateur et en allant sur l'adresse localhost:{port}.
