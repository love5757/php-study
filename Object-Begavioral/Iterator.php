<?php
/*
 * @category Design Pattern Tutorial
 * @package Iterator Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */
class Dao_News
{
    public function fetchAll()
    {
        $raws = array(
            (object)array("id" => 1, "title" => "Title 1"),
            (object)array("id" => 2, "title" => "Title 2"),
            (object)array("id" => 3, "title" => "Title 3"),
        );
        return new ArrayIterator($raws);
    }
}
/**
 * Usage
 */
$dao = new Dao_News();
$newIt = $dao->fetchAll();
for ($newIt->rewind(); $newIt->valid(); $newIt->next()) {
    echo $newIt->current()->title, "\n";
}

// Output:
// Title 1
// Title 2
// Title 3
