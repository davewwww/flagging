<?php

namespace Lab\Component\Flagging\Model;

/**
 * @author David Wolter <david@dampfer.net>
 */
interface ValueInterface extends FiltersTraitInterface
{
    /**
     * @return mixed
     */
    function getValue();
}