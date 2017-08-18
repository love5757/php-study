<?php
/*
 * @category Design Pattern Tutorial
 * @package Interpreter Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */
/**
 * Context
 */
class View
{
    private $_vars;

    public function assign($var, $val)
    {
        $this->_vars[$var] = $val;
    }
    public function getVars()
    {
        return $this->_vars;
    }

}
/**
 * Expression
 */
interface Expression_Interface
{
    public function interpret($expression, View $context);
}
class Web_Template_Interpreter implements Expression_Interface
{
    public function interpret($expression, View $context)
    {
        $assigments = self::_getAssigmentsMap($context->getVars());
        $output = strtr($expression, $assigments);
        if (($errorMsg = self::_isValidXml($output)) === true) {
            return $output;
        } else {
            return "[Error:]\n" . $errorMsg;
        }
    }
    private static function _getAssigmentsMap($vars)
    {
        $keys = array_map(function ($key) { return "{{$key}}"; }, array_keys($vars));
        return array_combine($keys, array_values($vars));
    }
    private static function _isValidXml($xml)
    {
        libxml_use_internal_errors(true);
        $sxe = simplexml_load_string("<?xml version='1.0' standalone='yes'?>{$xml}");
        if (!$sxe) {
            $msgs = array_map(function ($li) { return $li->message; }, libxml_get_errors());
            return implode("", $msgs);
        }
        return true;
    }
}

/**
 * Usage
 */

$view = new View();
$view->assign('title', 'Article Title');
$view->assign('text', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit');

$invalidTpl = <<<'EOD'
<article>
    <header>
        ...
EOD;
$validTpl = <<<'EOD'
<article>
    <header>
      <h2>{title}</h2>
    </header>
    <section>
      {text}
    </section>
</article>
EOD;

$interpreter = new Web_Template_Interpreter();

echo "Invalid template:\n", $interpreter->interpret($invalidTpl, $view), "\n---------------\n\n";
echo "Valid template:\n", $interpreter->interpret($validTpl, $view), "\n---------------\n\n";

// Output:
// Invalid template:
// [Error:]
// Premature end of data in tag header line 2
// Premature end of data in tag article line 1
//
// ---------------
//
// Valid template:
// <article>
//     <header>
//       <h2>Article Title</h2>
//     </header>
//     <section>
//       Lorem ipsum dolor sit amet, consectetur adipisicing elit
//     </section>
// </article>
// ---------------
