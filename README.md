# Projet fin php

### Sujet : Développer le modèle avec Doctrine : relations OneToMany, ManyToMany, développer la couche de Repository

## Description du projet

- Site e-commerce
- Avec gestion simple des templates via Twig
- Développer la couche de Repository

  Suggestions pour aller plus loin :
- Héritage Doctrine sur l'entité User : Entité Client (n° client, premium ou pas (booléen)) + Entité Employe (n° employe, date d'embauche)
- Event Listeners / Subscribers : automatiser la création d'un slug dans un produit, lors de son insertion (event prePersist) ou bien mise à jour (event preUpdate)

### Modelisation BDD

Produits // Products

| id  | name     | #category_id | image | ref | price | brand | slug | active |
| --- |:--------:| ------------:| ----- | --- |:-----:| ----- | ---- | ------ |

(On ne gère pas la marque d'un produit comme une entité entière mais comme une simple chaine de caractères). En effet le même code que pour les catégories serait utilisé.

Utilisateurs // Users

| id  | name    | username | password | email |
| --- |:-------:| -------- | -------- | ----- |

Clients // Client

| #user_id | client_num | is_premium |
| -------- | ---------- | ---------- |

> Les entités Client et Employé seront des héritages gérés avec Doctrine : [Single Table Inheritance](https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/inheritance-mapping.html#single-table-inheritance)

Employés // Employees

| #user_id  | employee_num | salary |
| -------- | ------------ | ------ |

Catégories // Categories

| id  | name    | slug |
| --- |:-------:| ----:|

Attributs // Attributes

| id  | name    |
| --- |:-------:|

Valeurs // AttributeValues

| id  | value  | #attribute_id |
| --- |:------:| ------------- |

Produits_Valeurs // AttributeValues_Products

| #product_id | #attributevalue_id |
| ----------- | ------------------ |
> Table intermédiaire qui lie les valeurs d'attributs et les produits

Commandes // Orders

| id  | #user_id | order_date |
| --- | -------- | ---- |

Commandes_Produits // Order_Products
> Permet de stocker les informations de prix et de quantité d'une commande sur le long terme, même si les prix changent.

| id  | #order_id | #product_id | qty | price |
| --- | --------- | ----------- | --- | ----- |


---

Étape 1
------
### Créer les entités

On crée les entités décrites plus haut grâce à Doctrine (à la main parce que si on pouvait utiliser le makerbundle ce serait trop facile)
Pour 'Produits', 'Catégories', 'Attributs', 'Valeurs', 'Commandes' et 'Commandes_Produits', il suffit de créer leurs classes respectives avec les bonnes annotations DocBlock.

On renseigne les metadata de chaque champ avec @ORM\Column(type="...", nullable=true,...).

Pour travailler avec les clés étrangères, on utilise le mapping associatif de Doctrine.
Si un produit a une catégorie, on utilisera une relation OneToOne et on écrira :

````php
class Product
{
    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;
}
````
> Référence Mapping Associatif de Doctrine : [Association Mapping] (https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/association-mapping.html)

On crée les setters et getters

````php
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
````

Et on réitère l'opération pour chaque association :
#### Entity/Order.php
-> $client | OneToOne
-> $products | ManyToMany

-> setClient() & getClient()
-> addProduct() & removeProduct()

#### Entity/Product.php
-> $category | ManyToOne
-> $values (fait référence aux valeurs d'attributs) | ManyToMany

-> getCategory() & setCategory()
-> getValues() & addValue() & removeValue()

#### Entity/User.php
-> $orders | OneToMany

-> getOrders() & addOrder() & removeOrder()

#### Entity/Category.php
-> $products | OneToMany

-> getProducts() & addProduct() & removeProduct()

#### Entity/Attribute.php
-> $attributeValues | OneToMany

-> getAttributeValues() & addAttributeValue() & removeAttributeValue()

#### Entity/AttributeValue.php
-> $attribute | ManyToOne
-> $products | ManyToMany

-> getAttribute() & setAttribute()
-> getProducts() & addProduct() & removeProduct()

Pour certaines entités, il est nécessaire d'initialiser des ArrayCollections dans le constructeur de l'entité.


### Cas particulier d'entités : l'héritage

Pour nos différents types d'utilisateurs (clients et employés), nous avons besoin d'utiliser un héritage.

Doctrine offre deux types d'héritage :
- single table inheritance
- class table inheritance

Cet article [About Doctrine inheritance](https://romaricdrigon.github.io/2019/06/11/doctrine-inheritance) explique très bien la différence entre les deux types d'héritage et qui plus est pourquoi il est préférable de n'utiliser aucun des deux.
Je vous fait un résumé : les deux méthodes posent des problèmes de performance. L'un parce que bcp de cellules vides, et l'autre parce que les requetes SQL deviennent très compliquées.
Comme notre application ne battra pas des records de fréquentation, nous allons choisir l'héritance de classe qui semble rester une meilleure option.

On renseigne donc plusieurs informations DocBlock :

````php
/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"employee" = "Employee", "client" = "Client"})
 * @ORM\Table(name="users")
 */
class User
{

}
````

- @InheritanceType définis le type d'héritage, ici 'class'

- @DiscriminatorColumn définis le nom de la colonne qui différenciera les utilisateurs. Ici on choisi 'type' sous ententu 'type d'utilisateur'.

- @DiscriminatorMap fais correspondre le discriminant à l'entité. Nous aurons donc deux types: employees et clients.

On crée ensuite les deux Entités qui extends de User

````php
/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client extends User
{
    ///
}
/**
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employee extends User
{
    ///
}
````

On peut ensuite créer les propriétés et méthodes :

````php

class Client extends User
{
  /**
   * @ORM\Column(type="integer", options={"default":0})
   */
  private $client_num;

  /**
   * @ORM\Column(type="boolean")
   */
  private $is_premium;
  
  public function getClientNum(): ?int
  public function setClientNum(int $client_num): self
  public function getIsPremium(): ?bool
  public function setIsPremium(bool $is_premium): self
}


class Employee extends User
{
    /**
     * @ORM\Column(type="integer")
     */
    private $employee_num;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $salary;

    public function getEmployeeNum(): ?int
    public function setEmployeeNum(int $employee_num): self
    public function getSalary(): ?float
    public function setSalary(?float $salary): self

}
````

Dès lors, dans notre IndexController, on pourra changer ceci :

````php
$user = new User();

    $user->setName("Bob")
      ->setFirstName("John")
      ->setUsername("Bobby")
      ->setPassword("randompass")
      ->setEmail("bob@bob.com")
      ->setBirthDate(new DateTime('1981-02-16'));
````

Par cela :

````php
    $user = new Client();

    $user->setName("Bob")
      ->setFirstName("John")
      ->setUsername("Bobby")
      ->setPassword("randompass")
      ->setEmail("bob@bob.com")
      ->setBirthDate(new DateTime('1981-02-16'))
      ->setClientNum(1223232321)
      ->setIsPremium(true);
````

On pourrait même imaginer avoir un formulaire avec un select qui récupère le type d'utilisateur, l'envoie en POST comme ceci :

````html

<form method="post">

<select name="typeutilisateur">
  <option value="Client" selected>Client</option>
  <option value="Employee">Employee</option>
</select>

  // d'autres champs dynamiques en fonction de l'option choisie
  
</form>
````

`````php

public function index(EntityManager $em)
{
  $type = $_POST['type'];
  
  // on vérifie que la valeur entrée par le champs est bien conforme puisque l'utilisateur peut modifier la valeur envoyée. (le code n'est pas écrit)
  // puis on instancie la classe $type() qui est soit Client soit Employee
  
  $user = new $type(); 

  // general user info
  $user->setName("Bob")
    ->setFirstName("John")
    ->setUsername("Bobby")
    ->setPassword("randompass")
    ->setEmail("bob@bob.com")
    ->setBirthDate(new DateTime('1981-02-16'));
    
    // adds value based on user type
    if ($type === "Client") {
    $user->setClientNum(1223232321)
         ->setIsPremium(true);
    } elseif ($type === "Employee") {
    $user->setEmployeeNum(1237893468)
         ->setSalary(1450);
    }

    // cree la requete
    $em->persist($user);
    // execute
    $em->flush();
  }
    
`````

On crée un controller pour seeder la DB lorsque l'uri "/generate" est visité plutôt que ce soit fait dans l'index :
donc @BeatGrinder quand vous voudrez tester, il faudra d'abord générer la db.

````php
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
````
On aurait voulu faire un formulaire comme montré plus haut mais pas le temps...

L'idéal serait de créer un controller CRUD pour chaque Entité mais pas le temps...
Donc on met tout en vrac dans le indexcontroller et on espère que le prof nous donnera quand même une bonne note :))


Quand on visite /list, on récupère toutes les produits grace au Repository d'EntityManager de Twig et on les envoie dans le template product/product-list.html.twig en passant la variable en paramètre.

#### Twig templating

````php
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
````
Dans product/product-list.html.twig

On pourrait boucler sur 
````html
{% for product in products %}
    <a href="{% 'product/' . product.id %}">Lien du produit</a>
{% endfor %}
````
Puis dans un ProductController

````php
#[Route(path: "/product/{id}")]
public function productList(EntityManager $em)
{

    // recuperer l'ID dans une var $id
    
$product = $em->getRepository(Product::class)->find($id);

if (!$product) {
    // redirect to 404
}

echo $this->twig->render('product/product.html.twig', $product);
}
````
TADAAAAAAA!

## Conclusion du projet : 

On a développé un système assez complexe d'Entités avec Doctrine en gérant les relations entre les entités et un type d'héritage pour notre entité User.
On a également ajouté des fonctions dans IndexController qui ne sont pas propres du tout et qui sont à moitié finies (on est sincèrement désolé). Mais on a tout de même généré un mini seeder qui s'enclenche à chaque fois que l'on visite l'url 'generate'. On aurait pu intégrer une condition qui vérifie si la BDD a déjà eté seedé avant de performer le seed. On aurait également souhaité intégrer la [librairie Faker](https://github.com/fzaninotto/Faker) pour seeder avec des données presque réelles. 
On regrette de ne pas avoir écrit toutes les Entités avec le Design Pattern Fluent pour les setters. Je l'ai fait sur quelques uns mais pas tous (et pas eu le temps de corriger ça).
On aurait aimé développer la couche Repository pour récupérer certaines données uniquement ex: ..->isActive()->findAll() pour récupérer uniquement les produits qui sont actifs.
Dans notre IndexController, on a géré le listing des produits. Il manque la mise en forme mais j'ai mis un bout de code qui montre comment on aurait pu faire [ici](#twig-templating) en envoyant les données avec la fonction render() de twig. En récupérant l'id (ou slug), on peut créer un lien pour chaque produit qui redirige vers la page de chaque produit, elle même controlée par une autre fonction dans IndexController. On pourrait également créer une fonction helper url() qui prendrait en paramètre le type ex: url(produit) et qui renverrait 'product/' plutôt que d'écrire directement 'product/' dans le <a></a>. 
On regrette d'avoir trop complexifié notre base de données ce qui nous a fait perdre du temps en nous faisant répéter les choses plusieurs fois.

Globalement, nous sommes satisfait du résultat, avec une Couche entité qui fonctionne très bien et qui peut enregistrer des données dans la DB et les récupérer avec le repository de base de Doctrine. 

