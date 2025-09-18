<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            ['nom' => 'Entrées', 'couleur' => '#FF5733', 'image' => 'public/uploads/entree.jpg'],
            ['nom' => 'Plats principaux', 'couleur' => '#3366FF', 'image' => 'public/uploads/plats.jpg'],
            ['nom' => 'Desserts', 'couleur' => '#FFCC33', 'image' => 'public/uploads/desserts.jpg'],
            ['nom' => 'Boissons chaudes', 'couleur' => '#663300', 'image' => 'public/uploads/boisson_chaude.jpg'],
            ['nom' => 'Boissons froides', 'couleur' => '#00CCFF', 'image' => 'public/uploads/boisson_froide.jpg'],
            ['nom' => 'Salades', 'couleur' => '#66CC33', 'image' => 'public/uploads/salade.jpg'],
            ['nom' => 'Sandwichs', 'couleur' => '#FF6600', 'image' => 'public/uploads/sandwich.jpg'],
            ['nom' => 'Pâtes', 'couleur' => '#9966FF', 'image' => 'public/uploads/pates.jpg'],
            ['nom' => 'Pizzas', 'couleur' => '#CC3300', 'image' => 'public/uploads/pizzas.jpg'],
            ['nom' => 'Burgers', 'couleur' => '#FF3333', 'image' => 'public/uploads/burger.jpg'],
        ];
        $produitsData = [

            ['nom' => 'Garlic Butter Steak and Potatoes', 'ingredients' => 'Filet mignon, sel, poivre, herbes de Provence', 'prix' => 22.99, 'categorie' => 'Plats principaux', 'image' => 'public/uploads/platsPrincipale/Garlic Butter Steak and Potatoes.jpg'],
            ['nom' => 'Boeuf braisé a la Guinness', 'ingredients' => 'Spaghetti, pancetta, œufs, parmesan, crème', 'prix' => 14.49, 'categorie' => 'Plats principaux', 'image' => 'public/uploads/platsPrincipale/Boeuf braisé a la Guinness.jpg'],
            ['nom' => 'Chorba frik', 'ingredients' => 'Bœuf, carottes, oignons, vin rouge, champignons', 'prix' => 18.99, 'categorie' => 'Plats principaux', 'image' => 'public/uploads/platsPrincipale/chorba frik.jpg'],
            ['nom' => 'Nouilles sautées au poulet', 'ingredients' => 'Poulet, herbes fraîches, ail, beurre, citron', 'prix' => 16.99, 'categorie' => 'Plats principaux', 'image' => 'public/uploads/platsPrincipale/Nouilles sautées au poulet.jpg'],
            ['nom' => 'Saumon', 'ingredients' => 'Aubergines, courgettes, poivrons, tomates, oignons', 'prix' => 12.99, 'categorie' => 'Plats principaux', 'image' => 'public/uploads/platsPrincipale/saumon.jpg'],

            // Desserts
            ['nom' => 'Ricotta kunafa rolls', 'ingredients' => 'Biscuits à la cuillère, café, mascarpone, cacao', 'prix' => 899, 'categorie' => 'Desserts', 'image' => 'public/uploads/Desserts/Ricotta kunafa rolls.jpg'],
            ['nom' => 'Crepes Fraise&chocolat', 'ingredients' => 'Crème, sucre, vanille, jaunes d\'œufs', 'prix' => 749, 'categorie' => 'Desserts', 'image' => 'public/uploads/Desserts/crepes Fraise&chocolat.jpg'],
            ['nom' => 'Chocolote Strawberry', 'ingredients' => 'Chocolat noir, beurre, sucre, œufs, farine', 'prix' => 999, 'categorie' => 'Desserts', 'image' => 'public/uploads/Desserts/chocolote strawberry.jpg'],
            ['nom' => 'vanilla mousse cake', 'ingredients' => 'Pâte sablée, fruits de saison, gelée de fruit', 'prix' => 1099, 'categorie' => 'Desserts', 'image' => 'public/uploads/Desserts/vanilla mousse cake.jpg'],
            ['nom' => 'Glace', 'ingredients' => 'Choux, crème pâtissière, chocolat chaud', 'prix' => 1149, 'categorie' => 'Desserts', 'image' => 'public/uploads/Desserts/Glaces.jpg'],
            // Boissons froides
            ['nom' => 'Cocktail Mojito', 'ingredients' => 'Rhum, menthe, citron vert, sucre, eau gazeuse', 'prix' => 999, 'categorie' => 'Boissons froides', 'image' => 'public/uploads/boisson froide/mojito.jpg'],
            ['nom' => 'Smoothie fraise-banane', 'ingredients' => 'Fraises, bananes, yaourt, miel', 'prix' => 699, 'categorie' => 'Boissons froides', 'image' => 'public/uploads/boisson froide/smothis.jpg'],
            ['nom' => 'Gin fuz a la menthe', 'ingredients' => 'menthe', 'prix' => 449, 'categorie' => 'Boissons froides', 'image' => 'public/uploads/boisson froide/Gin_fuz.jpg'],
            ['nom' => 'wiskey glaces', 'ingredients' => 'Citron, sucre, eau', 'prix' => 399, 'categorie' => 'Boissons froides', 'image' => 'public/uploads/boisson froide/wiskey glaces.jpg'],
            ['nom' => 'Thé glacé au Caramel', 'ingredients' => 'Thé noir, pêches, sirop de sucre', 'prix' => 549, 'categorie' => 'Boissons froides', 'image' => 'public/uploads/boisson froide/caramel.jpg'],

            // Boissons chaudes
            ['nom' => 'Café au lait', 'ingredients' => 'Café, lait', 'prix' => 349, 'categorie' => 'Boissons chaudes', 'image' => 'public/uploads/boisson chaude/coffe.jpg'],
            ['nom' => 'Thé à la menthe', 'ingredients' => 'Thé vert, menthe fraîche, sucre', 'prix' => 449, 'categorie' => 'Boissons chaudes', 'image' => 'public/uploads/boisson chaude/the_menthe.jpg'],
            ['nom' => 'Chocolat chaud', 'ingredients' => 'Chocolat, lait, crème fouettée', 'prix' => 599, 'categorie' => 'Boissons chaudes', 'image' => 'public/uploads/boisson chaude/chocolat_chaud.jpg'],
            ['nom' => 'Slow cooker Hot', 'ingredients' => 'Café, lait, mousse de lait', 'prix' => 499, 'categorie' => 'Boissons chaudes', 'image' => 'public/uploads/boisson chaude/Slow cooker Hot.jpg'],
            ['nom' => 'Espresso', 'ingredients' => 'Café', 'prix' => 299, 'categorie' => 'Boissons chaudes', 'image' => 'public/uploads/boisson chaude/espresso.jpg'],

            ['nom' => 'Caprese Salade & Balsamic', 'ingredients' => 'Laitue, croûtons, parmesan, poulet grillé, sauce césar', 'prix' => 999, 'categorie' => 'Salades', 'image' => 'public/uploads/Salades/caprese Salade & Balsamic.jpg'],
            ['nom' => 'Salade avocat crevette', 'ingredients' => 'Pain grillé, tomates fraîches, basilic, huile d\'olive', 'prix' => 749, 'categorie' => 'Salades', 'image' => 'public/uploads/Salades/salade avocat crevette.jpg'],
            ['nom' => 'Salade de pomme de terre', 'ingredients' => 'Oignons, bouillon de bœuf, fromage, pain, vin blanc', 'prix' => 699, 'categorie' => 'Salades', 'image' => 'public/uploads/Salades/salade de pomme de terre.jpg'],
            ['nom' => 'Salade d\'ete', 'ingredients' => 'Calamars, farine, œufs, huile de friture, citron', 'prix' => 1099, 'categorie' => 'Salades', 'image' => 'public/uploads/Salades/Salade ete.jpg'],
            ['nom' => 'Salade Santa Fe', 'ingredients' => 'Escargots, beurre à l\'ail, persil, pain', 'prix' => 1299, 'categorie' => 'Salades', 'image' => 'public/uploads/Salades/salade Santa Fe.jpg'],


            ['nom' => 'Salade Balsamic', 'ingredients' => 'Laitue, croûtons, parmesan, poulet grillé, sauce césar', 'prix' => 9.99, 'categorie' => 'Entrées', 'image' => 'public/uploads/entree/Salade Balsamic.jpg'],
            ['nom' => 'sashimi poisson', 'ingredients' => 'Pain grillé, tomates fraîches, basilic, huile d\'olive', 'prix' => 7.49, 'categorie' => 'Entrées', 'image' => 'public/uploads/entree/sashimi poisson.jpg'],
            ['nom' => 'Nids de pommes de terre aux boeufs et bacon', 'ingredients' => 'Oignons, bouillon de bœuf, fromage, pain, vin blanc', 'prix' => 6.99, 'categorie' => 'Entrées', 'image' => 'public/uploads/entree/Nids de pommes de terre aux boeufs et bacon.jpg'],
            ['nom' => 'Food Plating', 'ingredients' => 'Calamars, farine, œufs, huile de friture, citron', 'prix' => 10.99, 'categorie' => 'Entrées', 'image' => 'public/uploads/entree/food Plating.jpg'],
            ['nom' => 'Sushi', 'ingredients' => 'Escargots, beurre à l\'ail, persil, pain', 'prix' => 12.99, 'categorie' => 'Entrées', 'image' => 'public/uploads/entree/sushi.jpg'],

        ];

        $slugger = new AsciiSlugger();

        foreach ($categoriesData as $data) {
            $categorie = new Categorie();
            $categorie->setNom($data['nom']);
            $categorie->setCouleur($data['couleur']);

            // Générer un nom de fichier unique pour l'image en utilisant le nom de la catégorie
            $imageName = $slugger->slug($data['nom']) . '.' . pathinfo($data['image'], PATHINFO_EXTENSION);
            // Copier l'image vers le dossier public/uploads
            copy($data['image'], 'public/uploads/' . $imageName);
            // Enregistrer le nom du fichier dans l'entité Categorie
            $categorie->setImage($imageName);

            $manager->persist($categorie);

            // Créer un tableau pour stocker les produits de cette catégorie
            $productsOfCategory[$data['nom']] = [];
        }

        $manager->flush();

// Chargement des produits
        foreach ($produitsData as $data) {
            $produit = new Produit();
            $produit->setNom($data['nom']);
            $produit->setIngredients($data['ingredients']);
            $produit->setPrix($data['prix']);

            // Recherche de la catégorie associée par son nom
            $categorie = $manager->getRepository(Categorie::class)->findOneBy(['nom' => $data['categorie']]);
            if (!$categorie) {
                throw new \Exception('Catégorie non trouvée : ' . $data['categorie']);
            }

            $imageName = $slugger->slug($data['nom']) . '.' . pathinfo($data['image'], PATHINFO_EXTENSION);
            copy($data['image'], 'public/uploads/' . $imageName);
            $produit->setImage($imageName);

            $produit->setCategorie($categorie);

            // Ajouter le produit à la catégorie correspondante
            $productsOfCategory[$data['categorie']][] = $produit;

            $manager->persist($produit);
        }

// Maintenant, associez les produits à leurs catégories respectives
        foreach ($productsOfCategory as $categoryName => $products) {
            $category = $manager->getRepository(Categorie::class)->findOneBy(['nom' => $categoryName]);
            foreach ($products as $product) {
                $category->addProduit($product);
            }
        }



        $manager->flush();
    }

}
