## API

| url | Verb HTTP | Controller | Method | constraint | title | content | comment |
|---|---|---|---|---|---|---|---|
| /api/vegetable | GET | Api\Vegetable | list | | | Données des fruits et légumes | |
| /api/vegetable/{id} | GET | Api\Vegetable | show | id = \d+ | | données d'un fruit/légume | [id] : vegetable's identifer (integer only) |
| /api/recipe | GET | Api\Recipe | list | | | Données des recettes | |
| /api/recipe/{id} | GET | Api\Recipe | show | id = \d+ | | données d'une recette | [id] : recipe's identifer (integer only) |
| /api/member/{id} | GET | Api\Member | show | id = \d+ | Profil | member's profil | [id] : member's identifer (integer only) |
| /api/member/{id} | PUT | Api\Member | update | id = \d+ | | modifier un member | [id] : member's identifer (integer only) |
| /api/member | POST | Api\Member | create | | | Ajouter un member| |
| /api/member/{id} | DELETE | Api\Member | delete | id = \d+ | | Suppression d'un member | [id] : member's identifer (integer only) |
| /api/login_check | POST | Api\Login | login | | | Connexion | |
| /api/logout | POST | Api\Login | login | | | Deconnexion| |
| /api/favorite | GET | Api\Favorite | list |  |  | Afficher la liste des favoris | |
| /api/favorite/delete/{id} | DELETE | Api\Favorite | delete | id = \d+ |  | Supprimer le favoris | |
| /api/favorite/add/{id} | POST | Api\Favorite | add | id = \d+ |  | Supprimer le favoris | |
| /api/user | POST | Api\User | suscribe  |  |  | s'inscrire à la newsletter |
| /api/user/{id} | DELETE | Api\User | unsuscribe | id = \d+  |  | se désinscrire de la newsletter | |

## BackOffice

| url | Verb HTTP | Controller | Method | constraint | title | content | comment | 
|---|---|---|---|---|---|---|---|
| /back/ | GET | Back\Main | home | | backoffice homepage |
| /back/vegetable/ | GET | back\Vegetable | index | | | Liste des fruits et légumes | |
| /back/vegetable/{id} | GET | back\Vegetable | show | id = \d+ | | données d'un fruit/légume | [id] : vegetable's identifer (integer only) |
| /back/vegetable/update/{id} | GET/POST | back\Vegetable | update | id = \d+ | | modifier un fruit/légume | [id] : vegetable's identifer (integer only) |
| /back/vegetable/create | GET/POST | back\Vegetable | create | | | Ajout d'un fruit/légume | |
| /back/vegetable/delete/{id} | POST | back\Vegetable | delete | id = \d+ | | Suppression d'un fruit/légume | [id] : vegetable's identifer (integer only) |
| /back/recipe/ | GET | back\Recipe | index | | | Liste des recettes | |
| /back/recipe/{id} | GET | back\Recipe | show | id = \d+ | | données d'une recette | [id] : recipe's identifer (integer only) |
| /back/recipe/{id}/update | GET/POST | back\Recipe | update | id = \d+ | | modifier une recette | [id] : recipe's identifer (integer only) |
| /back/recipe/create | GET/POST | back\Recipe | create | | | Ajout d'une recette | |
| /back/recipe/{id} | POST | back\Recipe | delete | id = \d+ | | Suppression d'une recette | [id] : recipe's identifer (integer only) |
| /back/ingredient/ | GET | back\Ingredient | index | | | Liste des ingrédients | |
| /back/ingredient/{id}/update | GET/POST | Back\Ingredient | update | id = \d+ | | modifier une recette | [id] : ingredient's identifer (integer only) |
| /back/ingredient/create | GET/POST | Back\Ingredient | create | | | Ajout d'une recette | |
| /back/ingredient/{id} | POST | Back\Ingredient | delete | id = \d+ | | Suppression d'une recette | [id] : ingredient's identifer (integer only) |
| /back/member| GET | back\Member | index |  | | Liste des membres | [id] : member's identifer (integer only) |
| /back/member/update/{id} | GET/POST | back\Member | update | id = \d+ | | modifier un member | [id] : member's identifer (integer only) |
| /back/member/{id} | POST | back\Member | delete | id = \d+ | | Suppression d'un member | [id] : user's identifer (integer only) |
| /back/user | GET | back\User | index  |  |  | affiche la liste des users newsletter | |
| /back/user/create | GET/POST | back\User | create  |  |  | creation d'un user newsletter | |
| /back/user/{id}/update | GET/POST | back\User | update |  id = \d+ |  | modifier une inscritpion newsletter| [id] : user's identifer (integer only)|
| /back/user/{id} | POST | back\User | delete |  id = \d+ |  | supprimer une inscritpion newsletter| [id] : user's identifer (integer only)|
| /back/content/ | GET | back\Content | index | |  | Afficher la liste des quantités et des ingrédients liée à une recette | |
| /back/content/create | GET/POST | back\Content | create | |  | Ajouter une quantité liée à un ingrédient et à une recette | |
| /back/content/{id}/update | GET/POST | back\Content | update | id = \d+ |  | Modifier une quantité liée à un ingrédient et à une recette | [id] : user's identifer (integer only)|
| /back/content/{id} | POST | back\Content | delete | id = \d+ |  | Supprimer une quantité liée à un ingrédient et à une recette | [id] : user's identifer (integer only)|
| /back/login | | back\Security | login | |  | Authentification au backoffice | |
| /back/logout | | back\Security | logout | |  | Deconnexion du backoffice | |