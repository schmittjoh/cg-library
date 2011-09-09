<?php

namespace CG\Proxy;

/**
 * Lazy Initializer.
 *
 * Implementations of this interface are responsible for lazily initializing
 * object instances.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface LazyInitializerInterface
{
    /**
     * Initializes the passed object.
     *
     * @param object $object
     * @return void
     */
    function initializeObject($object);
}