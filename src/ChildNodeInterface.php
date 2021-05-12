<?php


namespace yv\node;


interface ChildNodeInterface extends NodeInterface
{
    public function after(Node ...$nodes): void;

    public function before(Node ...$nodes): void;

    public function remove(): void;

    public function replaceWith(Node ...$nodes): void;
}