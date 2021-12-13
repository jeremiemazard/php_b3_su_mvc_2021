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
