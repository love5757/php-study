<?php
/*
 * @category Design Pattern Tutorial
 * @package State Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

/**
 * The state interface and two implementations
 */
interface State_Logging_Interface
{
    public function log(Lib_Logger $context);
}
class State_Logging_File implements State_Logging_Interface
{
    public function log(Lib_Logger $context)
    {
        echo $context->getMessage(), ' logged into file', "\n";
    }
}
class State_Logging_Db implements State_Logging_Interface
{
    private static function _isDbAvailable()
    {
        static $counter = false;
        $counter = !$counter;
        return $counter;
    }
    public function log(Lib_Logger $context)
    {
        if ($this->_isDbAvailable()) {
            echo $context->getMessage(), ' logged into DB', "\n";
        } else {
            $context->setState(new State_Logging_File());
            $context->log($context->getMessage());
            $context->log('DB connection is not available');
        }
    }
}
/**
 * Context class
 */
class Lib_Logger
{
    private $_state;
    private $_message;
    public function  __construct()
    {
        // Default state
        $this->_state = new State_Logging_Db();
    }
    public function setState(State_Logging_Interface $state)
    {
        $this->_state = $state;
    }
    public function getMessage()
    {
        return $this->_message;
    }
    public function log($message )
    {
        $this->_message = $message;
        $this->_state->log($this, $message);
    }
}

/**
 * Usage
 */
$logger = new Lib_Logger();
$logger->log('Message 1');
$logger->log('Message 2');
$logger->log('Message 3');

// Output:
// Message 1 logged into DB
// Message 2 logged into file
// DB connection is not available logged into file
// Message 3 logged into file
