<?php


namespace yv\node;


interface NodeInterface
{
    public function appendChild(Node $newChild): Node;

    public function contains(Node $other): bool;

    public function getRootNode(): Node;

    public function hasChildNodes(): bool;

    public function insertBefore(Node $newChild): Node;

    public function removeChild(Node $oldChild): Node;

    public function replaceChild(Node $newChild, Node $oldChild): Node;
}