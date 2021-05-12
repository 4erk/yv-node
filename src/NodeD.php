<?php


namespace yv\node;


use yv\core\Decorator;

/**
 * Class NodeD
 * @package yv\node
 *
 * @property-read  string  $baseURI
 * @property-read  ?Node[] $children
 * @property-read  ?Node   $first
 * @property-read  ?Node   $last
 * @property-read  ?Node   $next
 * @property-read  string  $nodeName
 * @property-read  ?Node   $parent
 * @property-read  ?Node   $prev
 *
 * @method void append(Node ...$nodes)
 * @method void prepend(Node ...$nodes)
 * @method Node appendChild(Node $newChild)
 * @method Node insertBefore(Node $newChild)
 * @method bool contains(Node $other)
 * @method Node root()
 * @method bool hasChild()
 * @method Node removeChild(Node $oldChild)
 * @method Node replaceChild(Node $newChild, Node $oldChild)
 * @method void replaceWith(Node ...$nodes)
 * @method void after(Node ...$nodes)
 * @method void before(Node ...$nodes)
 * @method void remove()
 */
class NodeD extends Decorator
{

    public const ORIGINAL_NAME  = 'node';
    public const ORIGINAL_CLASS = Node::class;
    public const MAP_PROPERTIES = [
        'baseURI'  => 'baseURI',
        'children' => 'childNodes',
        'first'    => 'firstChild',
        'last'     => 'lastChild',
        'next'     => 'nextSibling',
        'nodeName' => 'nodeName',
        'parent'   => 'parentNode',
        'prev'     => 'previousSibling',

    ];
    public const MAP_METHODS    = [
        'append'       => 'append',
        'prepend'      => 'prepend',
        'appendChild'  => 'appendChild',
        'insertBefore' => 'insertBefore',
        'contains'     => 'contains',
        'root'         => 'getRootNode',
        'hasChild'     => 'hasChildNodes',
        'removeChild'  => 'removeChild',
        'replaceChild' => 'replaceChild',
        'replaceWith'  => 'replaceWith',
        'after'        => 'after',
        'before'       => 'before',
        'remove'       => 'remove',
    ];
    public static Node $node;

    public function __construct($name = null, $namespace = null)
    {
        static::initDecorator([$name, $namespace]);
    }
}