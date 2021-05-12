<?php


namespace yv\node;


use yv\core\Configurable;
use yv\exception\Exception;

/**
 * Class Node
 * @package yv\node
 *
 * @property-read  string  $baseURI
 * @property-read  ?Node[] $childNodes
 * @property-read  ?Node   $firstChild
 * @property-read  ?Node   $lastChild
 * @property-read  ?Node   $nextSibling
 * @property-read  string  $nodeName
 * @property-read  ?Node   $parentNode
 * @property-read  ?Node   $previousSibling
 */
class Node extends Configurable implements NodeInterface, ChildNodeInterface, ParentNodeInterface
{

    public const  MAX_DEEP = 255;

    private const DYNAMIC_PROPERTIES = [
        '_firstChild',
        '_lastChild',
        '_nextSibling',
        '_previousSibling',
    ];

    public string $_baseURI;
    /** @var Node[] */
    public array  $_childNodes = [];
    public ?Node  $_firstChild;
    public ?Node  $_lastChild;
    public ?Node  $_nextSibling;
    public string $_nodeName;
    public ?Node  $_parentNode;
    public ?Node  $_previousSibling;


    public function __construct()
    {
        $this->_baseURI  = __NAMESPACE__;
        $this->_nodeName = basename(static::class);
    }

    /**
     * @param $name
     * @return mixed
     * @uses _firstChild(), _lastChild(), _nextSibling(), _previousSibling()
     */
    public function __get($name)
    {
        if (!in_array($name, static::DYNAMIC_PROPERTIES, true)) {
            return parent::__get($name);
        }
        return $this->$name();
    }

    public function appendChild(Node $newChild): Node
    {
        return $this->_childNodes[] = &$newChild;
    }

    public function insertBefore(Node $newChild): Node
    {
        $key = array_key_first($this->_childNodes);
        if (is_null($key)) {
            return $this->_childNodes[] = &$newChild;
        }
        $this->_childNodes[--$key] = &$newChild;
        ksort($this->_childNodes);
        return $newChild;
    }

    public function contains(Node $other): bool
    {
        return in_array($other, $this->_childNodes, true);
    }

    /**
     * @return Node
     * @throws Exception
     */
    public function getRootNode(): Node
    {
        $root = $this;
        for ($i = static::MAX_DEEP; $i--;) {
            if (!$this->_parentNode) {
                return $root;
            }
            $root = $this->_parentNode;
        }
        throw new Exception('Parent root deeper then ' . static::MAX_DEEP . ' levels');
    }

    public function hasChildNodes(): bool
    {
        return count($this->_childNodes) > 0;
    }


    public function removeChild(Node $oldChild): Node
    {
        $key = array_search($oldChild, $this->_childNodes, true);
        if ($key !== false) {
            unset($this->_childNodes[$key]);
        }
        return $oldChild;
    }

    public function replaceChild(Node $newChild, Node $oldChild): Node
    {
        $key = array_search($oldChild, $this->_childNodes, true);
        if ($key !== false) {
            $this->_childNodes[$key] = &$newChild;
        }
        return $oldChild;
    }

    public function after(Node ...$nodes): void
    {
        $this->insertSiblingNodes($nodes, true);
    }

    public function before(Node ...$nodes): void
    {
        $this->insertSiblingNodes($nodes);
    }

    public function remove(): void
    {
        if ($this->_parentNode) {
            $key = array_search($this, $this->_parentNode->_childNodes, true);
            if ($key !== false) {
                unset($this->_parentNode->_childNodes[$key]);
            }
        }
    }

    public function replaceWith(Node ...$nodes): void
    {
        if ($this->_parentNode) {
            $this->_parentNode->_childNodes = [];
            foreach ($nodes as $node) {
                $this->_parentNode->_childNodes[] = &$node;
                unset($node);
            }
        }
    }

    public function append(Node ...$nodes): void
    {
        foreach ($nodes as $node) {
            $this->_childNodes[] = &$node;
            unset($node);
        }
    }

    public function prepend(Node ...$nodes): void
    {
        $childNodes        = $this->_childNodes;
        $this->_childNodes = [];
        foreach ($nodes as $node) {
            $this->_childNodes[] = &$node;
            unset($node);
        }
        $this->_childNodes = array_merge($this->_childNodes, $childNodes);
    }

    public function querySelector(string $selectors): ?Node
    {
        // TODO: Implement querySelector() method.
    }

    public function querySelectorAll(string $selectors): ?array
    {
        // TODO: Implement querySelectorAll() method.
    }

    private function _firstChild(): ?Node
    {
        return $this->_childNodes[array_key_first($this->_childNodes)];
    }

    private function _lastChild(): ?Node
    {
        return $this->_childNodes[array_key_last($this->_childNodes)];
    }

    private function _nextSibling(): ?Node
    {
        return $this->nearbySibling();
    }

    private function _previousSibling(): ?Node
    {
        return $this->nearbySibling(false);
    }

    private function nearbySibling($next = true): ?Node
    {
        if (!$this->_parentNode) {
            return null;
        }
        $key = array_search($this, $this->_parentNode->_childNodes, true);
        if ($key === false) {
            return null;
        }
        $next ? $key++ : $key--;
        return $this->_parentNode->_childNodes[$key] ?? null;
    }

    private function insertSiblingNodes($nodes, $after = false): void
    {
        if ($this->_parentNode) {
            $key         = array_search($this, $this->_parentNode->_childNodes, true);
            $childAfter  = array_slice($this->_parentNode->_childNodes, $after ? ++$key : $key);
            $childBefore = array_slice($this->_parentNode->_childNodes, 0, $key);
            foreach ($nodes as $node) {
                $childAfter[] = &$node;
                unset($node);
            }
            $this->_parentNode->_childNodes = array_merge($childBefore, $childAfter);
        }
    }


}