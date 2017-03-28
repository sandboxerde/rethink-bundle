TBoileau/RethinkBundle
======================

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require tboileau/rethink-bundle "^0.1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new TBoileau\RethinkBundle\TBoileauRethinkBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Configuration
---------------------

After you enable the bundle, you have to configure it in `app/config/config.yml` file of your project :

```yml
t_boileau_rethink:
    connection:
        hostname: "localhost"
        port: 28015
        dbname: "test"
```

Step 4: Usage
-------------

```php
<?php

namespace AppBundle\Document;

use TBoileau\RethinkBundle\ODM\Metadata as Rethink;

/**
 * @Rethink\Table(name="foo")
 */
class Foo
{
    /**
     * @Rethink\Id
     */
    protected $id;

    /**
     * @Rethink\Column(name="name",type="string")
     */
    protected $name;

    /**
     * @Rethink\OneToMany(target="AppBundle\Document\Bar",mappedBy="foo")
     */
    protected $bars;

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setBars($bars)
    {
        $this->bars=$bars;
    }

    public function getBars()
    {
        return $this->bars;
    }
}
```

```php
<?php

namespace AppBundle\Document;

use TBoileau\RethinkBundle\ODM\Metadata as Rethink;

/**
 * @Rethink\Table(name="bar")
 */
class Bar
{
    /**
     * @Rethink\Id
     */
    protected $id;

    /**
     * @Rethink\Column(name="name",type="string")
     */
    protected $name;

    /**
     * @Rethink\ManyToOne(name="foo_id",target="AppBundle\Document\Foo",inversedBy="bars")
     */
    protected $foo;

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFoo($foo)
    {
        $this->foo=$foo;
    }

    public function getFoo()
    {
        return $this->foo;
    }
}
```

```php
<?php

$em = $this->get("t_boileau_rethink.entity_manager");

$foo = new Foo();
$foo->setName("foo name");
$em->insert($foo);

$bar = new Bar();
$bar->setName("bar name");
$bar->setFoo($foo);
$em->insert($bar);

$repository = $em->getRepository("AppBundle\Document\Foo");

$foos = $repository->query()->getResults();

$foosFiltered = $repository->query(function($table){
    return $table->filter(["name"=>"bar"]);
})->getResults();

$lastFoo = $repository->query(function($table) use ($foo){
    return $table->get($foo->getId());
})->getSingleResult();

$lastFoo->getBars(); //lazy loading
```
