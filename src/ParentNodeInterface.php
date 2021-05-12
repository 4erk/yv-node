<?php


namespace yv\node;


interface ParentNodeInterface
{
    public function append(Node ...$nodes): void;

    public function prepend(Node ...$nodes): void;

    public function querySelector(string $selectors): ?Node;

    /**
     * @param string $selectors
     * @return Node[]|null
     */
    public function querySelectorAll(string $selectors): ?array;
}