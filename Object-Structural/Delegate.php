<?php
/*
 * @category Design Pattern Tutorial
 * @package Delegate Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

/**
 * Delegate
 */

class MockMailer
{
    public function send($to, $subject, $body)
    {
        printf ("Message to: %s, subject: %s, body: %s\n\n", $to, $subject, $body);
    }
}

/**
 * Delegator
 */
class Mailer
{
    public function send($to, $subject, $body)
    {
        $mailer = new MockMailer();
        $mailer->send($to, $subject, $body);
    }
}

/**
 * Usage
 */

$mailer = new Mailer();
$mailer->send("email@address.com", "a subject", "a body");

// Output:
// -> Message to: email@address.com, subject: a subject, body: a body
