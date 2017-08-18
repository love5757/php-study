<?php
/*
 * @category Design Pattern Tutorial
 * @package Proxy Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */
/**
 * Subject interface
 */
interface Entry_Interface
{
    public function get();
}
/**
 * Subject
 */
class Entry implements Entry_Interface
{
    private $_id;
    public function  __construct($id)
    {
        $this->_id;
    }
    public function get()
    {
        return "Entry #{$this->_id} retrieved";
    }
}
/**
 * Proxy
 */
class Entry_ChacheProxy implements Entry_Interface
{
    private $_id;
    public function  __construct($id)
    {
        $this->_id;
    }
    public function get()
    {
        static $entry = null;
        if ($entry === null) {
            $entry = new Entry($this->_id);
        }
        return $entry->get();
    }
}

/**
 * Usage
 */
$entryId = 1;
$entry = new Entry_ChacheProxy($entryId);
echo $entry->get(), "\n"; // loading necessary
echo $entry->get(), "\n"; // loading unnecessary
