<?php
namespace DomainLayer;

interface Normalized
{
    /**
     * Context from TreeGetSetNormalizer
     * @return array
     */
    public static function getStandardNormalizeContext();
}
