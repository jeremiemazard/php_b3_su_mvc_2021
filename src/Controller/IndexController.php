<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Product;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractController
{
  #[Route(path: "/generate")]
  public function index(EntityManager $em)
  {
      $cat = new Category();
      $cat2 = new Category();

    $cat->setName('categorie1')->setSlug('c1');
    $cat2->setName('categorie2')->setSlug('c2');

    $em->persist($cat);
    $em->persist($cat2);
    $em->flush();

    for ($i=0; $i<10; $i++) {
        $prod = new Product();
        $prod->setName('produit')->setCategory($cat)->setSlug('prod')->setActive(1)->setBrand('apple')->setCreatedAt(new DateTime('1981-02-16'))->setPrice(200);

        $em->persist($prod);
        $em->flush();
    }
  }

  #[Route(path: "/list")]
  public function productList(EntityManager $em)
  {
    $products = $em->getRepository(Product::class)->findAll();

    dump($products);

    if (!$products) {
      echo 'Pas de produits';
    }

    echo $this->twig->render('product/product-list.html.twig', $products);
  }

  #[Route(path: "/")]
  public function createUser(EntityManager $em) {

      $user = new Client();

      $user->setName("Bob")
          ->setFirstName("John")
          ->setUsername("Bobby")
          ->setPassword("randompass")
          ->setEmail("bob@bob.com")
          ->setBirthDate(new DateTime('1981-02-16'))
          ->setClientNum(1223232321)
          ->setIsPremium(true);

      // On demande au gestionnaire d'entités de persister l'objet
      // Attention, à ce moment-là l'objet n'est pas encore enregistré en BDD
      $em->persist($user);
      $em->flush();

      echo "Utilisateur créé!";
  }

  #[Route(path: "/contact", name: "contact", httpMethod: "POST")]
  public function contact()
  {
    echo $this->twig->render('index/contact.html.twig');
  }
}
