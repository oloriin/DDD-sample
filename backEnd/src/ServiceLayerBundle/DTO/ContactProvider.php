<?php
namespace ServiceLayerBundle\DTO;

class ContactProvider implements \Iterator, \Countable
{
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    private $elements = [];

    /**
     * @param ContactNormalizer $contactNormalizer
     */
    public function add(ContactNormalizer $contactNormalizer)
    {
        $this->elements[] = $contactNormalizer;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return ContactNormalizer
     * @since 5.0.0
     */
    public function current() : ContactNormalizer
    {
        return current($this->elements);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return ContactNormalizer
     * @since 5.0.0
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return integer
     * @since 5.0.0
     */
    public function key() : int
    {
        return key($this->elements);
    }

    /** {@inheritDoc} */
    public function valid() : bool
    {
        $key = key($this->elements);
        $var = ($key !== null && $key !== false);
        return $var;
    }

    /** {@inheritDoc} */
    public function rewind()
    {
        reset($this->elements);
    }

    /** {@inheritDoc} */
    public function count() : int
    {
        return count($this->elements);
    }
}
