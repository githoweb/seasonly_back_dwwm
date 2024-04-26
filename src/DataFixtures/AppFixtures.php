<?php

namespace App\DataFixtures;

use App\Entity\Meal;
use App\Entity\Genre;
use App\Entity\Month;
use App\Entity\Recipe;
use App\Entity\Member;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Content;
use App\Entity\Measure;
use App\Entity\Botanical;
use App\Entity\Vegetable;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture 
{
    private $passwordHasher;
    // injecter une dépendance
    public function __construct(UserPasswordHasherInterface $passwordHasherInterface)
    {
        // Permet de hasher un mot de passe
        $this->passwordHasher = $passwordHasherInterface;
    }

    public function load(ObjectManager $manager): void 
    {       
        // Création des Meal
        $mealList = $this->getMeal();
        foreach ($mealList as $currentMeal) {
            // création de l'objet Meal
            $mealObject = new Meal();
            $mealObject->setName($currentMeal['name']);
            $mealObject->setCreatedAt(new DateTimeImmutable($currentMeal['created_at']));

            $manager->persist($mealObject);
        }

        // Création des Measures
        $measureList = $this->getMeasure();

        foreach ($measureList as $currentMeasure) {
            // Création de l'objet Measure
            $measureObject = new Measure();
            $measureObject->setType($currentMeasure['type']);
            $measureObject->setCreatedAt(new DateTimeImmutable($currentMeasure['created_at']));

            $manager->persist($measureObject);
        }
        
        // Création des genres
        $genreList = $this->getGenre();
        
        foreach ($genreList as $currentGenre) {
            // création de l'objet Genre
            $genreObject = new Genre();
            $genreObject->setName($currentGenre['name']);
            $genreObject->setCreatedAt(new DateTimeImmutable($currentGenre['created_at']));
            
            $manager->persist($genreObject);
            
        }
        
        // Création des botanicals
        $botanicalList = $this->getBotanical();
        
        foreach ($botanicalList as $currentBotanical) {
            // Création de l'objet Botanical
            $botanicalObject = new Botanical();
            $botanicalObject->setName($currentBotanical['name']);
            $botanicalObject->setCreatedAt(new DateTimeImmutable($currentBotanical['created_at']));
            
            $manager->persist($botanicalObject);
        }     
        
        // Creation des Ingredients
        $ingredientList = $this->getIngredient();
        
        foreach ($ingredientList as $currentIngredient) {
            // création de l'objet Ingredient
            $ingredientObject = new Ingredient();
            $ingredientObject->setName($currentIngredient['name']);
            $ingredientObject->setCreatedAt(new DateTimeImmutable($currentIngredient['created_at']));
            
            $manager->persist($ingredientObject);
        }

        // Creation des Month
        $monthList = $this->getMonths();
        foreach ($monthList as $currentMonth) {
            // création de l'objet Month
            $monthObject = new Month();
            $monthObject->setName($currentMonth['name']);
            $monthObject->setCreatedAt(new DateTimeImmutable($currentMonth['created_at']));
            
            $manager->persist($monthObject);
    }        
        // Envoie des entités en base de données
        $manager->flush();

        
        // Création des recettes
        $recipeList = $this->getRecipe();
        foreach($recipeList as $currentRecipe) {
            // Création de l'object Recipe
            $recipeObject = new Recipe();
            $recipeObject->setTitle($currentRecipe['title']);
            $recipeObject->setImage($currentRecipe['image']);
            $recipeObject->setDescription($currentRecipe['description']);
            $recipeObject->setInstruction($currentRecipe['instruction']);
            $recipeObject->setDuration($currentRecipe['duration']);
            $recipeObject->setServing($currentRecipe['serving']);
            $recipeObject->setCreatedAt(new DateTimeImmutable($currentRecipe['created_at']));
            
            // Association avec les meal
            $MealRepository = $manager->getRepository(Meal::class);
            $mealList = $MealRepository->findAll();
            foreach ($mealList as $meal)
            {
                $currentMealName = $meal->getName();
                if ($currentMealName === $currentRecipe['meal'])
                {
                    $recipeObject->setMeal($meal);
                }
            }
            
            $manager->persist($recipeObject);            
        }
        
        // Création des vegetables - on récupère la liste des vegetables
        $vegetableList = $this->getVegetables();

        // Boucle sur chaque vegetable de la liste
        foreach($vegetableList as $currentVegetable) {

            // création d'un nouvel objet Vegetable
            $vegetableObject = new Vegetable();

            // On remplit de données les propriétés du vegetable (à partir de tableaux plus bas)
            $vegetableObject->setTitle($currentVegetable['title']);
            $vegetableObject->setImage($currentVegetable['image']);
            $vegetableObject->setDescription($currentVegetable['description']);
            $vegetableObject->setBenefits($currentVegetable['benefits']);
            $vegetableObject->setLocal($currentVegetable['local']);
            $vegetableObject->setConservation($currentVegetable['conservation']);
            $vegetableObject->setCreatedAt(new DateTimeImmutable($currentVegetable['created_at']));
            
            // Association du vegetable avec avec le genre
            // On récupère le repository Genre
            $genreRepository = $manager->getRepository(Genre::class);
            // On récupère la liste de tous les genres
            $genreList = $genreRepository->findAll();
            // Boucle qui permet de parcourir chaque genre
            foreach ($genreList as $genre)
            {
                // On récupère le nom du genre
                $currentGenreName = $genre->getName();

                // Si le nom du genre actuel correspond au genre du vegetable
                if ($currentGenreName === $currentVegetable['genre'])
                {
                    // On associe le genre au vegetable
                    $vegetableObject->setGenre($genre);
                }
            }

            // Association du vegetable avec avec botanical 
            $botanicalRepository = $manager->getRepository(Botanical::class);
            $botanicalList = $botanicalRepository->findAll();
            foreach ($botanicalList as $botanical)
            {
                $currentBotanicalName = $botanical->getName();
                // On compare le nom des entité Botanical avec celui du tableau
                if ($currentBotanicalName === $currentVegetable['botanical'])
                {
                    // S'ils sont identique on associe l'ingrédient avec le vegetable
                    $vegetableObject->setBotanical($botanical);
                }
            }
            
            // Association du vegetable avec avec un ingredient
            $ingredientRepository = $manager->getRepository(Ingredient::class);
            $ingredientList = $ingredientRepository->findAll();
            foreach ($ingredientList as $ingredient)
            {
                $currentIngredientName = $ingredient->getName();
                // On compare le nom des entité Ingredient avec celui du tableau
                if ($currentIngredientName === $currentVegetable['ingredient'])
                {
                    // S'ils sont identique on associe l'ingrédient avec le vegetable
                    $vegetableObject->setIngredient($ingredient);
                }
            }
          
            // Association du vegetable avec avec les mois
            $monthRepository = $manager->getRepository(Month::class);
            $monthList = $monthRepository->findAll();

            $vegetableMonth = $currentVegetable['month'];
            foreach($vegetableMonth as $month) {
                foreach ($monthList as $monthEntity) {
                    $currentMonthName = $monthEntity->getName();
                    if ($currentMonthName === $month) {
                        $vegetableObject->addMonth($monthEntity);
                    }
                }
            }
            // On persist 
            $manager->persist($vegetableObject);
        }
        
        // On applique dans la base de données, on enregistre tous les nouveaux vegetables
        $manager->flush();

        // Création Content
        for ($i = 1; $i <= 25; $i++) {
            $quantityList = $this->getQuantity();
            
            $quantityRandom = mt_rand(0, count($quantityList) -1);
            $currentContent = $quantityList[$quantityRandom];

            // Création de l'objet Content
            $contentObject = new Content();
            $contentObject->setQuantity($currentContent['quantity']);
            
            // Association aléatoire avec measure 
            $measureRepository = $manager->getRepository(Measure::class);
            $measureList = $measureRepository->findAll();
            $measureRandom = mt_rand(0, count($measureList) -1);
            $currentMeasureId = $measureList[$measureRandom];
            $contentObject->setMeasure($currentMeasureId);
            
            // Association aléatoire avec ingredient 
            $ingredientRepository = $manager->getRepository(Ingredient::class);
            $ingredientList = $ingredientRepository->findAll();
            $ingredientRandom = mt_rand(0, count($ingredientList) -1);
            $currentIngredientId = $ingredientList[$ingredientRandom];
            $contentObject->setIngredient($currentIngredientId);
            
            // Association aléatoire avec recipe 
            $recipeRepository = $manager->getRepository(Recipe::class);
            $recipeList = $recipeRepository->findAll();
            $recipeRandom = mt_rand(0, count($recipeList) -1);
            $currentRecipeId = $recipeList[$recipeRandom];
            $contentObject->setRecipe($currentRecipeId);
            
            $manager->persist($contentObject);       
        }

        // Création des members
        $memberList = $this->getMember();

        // On créer en premier les users qui vont être associés aux members
        foreach ($memberList as $currentUser) {
            $userObject = new User();
            $userObject->setEmail($currentUser['email']);
            $userObject->setNewsletter($currentUser['newsletter']);
            $userObject->setCreatedAt(new DateTimeImmutable($currentUser['created_at']));
            $manager->persist($userObject);
        }
        // on les ajoute en base de données
        $manager->flush();

        // On récupère la liste des entités users enregistré juste avant
        $userRepository = $manager->getRepository(User::class);
        $userList = $userRepository->findAll();

        foreach($memberList as $currentMember) {
            $memberObject = new Member();
            $memberObject->setPseudo($currentMember['pseudo']);
            $memberObject->setRoles($currentMember['roles']);
            $hashedPassword = $this->passwordHasher->hashPassword($memberObject, $currentMember['password']);
            $memberObject->setPassword($hashedPassword);
            $memberObject->setCreatedAt(new DateTimeImmutable($currentMember['created_at']));
            
            foreach ($userList as $userObject) {
                if ($userObject->getEmail() === $currentMember['email']) {
                    $memberObject->setUser($userObject);
                }
            }    
            
            // Association avec les recettes (favoris)

            // On récupère la liste des entités recettes
            $recipeRepository = $manager->getRepository(Recipe::class);
            $recipeList = $recipeRepository->findAll();

            // Pour chaque member on ajoute entre 0 et 8 recettes en favoris aléatoirement
            for ($i = 1; $i <= mt_rand(0, 8); $i++) {
            $recipeRandom = mt_rand(0, count($recipeList) -1);
            $currentRecipeId = $recipeList[$recipeRandom];
            $memberObject->addRecipe($currentRecipeId);
        }
            
            $manager->persist($memberObject);

        }


        // Création des users - on récupère la liste des users existants
      
        $userList = $this->getUser();

        // On boucle sur chaque user dans cette liste
        foreach($userList as $currentUser) {

        // Création d'un nouvel objet pour chaque user
        $userObject = new User();

        // On ajoute a ce user un email, newsletter, createdAt (à partir de tableaux plus bas qui contiennent les données)
        $userObject->setEmail($currentUser['email']);
        $userObject->setNewsletter($currentUser['newsletter']);
        $userObject->setCreatedAt(new DateTimeImmutable($currentUser['created_at']));
        
        // On persist l'objet user
        $manager->persist($userObject);

        }

        // On applique les changements dans la base de données (on enregistre tous les nouveaux users)
        $manager->flush();
    }

    // Tableau des Meals
    public function getMeal() {
        return [
            [
                'name' => 'Entrée',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Plat',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Dessert',
                'created_at' => '2010-03-05',
            ],
        ];
    }

    // Tableau des Genres
    public function getGenre() {
        return [
            [
                'name' => 'Fruit',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légume',
                'created_at' => '2010-03-05',
            ],
        ];
    }

    // Tableau des Measures
    public function getMeasure() {
        return [
            [
                'type' => '',
                'created_at' => '2010-03-05',
            ],
            [
                'type' => 'ml',
                'created_at' => '2010-03-05',
            ],
            [
                'type' => 'cl',
                'created_at' => '2010-03-05',
            ],
            [
                'type' => 'l',
                'created_at' => '2010-03-05',
            ],
            [
                'type' => 'g',
                'created_at' => '2010-03-05',
            ],
            [
                'type' => 'kg',
                'created_at' => '2010-03-05',
            ],
          ];
    }


    // tableau des Botanicals
    public function getBotanical() {
        return [
            [
                'name' => 'Légumes racines',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légumes bulbes',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légumes tiges',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légumes graines',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légumes fleurs',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légumes feuilles',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Légumes fruits',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fruits à noyau',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fruits à pépins',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fruits rouges',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Agrumes',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fruits à coque',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fruits exotiques',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Baies',
                'created_at' => '2010-03-05',
            ],
        ];
    }

    // tableau des Ingredients
    public function getIngredient() {
        return [
            [
                'name' => 'Tomate',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Aubergine',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Pomme de terre',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Farine',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Oeuf',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Oignon',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Figue',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Concombre',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Salade',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fraise',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Carotte',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Citron',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Banane',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Pomme',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Pâte feuilletée',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Beurre demi-sel',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Sucre',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Sauce soja salée',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Thon à l\'huile',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Pain de mie',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Fromage frais',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Miel',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Gnocchis',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Epinards',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Gorgonzola',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Basilic',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Huile d\'olive',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Champignon',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Chou',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Courgette',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Carotte',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Orange',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Kiwi',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Oignon',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Fenouil',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Courgette',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Cassis',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Poulet',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Lait',
                'created_at' => '2023-03-05',
            ],
            [
                'name' => 'Pâtes fraiches',
                'created_at' => '2023-03-05',
            ],
        ];
    }

    // Tableaud es Quantity
    public function getQuantity() {
        return [
            [
                'quantity' => 1,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 2,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 3,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 4,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 5,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 10,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 20,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 25,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 50,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 100,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 200,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 250,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 300,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 400,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 500,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 600,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 700,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 750,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 800,
                'created_at' => '2010-03-05',
            ],
            [
                'quantity' => 900,
                'created_at' => '2010-03-05',
            ],
        ];
    }

    // Tableau des Recipes
    public function getRecipe() {
        return [
            [
                'title' => 'Pizza aux champignons & mozarella',
                'image' => 'https://cdn.pixabay.com/photo/2015/04/03/19/02/pizza-705680_1280.jpg',
                'description' => 'La pizza est une recette de cuisine traditionnelle de la cuisine italienne, originaire de Naples à base de galette de pâte à pain, garnie principalement d\'huile d\'olive, de sauce tomate, de mozzarella et d\'autres ingrédients et cuite au four.',
                'instruction' => 'Etape 1 : réparer une pâte à pizza ou l\'acheter toute faite, Etape 2 : Étaler la pâte et la recouvrir d\'huile d\'olive, Etape 3 : Couper les tomates en rondelles, la mozzarella en cubes et le jambon en petits carrés. Éplucher l\'oignon et les champignons. Les hacher finement.',
                'duration' => 120,
                'serving' => 4,
                'created_at' => '2010-03-05',
                'meal' => 'Plat',
            ],
            [
                'title' => 'Tatin de tomates cerises',
                'image' => 'https://cdn.pixabay.com/photo/2016/11/13/22/04/tomatoes-1822187_1280.jpg',
                'description' => 'Une si jolie tarte !',
                'instruction' => 'Étape 1 : Préchauffez le four à 200°C. Dans une poêle à feu vif ajoutez la sauce soja, le sucre et le beurre. Faire réduire pour faire un caramel. Étape 2 : Coupez les tomates cerises en deux. Étape 3 : Versez le caramel dans le fond du plat et y ajouter les tomates. Étape 4 : Disposez la pâte feuilletée sur le dessus. Étape 5 : Faites un trou au milieu de la pate puis enfournez à 200°C pendant 20 minutes. Étape 6 : Sortez la tarte du four, attendez que la tarte refoidisse un peu. Démoulez : placez une grande assiette sur votre plat puis retournez le tout. Servez avec un peu de basilic !',
                'duration' => 30,
                'serving' => 4,
                'created_at' => '2010-03-05',
                'meal' => 'Plat',
            ],
            [
                'title' => 'Aubergine parma',
                'image' => 'https://cdn.pixabay.com/photo/2021/11/19/18/29/eggplant-6810008_1280.jpg',
                'description' => 'Des aubergines bien gourmandes',
                'instruction' => 'Étape 1 : Préchauffez le four à 220°C. Coupez les aubergines en deux. Étape 2 : Sur une plaque allant au four, disposez les aubergines. Ajoutez un filet d\'huile d\'olive, salez et poivrez. Enfournez pour 20 minutes. Étape 3 : Ajoutez la sauce tomate, la mozzarella et le parmesan sur les aubergines. Enfournez à nouveau pour 20 minutes de cuisson. Étape 4 : Sortez les aubergines du four et ajoutez quelques feuilles de basilic par-dessus. Accompagnez d\'une salade assaisonnée !',
                'duration' => 45,
                'serving' => 4,
                'created_at' => '2010-03-05',
                'meal' => 'Plat',
            ],
            [
                'title' => 'Sandwich thon & concombre',
                'image' => 'https://cdn.pixabay.com/photo/2018/07/24/18/15/sushi-3559729_1280.jpg',
                'description' => 'Un mélange de légumes',
                'instruction' => 'Étape 1 : Lavez puis coupez le concombre en tranches. Étape 2 : Tartinez vos tranches de pain de fromage frais puis ajouter des lamelles d\'oignon rouge. Étape 3 : Ajoutez le thon égoutté et les lamelles de concombre sur vos tranches de pain. Étape 4 : Refermez le sandwich et coupez-le en deux si vous le souhaitez !',
                'duration' => 5,
                'serving' => 1,
                'created_at' => '2010-03-05',
                'meal' => 'Plat',
            ],
            [
                'title' => 'Salade de tomate, fraise & basilic',
                'image' => 'https://cdn.pixabay.com/photo/2018/01/12/10/19/basil-3077929_1280.jpg',
                'description' => 'Pour un été tout en douceur',
                'instruction' => 'Étape 1 : Coupez les tomates et les fraises en quartiers. Les mettre dans un saladier avec la burrata en morceaux et le basilic. Étape 2 : Pour la vinaigrette, mélangez le vinaigre balsamique et 4 cuillères à soupe d\'huile d\'olive, ajoutez du sel et du poivre. Mélangez à votre salade !',
                'duration' => 10,
                'serving' => 4,
                'created_at' => '2010-03-05',
                'meal' => 'Entrée',
            ],
            [
                'title' => 'Tarte aux figues',
                'image' => 'https://cdn.pixabay.com/photo/2017/11/11/20/57/food-2940452_1280.jpg',
                'description' => 'Une magnifique tarte aux figues, pour allier légèreté et gourmandise',
                'instruction' => 'Étape 1 : Préchauffez le four à 200°C. Coupez les figues en 4. Étape 2 : Déroulez la pâte feuilletée et déposez-la sur une plaque de cuisson recouverte de papier sulfurisé. Saupoudrez de poudre d’amande. Étape 3 : Répartissez par-dessus les figues coupées. Saupoudrez de sucre. Étape 4 : Enfourner pour 30 minutes !',
                'duration' => 120,
                'serving' => 6,
                'created_at' => '2010-03-05',
                'meal' => 'Dessert',
            ],
            [
                'title' => 'Soupe alphabet',
                'image' => 'https://cdn.pixabay.com/photo/2023/05/27/13/49/soup-8021565_1280.jpg',
                'description' => 'Une soupe réconfortante aux doux souvenirs d\'enfance.',
                'instruction' => 'Étape 1 : Ecrasez les tomates dans un bol. Étape 2 : Émincez les oignons. Étape 3 : Dans une casserole, faites dorer les oignons dans un filet d\'huile d\'olive 1 à 2 minutes. Étape 4 : Ajoutez les tomates avec leur jus et poursuivez la cuisson 2 minutes. Ajoutez 120cl d\'eau et le cube de bouillon. Portez à ébullition. Étape 5 : Ajoutez les pâtes et poursuivez la cuisson pendant 8 min. Salez, poivrez !',
                'duration' => 20,
                'serving' => 4,
                'created_at' => '2010-03-05',
                'meal' => 'Plat',
            ],
            [
                'title' => 'Gnocchis aux épinards & gorgonzola',
                'image' => 'https://cdn.pixabay.com/photo/2020/02/03/15/27/noodles-4815929_1280.jpg',
                'description' => 'Simple et efficace !',
                'instruction' => 'Étape 1 : Mettre une noisette de beurre dans une poêle et faites revenir les gnocchis 2 minutes à feu vif. Étape 2 : Ajoutez les épinards, la crème et le gorgonzola. Étape 3 : Mélangez et couvrez pour 3 minutes de cuisson à feu moyen. Étape 4 : Salez et poivrez !',
                'duration' => 120,
                'serving' => 4,
                'created_at' => '2010-03-05',
                'meal' => 'Plat',
            ],
            [
                'title' => 'Carottes rôties au miel',
                'image' => 'https://cdn.pixabay.com/photo/2018/06/10/21/35/carrots-3467399_1280.jpg',
                'description' => 'Tout en douceur',
                'instruction' => 'Étape 1: Préchauffez le four à 180°C. Epluchez et coupez les carottes en 2 dans la longueur. Disposez-les sur une plaque de cuisson ou dans un plat. Étape 2 : Saupoudrez-les de cumin, ajoutez un filet d\'huile d\'olive et de miel, salez, poivrez, puis enfournez pendant 45 à 50 minutes !',
                'duration' => 60,
                'serving' => 2,
                'created_at' => '2010-03-05',
                'meal' => 'Entrée',
            ],
            [
                'title' => 'Yaourt & poire',
                'image' => 'https://cdn.pixabay.com/photo/2017/06/21/17/57/dessert-2428038_1280.jpg',
                'description' => 'Rien de plus simple !',
                'instruction' => 'Étape 1 : Coupez les poires. Décortiquez et concassez les pistaches. Étape 2 : Mettez le yaourt dans un bol, ajoutez les poires, les pistaches et le sirop d\'érable !',
                'duration' => 2,
                'serving' => 1,
                'created_at' => '2010-03-05',
                'meal' => 'Dessert',
            ],
          ];
    }
  
    // Tableau des Month
    public function getMonths() {
        return [
            [
                'name' => 'Janvier',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Février',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Mars',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Avril',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Mai',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Juin',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Juillet',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Août',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Septembre',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Octobre',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Novembre',
                'created_at' => '2010-03-05',
            ],
            [
                'name' => 'Décembre',
                'created_at' => '2010-03-05',
            ],
        ];
    }

    // Tableau des vegetables
    public function getVegetables() {
        return [
            [   
                'title' => 'Tomates cerises',
                'description' => 'La tomate cerise est un type de variété de tomate, cultivée comme cette dernière pour ses fruits - mais de taille réduite - consommés comme légumes. Les tomates cerises sont généralement considérées comme des hybrides entre Solanum pimpinellifolium L. et la tomate cultivée, issue de l\'espèce Solanum lycopersicum.',
                'image' => 'https://cdn.pixabay.com/photo/2017/08/01/16/34/cherry-tomatoes-2566449_1280.jpg',
                'benefits' => 'Vitamines C',
                'local' => true,
                'conservation' => 'Malgré ce que l\'on croit, , il faut éviter de placer les tomates cerises au frigo. L\'air froid va les empécher de continuer à mûrir.',
                'created_at' => '2010-03-05',
                'month' => ['Juillet', 'Août', 'Septembre'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Tomate',
            ],
            [
                'title' => 'Tomates coeur de boeuf',
                'description' => 'Cœur de bœuf ou cuor di bue est l\'appellation de plusieurs cultivars de tomate d\'origine italienne. La cœur de bœuf originelle est une variété de grosse tomate dont la forme rappelle celle d\'un cœur de bovin, pouvant atteindre un poids de 500 à 600 grammes.',
                'image' => 'https://cdn.pixabay.com/photo/2016/04/15/20/45/tomato-1331862_1280.jpg',
                'benefits' => 'Vitamine C',
                'local' => true,
                'conservation' => 'L\'idéal est de conserver les tomates à température ambiante, afin de préserver leur arôme et leur texture. Lavez soigneusement les tomates',
                'created_at' => '2010-03-05',
                'month' => ['Juillet', 'Août', 'Septembre'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Tomate',
            ],
            [
                'title' => 'Pomme golden',
                'description' => 'La Golden Delicious est une pomme jaune très populaire dans le monde entier. En France, c\'est la pomme la plus cultivée puisqu\'elle représente plus d\'un tiers de la production nationale. Communément appelée « Golden », son succès vient de sa capacité à répondre au goût des consommateurs, aux exigences de la production et du commerce moderne. Toutefois, les gastronomes lui reprochent souvent son manque de goût et sa forte teneur en eau.',
                'image' => 'https://cdn.pixabay.com/photo/2019/01/23/10/57/apple-3950021_1280.jpg',
                'benefits' => 'La pomme est également recommandée pour avoir une bonne santé cérébrale car elle contient un antioxydant',
                'local' => true,
                'conservation' => 'Si vous voulez conserver beaucoup de pommes pendant plusieurs mois, une atmosphère avec une température de 10°, isolée de la lumière et des grandes variations de température',
                'created_at' => '2010-03-05',
                'month' => ['Septembre', 'Octobre'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Pomme',
            ],
            [
                'title' => 'Pomme gala',
                'description' => 'Les pommes Gala sont plutôt petites. Rouge orangé à rayures verticales, elles résistent bien aux chocs bien qu\'elles aient une peau très fine. La Gala est une pomme très sucrée, faiblement acide avec une petite pointe d’amertume; ferme et juteuse, très croquante. Certains lui trouvent des arômes de banane et de poire',
                'image' => 'https://cdn.pixabay.com/photo/2023/06/05/19/55/apples-8043221_1280.jpg',
                'benefits' => 'La pomme est pour avoir une bonne santé cérébrale car elle contient un antioxydant',
                'local' => true,
                'conservation' => 'Si vous voulez conserver beaucoup de pommes pendant plusieurs mois, une atmosphère avec une température de 10°, isolée de la lumière et des grandes variations de température',
                'created_at' => '2010-03-05',
                'month' => ['Octobre', 'Novembre', 'Décembre'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Pomme',
            ],
            [
                'title' => 'Pomme granny smith',
                'description' => 'La Granny Smith est une pomme verte acidulée et peu sucrée, à la chair ferme et friable1. Elle est issue d\'un pommier domestique Malus domestica Granny Smith, un cultivar très populaire apparu en Australie en 1868 à la suite d\'un « semis chanceux » réalisé par une vieille dame, Maria Ann Smith surnommée Granny Smith (« Mamie Smith » en anglais)',
                'image' => 'https://cdn.pixabay.com/photo/2018/12/27/22/24/green-apple-3898527_1280.jpg',
                'benefits' => 'La pomme est recommandée pour avoir une bonne santé cérébrale car elle contient un antioxydant',
                'local' => true,
                'conservation' => 'Si vous voulez conserver beaucoup de pommes pendant plusieurs mois, une atmosphère avec une température de 10°, isolée de la lumière et des grandes variations de température',
                'created_at' => '2010-03-05',
                'month' => ['Juillet', 'Août'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Pomme',
            ],
            [
                'title' => 'Citron vert',
                'description' => 'La lime, lime acide ou citron vert est un agrume. « Lime » est l\'appellation la plus utilisée au Canada alors que l\'appellation « citron vert » est davantage utilisée en France. C\'est le fruit du limettier, arbuste de la famille des Rutacées, dont il existe deux espèces : Citrus latifolia et Citrus aurantiifolia. Elles sont originaires d\'Asie du Sud-Est.',
                'image' => 'https://cdn.pixabay.com/photo/2020/07/24/21/58/lemon-5435158_1280.jpg',
                'benefits' => 'Le citron aide le foie à filtrer les déchets du corps',
                'local' => true,
                'conservation' => 'Si vous consommez régulièrement du citron, vous pouvez tout à fait le laisser à portée à température ambiante, il restera en bon état une dizaine de jours.',
                'created_at' => '2010-03-05',
                'month' => ['Juin', 'Juillet', 'Août'],
                'botanical' => 'Agrumes',
                'genre' => 'Fruit',
                'ingredient' => 'Citron',
            ],
            [
                'title' => 'Citron jaune',
                'description' => 'Le citron (ou citron jaune) est un agrume, fruit du citronnier Citrus limon. Ce fruit, mûr, a une écorce qui va du vert tendre au jaune éclatant sous l\'action du froid. La maturité est en fin d\'automne et début d\'hiver dans l’hémisphère nord. Sa chair est juteuse, le citron acide est riche en vitamine C',
                'image' => 'https://cdn.pixabay.com/photo/2021/11/26/12/45/lemon-6825808_1280.jpg',
                'benefits' => 'Le citron aide le foie à filtrer les déchets du corps',
                'local' => true,
                'conservation' => 'Si vous consommez régulièrement du citron, vous pouvez tout à fait le laisser à portée à température ambiante, il restera en bon état une dizaine de jours.',
                'created_at' => '2010-03-05',
                'month' => ['Juillet', 'Août', 'Septembre'],
                'botanical' => 'Agrumes',
                'genre' => 'Fruit',
                'ingredient' => 'Citron',
            ],
            [
                'title' => 'Chou rouge',
                'description' => 'Avec sa tête bien ferme et ses feuilles lisses, le chou rouge fait partie des choux cabus. De forme ronde ou ovale, il pèse en moyenne 1,5 kg. Généralement pourpre, sa couleur varie du rouge au violet selon l’acidité du sol dans lequel il a poussé.',
                'image' => 'https://cdn.pixabay.com/photo/2019/11/19/13/26/red-cabbage-4637426_1280.jpg',
                'benefits' => 'Vitamine C',
                'local' => true,
                'conservation' => 'Vous pouvez garder le chou rouge de votre panier 3 à 4 semaines dans le bac à légumes de votre réfrigérateur.',
                'created_at' => '2010-03-05',
                'month' => ['Septembre', 'Octobre', 'Novembre', 'Décembre', 'Janvier'],
                'botanical' => 'Légumes feuilles',
                'genre' => 'Légume',
                'ingredient' => 'Chou',
            ],
            [
                'title' => 'Chou de Bruxelles',
                'description' => 'Notamment grâce à des saveurs moins amères et plus sucrées, davantage adaptées aux goûts actuels. Les jeunes chefs l’ont bien compris : le chou de Bruxelles n’est plus exclusivement le symbole d’une nourriture rustique traditionnelle mais peut jouer la carte de la modernité en s’accomodant de mille façons.',
                'image' => 'https://cdn.pixabay.com/photo/2014/04/07/02/29/brussels-sprouts-318200_1280.jpg',
                'benefits' => 'Riche en calcium et en potassium',
                'local' => true,
                'conservation' => 'Pour les conserver jusqu\'à deux semaines dans le bac à légumes du réfrigérateur',
                'created_at' => '2023-08-11',
                'month' => ['Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février', 'Mars'],
                'botanical' => 'Légumes feuilles',
                'genre' => 'Légume',
                'ingredient' => 'Chou',
            ],
            [
                'title' => 'Salade romaine',
                'description' => 'La laitue romaine (Lactuca sativa var. longifolia) est une variété de laitue qui pousse avec un cœur ferme et une longue tête de feuilles robustes. Au contraire de la plupart des laitues, elle tolère une chaleur élevée. Elle ne présentait pas de cœur à l\'origine, mais la sélection tend à améliorer sa formation.',
                'image' => 'https://cdn.pixabay.com/photo/2015/05/17/14/29/salad-771056_1280.jpg',
                'benefits' => 'Vitamines B9, Vitamines C',
                'local' => true,
                'conservation' => 'Essuyez ses feuilles ou essorez-les mais laissez les un peu humides afin qu\'elles restent fraîches et ne sèchent pas',
                'created_at' => '2010-03-05',
                'month' => ['Avril', 'Mai', 'Juin', 'Juillet'],
                'botanical' => 'Légumes feuilles',
                'genre' => 'Légume',
                'ingredient' => 'Salade',
            ],
            [
                'title' => 'Salade batavia',
                'description' => 'Elle est craquante et tendre, dotée d\'une grosse pomme vert clair ou vert jaune, gaufrée, avec les bords découpés, au goût légèrement sucré. Certaines variétés sont parfois rougeâtres. Il convient de la choisir avec une base bien blanche, signe de fraîcheur.',
                'image' => 'https://cdn.pixabay.com/photo/2016/07/22/01/18/lettuce-1533958_1280.jpg',
                'benefits' => 'Vitamines B9, Vitamines C',
                'local' => true,
                'conservation' => 'Essuyez ses feuilles ou essorez-les mais laissez les un peu humides afin qu\'elles restent fraîches et ne sèchent pas',
                'created_at' => '2010-03-05',
                'month' => ['Mars', 'Avril', 'Mai'],
                'botanical' => 'Légumes feuilles',
                'genre' => 'Légume',
                'ingredient' => 'Salade',
            ],
            [
                'title' => 'Mini concombre',
                'description' => 'Le concombre (Cucumis sativus) est une espèce de plante à fleurs de la famille des Cucurbitacées. C\'est une plante potagère herbacée, rampante, de la même famille que la calebasse africaine, le melon ou la courge. C\'est botaniquement un fruit qui est consommé comme un légume.',
                'image' => 'https://cdn.pixabay.com/photo/2015/07/17/13/44/cucumbers-849269_1280.jpg',
                'benefits' => 'Vitamines B',
                'local' => true,
                'conservation' => 'Conservez le concombre (entier ou coupé) au réfrigérateur, dans le bac à légumes, dans un sac plastique ou autre contenant hermétique',
                'created_at' => '2010-03-05',
                'month' => ['Mai', 'Juin'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Concombre',
            ],
            [
                'title' => 'Concombre blanc',
                'description' => 'Le concombre (Cucumis sativus) est une espèce de plante à fleurs de la famille des Cucurbitacées. C\'est une plante potagère herbacée, rampante, de la même famille que la calebasse africaine, le melon ou la courge. C\'est botaniquement un fruit qui est consommé comme un légume.',
                'image' => 'https://cdn.pixabay.com/photo/2018/11/11/19/43/cucumbers-3809535_1280.jpg',
                'benefits' => 'Vitamines B',
                'local' => true,
                'conservation' => 'Conservez le concombre (entier ou coupé) au réfrigérateur, dans le bac à légumes, dans un sac plastique ou autre contenant hermétique',
                'created_at' => '2010-03-05',
                'month' => ['Avril', 'Mai', 'Juin', 'Juillet'],
                'botanical' => 'Fruits à pépins',
                'genre' => 'Fruit',
                'ingredient' => 'Concombre',
            ],
            [
                'title' => 'Aubergine de barbentane',
                'description' => 'Variété hâtive et productive. Elle produit des fruits allongés, de couleur violet foncé, très brillants. Sa chair ferme de couleur blanche est très fondante. Le feuillage ovale est épineux et duveteux. On la consomme cuite en ratatouille, ou grillée au barbecue.',
                'image' => 'https://cdn.pixabay.com/photo/2017/08/05/15/43/vegetable-2584412_1280.jpg',
                'benefits' => 'Bonne sources de fibres et faible en calories',
                'local' => true,
                'conservation' => 'gardez l\'aubergine à une température fraîche mais pas froide pour éviter qu\'elle ne se dessèche ou ne pourrisse.',
                'created_at' => '2010-03-05',
                'month' => ['Juin', 'Juillet', 'Août'],
                'botanical' => 'Légumes fruits',
                'genre' => 'Légume',
                'ingredient' => 'Aubergine',
            ],
            [
                'title' => 'Aubergine black beauty',
                'description' => 'Variété ancienne et précoce d\'environ 60 cm de haut et donnant de nombreux fruits d\'environ 13 cm de long. Ses fruits d\'un noir profond se conservent bien. On peut la consommer en ratatouille, farcie, grillée ou gratinée.',
                'image' => 'https://cdn.pixabay.com/photo/2022/08/08/19/07/eggplant-7373425_1280.jpg',
                'benefits' => 'Bonne sources de fibres et faible en calories',
                'local' => true,
                'conservation' => 'gardez l\'aubergine à une température fraîche mais pas froide pour éviter qu\'elle ne se dessèche ou ne pourrisse.',
                'created_at' => '2010-03-05',
                'month' => ['Juin', 'Juillet', 'Août', 'Septembre'],
                'botanical' => 'Légumes fruits',
                'genre' => 'Légume',
                'ingredient' => 'Aubergine',
            ],
            [
                'title' => 'Fraise gariguette',
                'description' => 'La fraise gariguette est la plus cultivée en France, c\'est la reine des fraises ! Très appréciée de tous, on la doit à une chercheuse de l\'INRA (devenu INRAE au 1er janvier 2020) d\'Avignon qui a mis au point la variété avec son équipe. C\'est une espèce précoce non remontante.',
                'image' => 'https://cdn.pixabay.com/photo/2021/09/23/05/30/strawberry-6648685_1280.jpg',
                'benefits' => 'Vitamine B',
                'local' => true,
                'conservation' => 'Déposez délicatement vos fraises dans une barquette perforée et placez-la dans le bac à légumes.',
                'created_at' => '2010-03-05',
                'month' => ['Avril', 'Mai', 'Juin'],
                'botanical' => 'Fruits rouges',
                'genre' => 'Fruit',
                'ingredient' => 'Fraise',
            ],
            [
                'title' => 'Fraise reine des vallées',
                'description' => 'Variété de type fraise des bois et remontante aux fruits plutôt allongés et très parfumés. Elle ne produit pas de stolons. A déguster nature ou en pâtisserie.',
                'image' => 'https://cdn.pixabay.com/photo/2018/04/29/11/54/strawberries-3359755_1280.jpg',
                'benefits' => 'Vitamine B',
                'local' => true,
                'conservation' => 'Déposez délicatement vos fraises dans une barquette perforée et placez-la dans le bac à légumes.',
                'created_at' => '2010-03-05',
                'month' => ['Avril', 'Mai', 'Juin'],
                'botanical' => 'Fruits rouges',
                'genre' => 'Fruit',
                'ingredient' => 'Fraise',
            ],
            [
                'title' => 'Banane cavendish',
                'description' => 'La banane est le fruit ou la baie dérivant de l’inflorescence du bananier. Les bananes sont des fruits très généralement stériles issus de variétés domestiquées. Seuls les fruits des bananiers sauvages et de quelques cultivars domestiques contiennent des graines. Les bananes sont vertes avant d\'être mûres et deviennent généralement jaunes avec des taches brunâtres à mâturité.',
                'image' => 'https://cdn.pixabay.com/photo/2023/08/27/12/41/bananas-8216891_1280.jpg',
                'benefits' => 'Riche en magnésium, potassium et calcium',
                'local' => false,
                'conservation' => 'Les bananes sont conservées à température ambiante. Si elles sont placées au réfrigérateur cela les empêchera de mûrir convenablement.',
                'created_at' => '2010-03-05',
                'month' => ['Octobre', 'Novembre', 'Décembre'],
                'botanical' => 'Fruits exotiques',
                'genre' => 'Fruit',
                'ingredient' => 'Banane',
            ],
            [
                'title' => 'Banane plantain',
                'description' => 'La banane plantain est une espèce hybride de plante de la famille des Musaceae. Comme la banane dessert, elle est un sous-groupe de l\'espèce Musa ×paradisiaca (issue du croisement entre Musa acuminata et Musa balbisiana).',
                'image' => 'https://cdn.pixabay.com/photo/2012/03/03/23/57/bananas-21686_1280.jpg',
                'benefits' => 'Riche en magnésium, potassium et calcium',
                'local' => false,
                'conservation' => 'Les bananes sont conservées à température ambiante. Si elles sont placées au réfrigérateur cela les empêchera de mûrir convenablement.',
                'created_at' => '2010-03-05',
                'month' => ['Septembre', 'Octobre', 'Novembre', 'Décembre', 'Janvier'],
                'botanical' => 'Fruits exotiques',
                'genre' => 'Fruit',
                'ingredient' => 'Banane',
            ],
            [
                'title' => 'Carotte primeur',
                'description' => 'La carotte (Daucus carota subsp. sativus) est une plante bisannuelle de la famille des Apiacées (aussi appelées Ombellifères), largement cultivée pour sa racine pivotante charnue, comestible, de couleur généralement orangée, consommée comme légume. La carotte représente, après la pomme de terre, le principal légume-racine cultivé dans le monde2. C\'est une racine riche en carotène.',
                'image' => 'https://cdn.pixabay.com/photo/2016/08/03/01/09/carrot-1565597_1280.jpg',
                'benefits' => 'Vitamine B',
                'local' => true,
                'conservation' => 'Conserver dans un sac perméable dans le tiroir à légumes ou dans l\'eau sur une tablette du réfrigérateur',
                'created_at' => '2010-03-05',
                'month' => ['Septembre', 'Octobre', 'Novembre', 'Décembre', 'Janvier'],
                'botanical' => 'Légumes racines',
                'genre' => 'Légume',
                'ingredient' => 'Carotte',
            ],
            [
                'title' => 'Orange',
                'description' => 'Son goût sucré légèrement acidulé excite les papilles. Consommée quotidiennement, elle apporte les vitamines nécessaires pour lutter contre le froid et la fatigue. Un concentré d’énergie et de bien-être à croquer et à boire !',
                'image' => 'https://cdn.pixabay.com/photo/2016/01/02/02/03/orange-1117645_1280.jpg',
                'benefits' => 'Une bonne source d\'antioxydants protecteurs',
                'local' => true,
                'conservation' => 'L\'orange est un fruit qui se conserve très bien à température ambiante',
                'created_at' => '2010-03-05',
                'month' => ['Novembre', 'Décembre', 'Janvier', 'Février', 'Mars'],
                'botanical' => 'Agrumes',
                'genre' => 'Fruit',
                'ingredient' => 'Orange',
            ],
            [
                'title' => 'Kiwi',
                'description' => 'En France, il est cultivé dans trois régions, dont l’une détient même un Label Rouge !',
                'image' => 'https://cdn.pixabay.com/photo/2016/08/03/04/34/kiwi-1565847_1280.jpg',
                'benefits' => 'Vitamine C',
                'local' => true,
                'conservation' => 'Il se conservera frais jusqu\'à 7 jours à température ambiante',
                'created_at' => '2010-03-05',
                'month' => ['Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril'],
                'botanical' => 'Baies',
                'genre' => 'Fruit',
                'ingredient' => 'Kiwi',
            ],
            [
                'title' => 'Oignon',
                'description' => 'S’invitant dans un nombre impressionnant de préparations, il se prête avec autant de réussite aux plats les plus rustiques (flamiche, gratinée…) qu’aux mets les plus sophistiqués (foie gras poêlé, magret de canard…). Ce bulbe, organe de réserves nutritives pour la plante et sa fleur, présente aussi des qualités nutritionnelles qui vous invitent à conjuguer bien-être et plaisir de la table.',
                'image' => 'https://cdn.pixabay.com/photo/2018/07/15/20/43/onion-3540502_1280.jpg',
                'benefits' => 'Riche en fibre',
                'local' => true,
                'conservation' => 'Les oignons ont besoin d\'un endroit frais, très sec et à l\'abri de la lumière.',
                'created_at' => '2010-03-05',
                'month' => ['Août', 'Septembre','Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai'],
                'botanical' => 'Légumes bulbes',
                'genre' => 'Légume',
                'ingredient' => 'Oignon',
            ],
            [
                'title' => 'Fenouil',
                'description' => 'Référence symbolique et mythologique depuis l’Antiquité, c’est avant tout un aliment particulièrement intéressant sur le plan nutritionnel. On le retrouve avec plaisir dans des plats salés, mais aussi dans les desserts sucrés.',
                'image' => 'https://cdn.pixabay.com/photo/2016/04/06/12/39/fennel-1311691_1280.jpg',
                'benefits' => 'Propriété anti-inflammatoire',
                'local' => true,
                'conservation' => 'Au réfrigérateur, il se conserve bien une semaine dans un bac à légumes ou dans un contenant hermétique.',
                'created_at' => '2023-04-01',
                'month' => ['Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai'],
                'botanical' => 'Légumes bulbes',
                'genre' => 'Légume',
                'ingredient' => 'Fenouil',
            ],
            [
                'title' => 'Courgette',
                'description' => 'Ce légume du soleil qui fleure bon la Méditerranée est présent dans les assiettes tout l’été. Facile à préparer, la courgette se consomme sous toutes ses formes. Et sa richesse en vitamines vous donne de l’énergie à la belle saison.',
                'image' => 'https://cdn.pixabay.com/photo/2016/09/10/13/25/zucchini-1659094_1280.jpg',
                'benefits' => 'riche en fibres',
                'local' => true,
                'conservation' => 'Pour les garder plus longtemps, disposez-les plutôt à l\'obscurité, dans un endroit frais.',
                'created_at' => '2023-04-01',
                'month' => ['Mai', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre'],
                'botanical' => 'Légumes graines',
                'genre' => 'Légume',
                'ingredient' => 'Courgette',
            ],
            [
                'title' => 'Cassis',
                'description' => 'Deux grandes variétés composent la majorité des cultures, concentrées dans quatre régions françaises. Ses fruits acidulés et juteux se consomment aussi bien nature que préparés en dessert ou sous forme de liqueur (1) et de crème. ',
                'image' => 'https://cdn.pixabay.com/photo/2023/07/31/10/20/fruit-8160703_1280.jpg',
                'benefits' => 'il contribue à éliminer le sel et l\'eau en excès dans l\'organisme',
                'local' => true,
                'conservation' => 'Il se conserve dans le bac à légumes du réfrigérateur pendant 24 à 48 h.',
                'created_at' => '2023-04-01',
                'month' => ['Juillet', 'Août', 'Septembre'],
                'botanical' => 'Baies',
                'genre' => 'Fruit',
                'ingredient' => 'Cassis',
            ],
        ];
    }
    
    // tableau des users
    public function getUser() {
        return [
            [
                'email' => 'john@gmail.com',
                'newsletter' => true,
                'created_at' => '2017-03-05',
            ],
            [
                'email' => 'maria@hotmail.fr',
                'newsletter' => false,
                'created_at' => '2017-03-05',
            ],
            [
                'email' => 'sylvie@laposte.net',
                'newsletter' => true,
                'created_at' => '2017-03-05',
            ],
            [
                'email' => 'marie@yahoo.fr',
                'newsletter' => true,
                'created_at' => '2017-03-05',
            ],
            [
                'email' => 'pierre@gmail.com',
                'newsletter' => false,
                'created_at' => '2017-03-05',
            ],
            [
                'email' => 'nicolas@gmail.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'baptiste@hotmail.fr',
                'newsletter' => false,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'clément@gmail.com',
                'newsletter' => false,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'megane@yahoo.fr',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'eloise@gmail.com',
                'newsletter' => false,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'jimmy@butler.com',
                'newsletter' => false,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'victor@hotmail.fr',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'cécile@gmail.com',
                'newsletter' => false,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'kylian@laposte.net',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'email' => 'sarah@hotmail.fr',
                'newsletter' => false,
                'created_at' => '2023-10-12',
            ],
          ];
    }

    // Tableau des membres et users associés
    public function getMember() {
        return [
            [
                'pseudo' => 'gilles',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'gilles',
                'email' => 'gilles@gilles.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'david',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'david',
                'email' => 'david@david.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'cyprien',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'cyprien',
                'email' => 'cyprien@cyprien.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'maiana',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'maiana',
                'email' => 'maiana@maiana.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'antoine',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'antoine',
                'email' => 'antoine@antoine.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'patrice',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'patrice',
                'email' => 'patrice@patrice.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'caroline',
                'roles' => ['ROLE_USER'],
                'password' => 'caroline',
                'email' => 'caroline@caroline.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'romain',
                'roles' => ['ROLE_USER'],
                'password' => 'romain',
                'email' => 'romain@bleh.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'salomé',
                'roles' => ['ROLE_USER'],
                'password' => 'salomé',
                'email' => 'salome@salome.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'lucas',
                'roles' => ['ROLE_USER'],
                'password' => 'lucas',
                'email' => 'lucas@debleh.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'mélina',
                'roles' => ['ROLE_USER'],
                'password' => 'mélina',
                'email' => 'melina@melina.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'admin',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin',
                'email' => 'admin@admin.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'member',
                'roles' => ['ROLE_MEMBER'],
                'password' => 'member',
                'email' => 'member@member.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'zizou',
                'roles' => ['ROLE_USER'],
                'password' => 'zizou',
                'email' => 'zizou@zizou.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
            [
                'pseudo' => 'kameto',
                'roles' => ['ROLE_USER'],
                'password' => 'kameto',
                'email' => 'kameto@kameto.com',
                'newsletter' => true,
                'created_at' => '2023-10-12',
            ],
        ];
    }
}