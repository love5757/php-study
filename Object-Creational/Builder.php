<?php
/*
 * @category Design Pattern Tutorial
 * @package Builder Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

// File: ./Document/Entity.php
namespace Document;

class Entity
{
    public $author = "George R. R. Martin";
    public $title = "A Song of Ice and Fire";
    public $chapters = array("Chapter 1", "Chapter 2");
}

// Director
// File: ./Document/Reader.php
namespace Document;

class Reader
{
    public function getDocument(\Document\Entity $document
        , \Document\AbstractConvertor $convertor)
    {
        $convertor->setAuthor($document->author);
        $convertor->setTitle($document->title);
        foreach ($document->chapters as $chapter) {
            $convertor->setText($chapter);
        }
        return $convertor->getDocument();
    }

}

// Abstract builder
// File: ./Document/AbstractConvertor.php
namespace Document;

abstract class AbstractConvertor
{
    protected $_buffer;
    abstract public function setAuthor($author);
    abstract public function setTitle($title);
    abstract public function setText($text);
    public function getDocument()
    {
        return$this->_buffer;
    }
}
// Concrete builder
// File: ./Document/Convertor/Pdf.php
namespace Document\Convertor;

class Pdf extends \Document\AbstractConvertor
{
    public function setAuthor($author)
    {
        $this->_buffer .= "PDF: Author info {$author}\n";
    }
    public function setTitle($title)
    {
        $this->_buffer .= "PDF: Title info {$title}\n";
    }
    public function setText($text)
    {
        $this->_buffer .= "PDF: {$text}\n";
    }

}
// Concrete builder
// File: ./Document/Convertor/Epub.php
namespace Document\Convertor;

class Epub extends \Document\AbstractConvertor
{
    public function setAuthor($author)
    {
        $this->_buffer .= "ePub: Author info {$author}\n";
    }
    public function setTitle($title)
    {
        $this->_buffer .= "ePub: Title info {$title}\n";
    }
    public function setText($text)
    {
        $this->_buffer .= "ePub: {$text}\n";
    }
}


/**
 * Usage
 */
include './Document/Entity.php';
include './Document/Reader.php';
include './Document/AbstractConvertor.php';
include './Document/Convertor/Pdf.php';
include './Document/Convertor/Epub.php';

$doc = new \Document\Entity();
$convertor = new \Document\Convertor\Pdf();
$reader = new \Document\Reader();
print $reader->getDocument($doc, $convertor);

// Output
// PDF: Author info George R. R. Martin
// PDF: Title info The Song of Ice and Fire
// PDF: Chapter 1
// PDF: Chapter 2
