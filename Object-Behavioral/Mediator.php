<?php
/*
 * @category Design Pattern Tutorial
 * @package Mediator Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

class Model_JobApplication
{
    const STATUS_HIRED = 7;
    private $_mediator;

    public function  __construct(Model_Mediator_Interface $mediator = null)
    {
        $this->_mediator = $mediator;
    }

    public function updateStatus($id, $newStatusId)
    {
        if (!is_null($this->_mediator)) {
            $this->_mediator->update($this, array(
                "statusId" => $newStatusId,
                "appId" => $id,
                "posId" => 1 // TILT
                ));
        }
    }
}
class Model_JobPosition
{
    public function updateAsStatusChanges($data)
    {
        if ($data['statusId'] === Model_JobApplication::STATUS_HIRED) {
            echo 'Number of open vacancies decremented', "\n";
        }
    }
}
class Model_JobPositionObserver
{
    // Observers and job positions on which they are subscibed for status updates
    private $_subscription = array(
        'department1@company.com' => array(1 => array(Model_JobApplication::STATUS_HIRED)),
        'department2@company.com' => array(1 => array(Model_JobApplication::STATUS_HIRED))
    );

    public function updateAsStatusChanges($data)
    {
        foreach ($this->_subscription as $mail => $positions) {
            if (isset ($positions[$data['posId']])
                && in_array($data['statusId'], $positions[$data['posId']])) {
                echo $mail . ' notified', "\n";
            }
        }
    }
}
interface Model_Mediator_Interface
{
    public function update($origObject, $data);
}
class Model_Mediator_JobApplicationStatus implements Model_Mediator_Interface
{
    private $_subscribers = array('JobPosition', 'JobPositionObserver');
    public function update($origObject, $data)
    {
        foreach ($this->_subscribers as $subscriber) {
            $subscriberClass = 'Model_' . $subscriber;
            if (!($origObject instanceof $subscriberClass)) {
                $object = new $subscriberClass();
                $object->updateAsStatusChanges($data);
            }
        }
    }
}

/**
 * Usage
 */

$model = new Model_JobApplication(new Model_Mediator_JobApplicationStatus());
$id = 1;
$newStatusId = Model_JobApplication::STATUS_HIRED;
$model->updateStatus($id, $newStatusId);

// Output:
// Number of open vacancies decremented
// department1@company.com notified
// department2@company.com notified
