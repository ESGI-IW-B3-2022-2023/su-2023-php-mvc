
# Projet PHP MVC Antoine Arnaud

## Démarrage

### Docker

Lancer Docker Desktop avant de lancer le projet sur votre IDE préféré

### Composer

Pour récupérer les dépendances déclarées dans `composer.json` et générer l'autoloader PSR-4, exécuter la commande suivante :

```bash
composer install
```

### DB Configuration

La configuration de la base de données doit être inscrite dans un fichier `.env.local`, sur le modèle du fichier `.env`.

### DB Cnitialisation

Si vous n'avez pas de base de donnée encore créée, veuillez lancer la commande suivante pour la créer

```bash
php bin/doctrine orm:schema-tool:create
```

Si vous avez déjà la base de donnée créée, veuillez la mettre à jour avec la commande suivante

```bash
php bin/doctrine orm:schema-tool:update --force
```

### Démarrer l'application

Commande :

```bash
composer start
```

## Fonctionnalités

### Base de donnée

Nous avons pris la décision d'utiliser Doctrine comme ORM au lieu d'utiliser PDO pour la connexion et les requêtes à la base de données.

### Routes

Implémentation des routes dynamiques avec une donnée non fixe dans l'url. Nous n'avons pas essayé avec plusieurs paramètre dans la même route mais théoriquement cela devrait marcher.
Nous avons la possiblité de récupérer la valeur du paramètre directement dans la méthode correpondante dans le controllera afin d'itérer le traitement du code selon cette variable. 

### Users

Déclaration d'une classe abstraite User. Elle va être le socle qui va être partagé avec les entités Clients et Employés car tous deux vont avoir quelques propriétés similaires. Cette classe abstraite est aussi une entité abstraite. Nous avons donc ajouté l'attribut MappedSuperclass pour indiquer à Doctrine que les entités enfantes vont reprendre ses propriétés.

### Clients

* Définition des noms d'utilisateur : Le code commence par définir un tableau de noms d'utilisateurs ($userNames). Chaque élément de ce tableau est une chaîne de caractères représentant le nom d'un utilisateur que nous voulons créer.

* Validation des noms d'utilisateur : Le code parcourt ensuite le tableau de noms d'utilisateurs avec une boucle foreach. Pour chaque nom d'utilisateur, il effectue deux contrôles de validation : Le premier contrôle vérifie que le nom n'est pas vide. Si le nom est vide, un message d'erreur est affiché et le code passe au nom suivant avec l'instruction continue;. Le deuxième contrôle vérifie que le nom n'est pas trop long (plus de 50 caractères dans cet exemple). Si le nom est trop long, un message d'erreur est affiché et le code passe au nom suivant avec l'instruction continue;.

* Création des utilisateurs : Si un nom d'utilisateur passe les deux contrôles de validation, alors un nouvel objet User est créé, le nom d'utilisateur est défini avec la méthode setName(), et l'utilisateur est persisté (préparé pour l'enregistrement) avec la méthode $this->em->persist().

* Enregistrement des utilisateurs : Après avoir parcouru tous les noms d'utilisateurs, le code appelle la méthode $this->em->flush() pour enregistrer tous les utilisateurs persistés en une seule opération. Cela est plus efficace que d'appeler flush() à chaque itération de la boucle, car cela réduit le nombre de requêtes à la base de données. 

* Gestion des erreurs : Tout le processus de création d'utilisateurs est encapsulé dans un bloc try-catch pour gérer les exceptions qui pourraient être lancées. Si une exception est lancée (par exemple, si la base de données est temporairement indisponible), le code attrape cette exception et affiche un message d'erreur.

* C'est une approche assez basique de la création et de l'enregistrement des utilisateurs. Dans une application réelle, vous voudriez probablement ajouter plus de contrôles de validation, gérer les erreurs de manière plus sophistiquée, et potentiellement déplacer la logique de création des utilisateurs dans un service pour éviter la duplication du code (Créer une Factory).

* Création d'un repository pour ajouter une méthode qui va chercher par ordre croissant de Prénom tous les clients ayant tel ou tel Nom.

* Ajout d'une pagination lors du listing des clients grâce au package PagerFanta. Ajout de bouton de page précédente et suivante si il en existe une.


## Notre feedback

### Antoine 
> Lorsque j'ai commencé à travailler sur une architecture MVC (Model-View-Controller) pour la première fois, j'ai trouvé cela assez déroutant. Je n'avais jamais travaillé avec un tel modèle auparavant, et la séparation des préoccupations entre les différents composants semblait complexe. J'avais l'impression de jongler constamment entre les modèles, les vues et les contrôleurs, sans comprendre pleinement comment ils interagissaient.

> Cependant, au fur et à mesure, je me familiarisais avec le modèle MVC, les avantages de cette architecture sont devenus de plus en plus évidents. 
J'ai mieux compris que le modèle contient la logique liée aux données, le contrôleur traite la logique de l'application et la vue se concentre sur la présentation des données.

> J'ai également rencontré un souci de concernant les données des utilisateurs qui étaient toujours le même nom. Pour cela, j'ai créer un tableau de noms que j'ai parcouru avec une boucle foreach. Cela m'a permis d'éviter d'écrire toutes les données à la main et de les appeler de manière dynamique pour anticiper l'évolution potentielle de l'application.

### Arnaud

> Ayant déjà développé sur Symfony, j'avais la vision d'améliorer le projet dans le sens de Symfony.

> Implémenter les routes avec des varaibles a été un petit challenge, j'ai fais le paris de ne pas regarder comment c'était implémenté par Symfony. J'ai utilisé les regex pour correspondres avec la bonne route. Par la suite j'ai réutilisé le service container pour y injecter en variable le paramètre de la route.

> L'ajout de la foncionnalité de la pagination a été très facile je trouve, il a juste suffit d'intaller PagerFanta et de créer une instance d'un Adapter et de PageFanta.




## Authors

- [@Azroph](https://github.com/Azroph)
- [@Arnaudgouel](https://github.com/Arnaudgouel)



