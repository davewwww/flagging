<?php

namespace Lab\Component\Flagging\Voter;

use Lab\Component\Flagging\VoteContext;

/**
 * @author David Wolter <david@dampfer.net>
 */
interface VoterInterface
{
    /**
     * @return string
     */
    function getName();

    /**
     * @param mixed       $config
     * @param VoteContext $token
     *
     * @return Boolean
     */
    function vote($config, VoteContext $token);
}
