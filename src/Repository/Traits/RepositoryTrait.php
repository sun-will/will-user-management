<?php

namespace App\Repository\Traits;

trait RepositoryTrait
{
    /**
     * @param entity $object
     */
    public function persist($object)
    {
        $this->_em->persist($object);
    }

    /**
     * @param entity $object
     */
    public function persistAndFlush($object)
    {
        $this->persist($object);
        $this->flush();
    }

    /**
     *
     */
    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @param entity $object
     * @param $flush
     */
    public function remove($object, $flush = true)
    {
        $this->_em->remove($object);
        if ($flush == true) {
            $this->flush();
        }
    }

    /**
     * @param $object
     */
    public function refresh($object)
    {
        $this->_em->refresh($object);
    }
}
