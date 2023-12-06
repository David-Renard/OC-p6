# OC - p6

## Openclassrooms projet 6 (parcours : Développeur d'application - PHP/Symfony)
### Développez de A à Z le site communautaire SnowTricks

## Codacy
### Here is the review of code by Codacy (on dev branch):
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/24c50e0040874790ac8115dddee2b27a)](https://app.codacy.com/gh/David-Renard/OC-p6/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Présentation du projet
### Contexte
Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif
pour faire connaître ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).

Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant 
l'intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les 
marques de snowboard grâce au traffic que le contenu aura généré.

Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy.

### Besoins
Implémenter les fonctionnalités : 
* un annuaire des figures de snowboard;
* la gestion des figures (création, modification, consultation);
* un espace de discussion commun à toutes les figures.

Création des pages :
* la page d'accueil où figure la liste des figures;
* la page de création d'une nouvelle figure;
* la page de modification d'une figure;
* la page de présentation d'une figure.


* la page d'inscription;
* la page de connexion;
* la page de mot de passe oublié;
* la page de modification de mot de passe.


   
``
## Installation
### Prerequisites
> Language : PHP ^8.2.9, Symfony ^6.3.4

> Database : Postgres ^15.0

> You also need a WebServer

> Install Composer

> Install Npm

1. Clone the repository (https://github.com/David-Renard/OC-p6.git).
2. Edit your own .env file into an .env.local file and in doctrine section you can type in :


    DATABASE_URL="postgresql://postgres:root@127.0.0.1:5432/testsnowtricks?serverVersion=15&charset=utf8"
3. Create the **_snowtricks_** database by running :


    symfony console doctrine:database:create
                                    6. Make the migration by running :
                                    
                                    
                                        symfony console make:migration
and then :

    symfony console doctrine:migrations:migrate
4. Load fixtures by running :


    symfony console doctrine:fixtures:load

5. Build everything you need to have by typing in :


    npm run build
6. Your project should now be ready !
7. You can use project after running :


    symfony serve -d

and register or use a registered user :
- with email : lucydupuy@yahoo.fr
- and password : Luc156-Dup

_In this project, there is no administration part._

