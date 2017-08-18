<?php
/*
 * @category Design Pattern Tutorial
 * @package Template Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

class Mock
{
    public static function mail($to, $subject, $body, $headers)
    {
        printf ("Mail to %s\nHeaders: %s\nSubject: %s\nBody: %s\n\n", $to, $headers, $subject, $body);
    }
}
/**
 * Template class
 */
abstract class FeedbackFormTemplate
{
    const FEEDBACK_RECIPIENT_EMAIL = 'support@site.com';
    protected $_subject;
    protected $_fromName;
    protected $_fromEmail;

    public function send()
    {
        $body = $this->_getBody() . $this->_getAttachment();
        Mock::mail(self::FEEDBACK_RECIPIENT_EMAIL, $this->_subject, $body, $this->_getHeaders());
    }
    private function _getHeaders()
    {
        return sprintf("From: %s <%s>\r\n", $this->_fromName, $this->_fromEmail);
    }
    private function _getAttachment()
    {
        return strtr('<h2>Session Info</h1><d2>'
            . '<dt>User Id:</dt><dd>{userId}</dd>'
            . '<dt>User Agent:</dt><dd>{userAgent}</dd>'
            . '<dt>URI:</dt><dd>{uri}</dd>'
            . '</dl>', array(
                '{userId}' => (isset ($_SESSION["userId"]) ? $_SESSION["userId"] : "undefined"),
                '{userAgent}' => $_SERVER["HTTP_USER_AGENT"],
                '{uri}' => $_SERVER["REQUEST_URI"],
            ));
    }
    abstract protected function _getBody();
}
/**
 * Concrete implementations
 */
final class SupportFeedbackForm extends FeedbackFormTemplate
{
    protected $_subject = 'Support Request';
    private $_data;

    public function  __construct(SupportFeedbackFormEntity $data)
    {
        $this->_data = $data;
        $this->_fromName = $data->name;
        $this->_fromEmail = $data->email;
    }
    protected function _getBody()
    {
        return strtr('<h1>{title}</h1><dl>'
            . '<dt>Name:</dt><dd>{name}</dd>'
            . '<dt>Email:</dt><dd>{email}</dd>'
            . '<dt>Category:</dt><dd>{city}</dd>'
            . '<dt>Message:</dt><dd>{message}</dd>'
            . '</dl>', array(
                '{title}' => $this->_subject,
                '{name}' => $this->_data->name,
                '{email}' => $this->_data->email,
                '{category}' => $this->_data->category,
                '{message}' => $this->_data->message,
            ));
    }
}


final class ContactFeedbackForm extends FeedbackFormTemplate
{
    protected $_subject = 'Contact Form Request';
    private $_data;

    public function  __construct(ContactFeedbackFormEntity $data)
    {
        $this->_data = $data;
        $this->_fromName = $data->name;
        $this->_fromEmail = $data->email;
    }
    protected function _getBody()
    {
        return srttr('<h1>{title}</h1><dl>'
            . '<dt>Name:</dt><dd>{name}</dd>'
            . '<dt>Email:</dt><dd>{email}</dd>'
            . '<dt>City:</dt><dd>{city}</dd>'
            . '<dt>Address:</dt><dd>{address}</dd>'
            . '<dt>Message:</dt><dd>{message}</dd>'
            . '</dl>', array(
                '{title}' => $this->_subject,
                '{name}' => $this->_data->name,
                '{email}' => $this->_data->email,
                '{city}' => $this->_data->city,
                '{address}' => $this->_data->address,
                '{message}' => $this->_data->message,
            ));
    }

}
/**
 * Data Transfer Objects
 */
abstract class FeedbackFormEntity
{
    public $name;
    public $email;
    public $message;
}
class ContactFeedbackFormEntity extends FeedbackFormEntity
{
    public $city;
    public $address;
}
class SupportFeedbackFormEntity extends FeedbackFormEntity
{
    public $category;
}

/**
 * Usage
 */
$data = new SupportFeedbackFormEntity();
$data->name = "John Snow";
$data->email = "John Snow";
$data->category = "John Snow";
$data->message = "John Snow";
$form = new SupportFeedbackForm($data);
$form->send();

// Output
// Mail to support@site.com
// Headers: From: John Snow <John Snow>
// Subject: Support Request
// Body: <h1>Support Request</h1>..
