<?php
/*
 * @category Design Pattern Tutorial
 * @package Strategy Sample
 * @author Dmitry Sheiko <me@dsheiko.com>
 * @link http://dsheiko.com
 */

class Profile
{
    public $data;
    protected $_strategy;

    public function  __construct(array $data)
    {
        $this->data = $data;
    }
    public function setStrategyContext(RichSnippetStrategyAbstract $strategy)
    {
        $this->_strategy = $strategy;
    }
    public function get() {
        return $this->_strategy->get($this);
    }

}


abstract class RichSnippetStrategyAbstract
{
    protected $_cardTemplate;
    protected $_propertyTpls;

    protected function _getReplacePairs($properties)
    {
        $replPairs = array();
        foreach ($properties as $property => $val) {
            if (is_array ($val)) {
                $val = $this->_process($val);
            }
            $replPairs['{' . $property . '}'] =
                strtr($this->_propertyTpls[$property], array('{value}' => $val));
        }
        return $replPairs;
    }

    protected function _process(array $data)
    {
        if (!isset ($data["template"]) || !isset ($data["properties"])) {
            throw new Exception('Input data structure is not correct');
        }
        return strtr($data["template"], $this->_getReplacePairs($data["properties"]));
    }

    public function get(Profile $context)
    {
        $card = $this->_process($context->data);
        return sprintf($this->_cardTpl, $card);
    }
}

class ProfileAsMicrodataStrategy extends RichSnippetStrategyAbstract
{
    protected $_cardTpl = '<div itemscope itemtype="http://data-vocabulary.org/Person">%s</div>';
    protected $_propertyTpls = array(
        'name' => '<span itemprop="name">{value}</span>',
        'nickname' => '<span itemprop="nickname">{value}</span>',
        'url' => '<a href="{value}" itemprop="url">{value}</a>',
        'address' => '<span itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">{value}</span>',
        'locality' => '<span itemprop="locality">{value}</span>',
        'region' => '<span itemprop="region">{value}</span>',
        'title' => '<span itemprop="title">{value}</span>',
        'org' => '<span itemprop="affiliation">{value}</span>');

}
class ProfileAsMicroformatStrategy extends RichSnippetStrategyAbstract
{
    protected $_cardTpl = '<div class="vcard">%s</div>';
    protected $_propertyTpls = array(
        'name' => '<span class="fn">{value}</span>',
        'nickname' => '<span class="nickname">{value}</span>',
        'url' => '<a href="{value}"class="url">{value}</a>',
        'address' => '<span class="adr">{value}</span>',
        'locality' => '<span class="locality">{value}</span>',
        'region' => '<span class="region">{value}</span>',
        'title' => ' <span class="title">{value}</span>',
        'org' => '<span class="org">{value}</span>');
}
class ProfileAsRdfStrategy extends RichSnippetStrategyAbstract
{
    protected $_cardTpl = '<div xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Person">%s</div>';
    protected $_propertyTpls = array(
        'name' => '<span property="v:name">{value}</span>',
        'nickname' => '<span property="v:nickname">{value}</span>',
        'url' => '<a href="{value}" rel="v:url">{value}</a>',
        'address' => '<span rel="v:address"><span typeof="v:Address">{value}</span></span>',
        'locality' => '<span property="v:locality">{value}</span>',
        'region' => '<span property="v:region">{value}</span>',
        'title' => '<span property="v:title">{value}</span>',
        'org' => '<span property="v:affiliation">{value}</span>');
}

/**
 * Usage
 */
$profileData = array (
    'template' =>
        'My name is {name}, but friends call me {nickname}.'
        . ' Here is my home page: {url}.'
        . ' Now I live in {address} and work as a {title} at {org}.',
    'properties' => array (
        'name' => 'Dmitry Sheiko',
        'nickname' => 'Dima',
        'url' => 'http://dsheiko.com',
        'address' => array (
            'template' => '{locality}, {region}',
            'properties' => array (
                'locality' => 'Frankfurt am Main',
                'region' => 'Germany',
            )
        ),
        'title' => 'web-developer',
        'org' => 'Crytek',
    )
);
$profile = new Profile($profileData);
$profile->setStrategyContext(new ProfileAsMicrodataStrategy());
echo $profile->get(), "\n";
$profile->setStrategyContext(new ProfileAsMicroformatStrategy());
echo $profile->get(), "\n";

// Output
// -> <div itemscope itemtype="http://data-vocabulary.org/Person">My ...
// -> <div class="vcard">My name is <span class="fn">Dmitry Sheiko ...
