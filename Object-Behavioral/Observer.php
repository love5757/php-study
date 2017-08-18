<?php

class Plugin_Bookmark implements SplObserver
{
    public function deleteByUserId($userId)
    {
        printf ("Bookmarks of user %d deleted<br/>", $userId);
    }
    public function update(SplSubject $subject)
    {
        $userId = $subject->getId();
        $this->deleteByUserId($userId);
    }
}

class Plugin_Liking implements SplObserver
{
    public function deleteByUserId($userId)
    {
        printf ("Likings of user %d deleted<br/>", $userId);
    }
    public function update(SplSubject $subject)
    {
        $userId = $subject->getId();
        $this->deleteByUserId($userId);
    }
}

abstract class Pluginable_Abstract implements SplSubject
{
    // Array of observers
    private $_observers = array();

    public function attach(SplObserver $observer)
    {

        $hash = spl_object_hash($observer);
        if (!isset ($this->_observers[$hash])) {
            $this->_observers[$hash] = $observer;
        }
        //var_dump($this->_observers);
    }
    public function detach(SplObserver $observer)
    {
        //var_dump(spl_object_hash($observer));
        unset($this->_observers[spl_object_hash($observer)]);
        var_dump($this->_observers);
    }
    /**
     * Implement SplSubject method
     */
    public function notify()
    {
        foreach ($this->_observers as $value) {
            var_dump($this);
            $value->update($this);
        }
    }
}

class Model_Abstract extends Pluginable_Abstract
{
    // Common and abstract properies and methods of Models
}
class Model_User extends Model_Abstract
{
    private $_userId;

    public function setId($userId)
    {
        $this->_userId = $userId;
    }

    public function getId()
    {
        return $this->_userId;
    }

    public function delete($userId)
    {
        $this->setId($userId);
        printf ("User %d deleted<br/>", $userId);
        $this->notify();
    }
}

/**
 * Usage
 */
$userId = 1;
$model = new Model_User();
//observer add
$observer = new Plugin_Bookmark();
$observer1 = new Plugin_Liking();

$model->attach($observer);
$model->attach($observer1);

//$model->detach($observer);
//$model->detach($observer1);

$model->delete($userId);

// Output
// -> User 1 deleted
// -> Bookmarks of user 1 deleted
// -> Likings of user 1 deleted
