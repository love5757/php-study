<?php
/*
 * @category Design Pattern Tutorial
 * @package Command Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

/**
 * The Receiver classes
 */
abstract class Model_Abstract
{
}
class Model_Search extends Model_Abstract
{
    public function index()
    {
        echo "Site re-indexed\n";
    }
}
class Model_Session extends Model_Abstract
{
    public function cleanup()
    {
        echo "Session cleaned up\n";
    }
}
class Lib_JobQueue extends SplQueue
{
}
/**
 * The Command classes
 */
abstract class Job_Abstract
{
    protected $_receiver;
    public function  __construct(Model_Abstract $receiver) {
        $this->_receiver = $receiver;
    }
    abstract public function execute();
}
class Job_IndexSearch extends Job_Abstract
{
    public function execute()
    {
         $this->_receiver->index();
    }
}
class Job_CleanupSessions extends Job_Abstract
{
    public function execute()
    {
        $this->_receiver->cleanup();
    }
}

/**
 * Usage
 */
$queue = new SplQueue(); // Job Queue
$searchReceiver = new Model_Search();
$sessionReceiver = new Model_Session();
$queue->enqueue(new Job_IndexSearch($searchReceiver));
$queue->enqueue(new Job_CleanupSessions($sessionReceiver));

foreach ($queue as $job) {
    $job->execute();
}

// Output:
// Site re-indexed
// Session cleaned up
