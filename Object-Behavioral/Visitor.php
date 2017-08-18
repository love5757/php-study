<?php
/*
 * @category Design Pattern Tutorial
 * @package Visitor Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

abstract class Content
{
    public $title;
    abstract public function save();
}
class Book extends Content
{
    public $author;
    public $isbn;
    public $chapters = array();
    public function addItem($chapter)
    {
        $this->chapters[] = $chapter;
    }
    public function save()
    {
        //..
    }
    public function accept(ContentVisitor $visitor)
    {
        $visitor->visit($this);
        array_map(function($element) use ($visitor) {
            $element->accept($visitor);
        }, $this->chapters);
    }
}
class Chapter extends Content
{
    public $articles = array();
    public function addItem($article)
    {
        $this->articles[] = $article;
    }
    public function save()
    {
        //..
    }
    public function accept(ContentVisitor $visitor)
    {
        $visitor->visit($this);
        array_map(function($element) use ($visitor) {
            $element->accept($visitor);
        }, $this->articles);
    }
}
class Artile extends Content
{
    public $text = "...";
    public function save()
    {
        //..
    }
    public function accept(ContentVisitor $visitor)
    {
        $visitor->visit($this);
    }
}

interface ContentVisitor
{
    public function visit(Content $content);
}

class Reporter implements ContentVisitor
{
    public function visit(Content $content)
    {
        echo "\nObject: ", get_class($content), " \n";
        foreach ($content as $property => $value) {
            echo $property . ": ", $value, " \n";
        }
    }
}


/**
 * Usage
 */

$book1 = new Book();
$book1->title = "Clean Code A Handbook of Agile Software Craftsmanship";
$book1->author = "Robert C. Martin";
$book1->isbn = "0132350882";
$chapter1 = new Chapter();
$chapter1->title = "Chapter 17: Smells and Heuristics";
$article1 = new Artile();
$article1->title = "C1: Inappropriate Information";
$article2 = new Artile();
$article2->title = "C2: Obsolete Comment";
$article3 = new Artile();
$article3->title = "C3: Redundant Comment";
$chapter1->addItem($article1);
$chapter1->addItem($article2);
$chapter1->addItem($article3);
$book1->addItem($chapter1);

$book1->accept(new Reporter());

// Output:
// Object: Book
// author: Robert C. Martin
// isbn: 0132350882
// chapters: Array
// title: Clean Code A Handbook of Agile Software Craftsmanship
//
// Object: Chapter
// articles: Array
// title: Chapter 17: Smells and Heuristics
//
// Object: Artile
// text: ...
// title: C1: Inappropriate Information
//
// Object: Artile
// text: ...
// title: C2: Obsolete Comment
//
// Object: Artile
// text: ...
// title: C3: Redundant Comment
