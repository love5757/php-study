<?php
/*
 * @category Design Pattern Tutorial
 * @package AbstractFactory Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

// Abstract Factory
// File: ./Widget/Factory/AbstractFactory.php
namespace Widget\Factory;

abstract class AbstractFactory
{
    abstract public function makeDialog();
    abstract public function makeButton();
}

// Concrete Factory
// File: ./Widget/Factory/Desktop.php
namespace Widget\Factory;

class Desktop extends AbstractFactory
{
    public function makeDialog()
    {
        return new \Widget\Dialog\Desktop();
    }
    public function makeButton()
    {
        return new \Widget\Button\Desktop();
    }
}

// Concrete Factory
// File: ./Widget/Factory/Mobile.php
namespace Widget\Factory;

class Mobile extends AbstractFactory
{
    public function makeDialog()
    {
        return new \Widget\Dialog\Mobile();
    }
    public function makeButton()
    {
        return new \Widget\Button\Mobile();
    }
}

// Abstract Product
// File: ./Widget/Dialog/iDialog.php
namespace Widget\Dialog;

interface iDialog
{
    public function render();
}

// Concrete Product
// File: ./Widget/Dialog/Desktop.php
namespace Widget\Dialog;

class Desktop implements \Widget\Dialog\iDialog
{
    public function render()
    {
        print "jQueryUI based dialog\n";
    }
}

// Concrete Product
// File: ./Widget/Dialog/Mobile.php
namespace Widget\Dialog;

class Mobile implements \Widget\Dialog\iDialog
{
    public function render()
    {
        print "jQueryMobile based dialog\n";
    }
}

// Abstract Product
// File: ./Widget/Button/iButton.php
namespace Widget\Button;

interface iButton
{
    public function render();
}

// Concrete Product
// File: ./Widget/Button/Desktop.php
namespace Widget\Button;

class Desktop implements \Widget\Button\iButton
{
    public function render()
    {
        print "jQueryUI based button\n";
    }
}

// Concrete Product
// File: ./Widget/Button/Mobile.php
namespace Widget\Button;

class Mobile implements \Widget\Button\iButton
{
    public function render()
    {
        print "jQueryMobile based button\n";
    }
}


// File: ./Config.php
class Config
{
    public $platform;
}

// Client
// File: ./Application.php
class Application
{
    private $_config;
    public function __construct($config)
    {
        $this->_config = $config;
    }
    private function _createPlaformSpecificFactory()
    {
        $className = "\\Widget\\Factory\\" . ucfirst($this->_config->platform);
        return new $className;
    }
    public function build()
    {
        $factory = $this->_createPlaformSpecificFactory();
        $dialog = $factory->makeDialog();
        $dialog->render();
        $button = $factory->makeButton();
        $button->render();
    }
}

/**
 * Usage
 */
include "./Widget/Factory/AbstractFactory.php";
include "./Widget/Factory/Desktop.php";
include "./Widget/Factory/Mobile.php";
include "./Widget/Dialog/iDialog.php";
include "./Widget/Dialog/Desktop.php";
include "./Widget/Dialog/Mobile.php";
include "./Widget/Button/iButton.php";
include "./Widget/Button/Desktop.php";
include "./Widget/Button/Mobile.php";
include "./Config.php";
include "./Application.php";

$config = new Config();
$config->platform = "mobile";
$app = new Application($config);
$app->build();

// Output:
// jQueryMobile based dialog
// jQueryMobile based button
