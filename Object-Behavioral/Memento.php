<?php
/*
 * @category Design Pattern Tutorial
 * @package Memento Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */
/**
 * Memento
 */
class Memento
{
    private $_originator;
    public function  __construct(&$originator)
    {
        $this->_originator = &$originator;
    }
    public function save()
    {
        $this->_state = $this->_originator->getState();
    }
    public function restore()
    {
        $this->_originator->setState($this->_state);
    }
}
/**
 * Originator
 */
interface Restorable
{
    public function getState();
    public function setState($state);
}
class RestorableIterator implements Iterator, Restorable
{
    private $_data;
    private $_cursor = 0;
    private $_valid = false;

    public function  __construct(array $data = array())
    {
        $this->_data = $data;
        $this->_valid = (bool)count($this->_data);
    }
    public function current()
    {
        return $this->_data[$this->_cursor];
    }
    public function key()
    {
        return $this->_cursor;
    }
    public function getState()
    {
        return $this->_cursor;
    }
    public function setState($state)
    {
        $this->_cursor = $state;
    }
    public function next()
    {
        $max = count($this->_data) - 1;
        $this->_valid = $this->_cursor < $max ? (bool)(++$this->_cursor) : false;
    }
    public function rewind()
    {
        $this->_valid = (bool)count($this->_data);
        $this->_cursor = 0;
    }
    public function valid()
    {
        return $this->_valid;
    }
}
/**
 * Caretaker
 */
$it = new RestorableIterator(array('a', 'b', 'c', 'd'));
$itMemento = new Memento($it);
$it->next();
$itMemento->save();
$it->next();
$it->next();
echo "Current value ", $it->current(), "\n";
$itMemento->restore();
echo "Current value ", $it->current(), "\n";

// Output:
// Current value d
// Current value b
