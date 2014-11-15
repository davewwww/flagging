<?php

namespace Lab\Component\Flagging;

use Lab\Component\Flagging\Model\FeatureInterface;
use Lab\Component\Flagging\Model\FeatureManagerInterface;
use Lab\Component\Flagging\Voter\VoterInterface;

/**
 * :TODO: refactor
 * @author David Wolter <david@dampfer.net>
 */
class FeatureDecider implements FeatureDeciderInterface
{
    /**
     * @var FeatureManagerInterface
     */
    protected $featureManager;

    /**
     * @var VoterInterface
     */
    protected $filterCollectionVoter;

    /**
     * @param FeatureManagerInterface $featureManager
     * @param VoterInterface          $filterCollectionVoter
     */
    function __construct(FeatureManagerInterface $featureManager, VoterInterface $filterCollectionVoter)
    {
        $this->featureManager = $featureManager;
        $this->filterCollectionVoter = $filterCollectionVoter;
    }

    /**
     * {@inheritdoc}
     */
    public function decideFeature(FeatureInterface $feature, VoteContext $context, $default = null)
    {
        if (!$feature->isEnabled()) {
            return false;
        }

        $context->setName($feature->getName());
        $this->checkParameter($context, $feature->getRequiredParameters());

        return $this->voteFilters($feature->getFilters(), $context);
    }

    /**
     * {@inheritdoc}
     */
    public function decide($name, VoteContext $context, $default = null)
    {
        if (null !== $feature = $this->featureManager->findFeatureByName($name)) {
            return $this->decideFeature($feature, $context, $default);
        }

        return $default;
    }

    /**
     * @param $filters
     * @param $context
     *
     * @return bool
     */
    protected function voteFilters($filters, $context)
    {
        return !empty($filters) ? $this->filterCollectionVoter->vote($filters, $context) : true;
    }

    /**
     * @deprecated
     * @param VoteContext $token
     * @param array       $required
     *
     * @throws \Exception
     */
    protected function checkParameter(VoteContext $token, array $required)
    {
        if (count($required)) {
            if (count($diff = array_diff($required, array_merge(array("user"), array_keys($token->getParams()))))) {
                throw new \Exception(
                    sprintf("expect for feature '%s' this parameters: %s", $token->getName(), implode(", ", $diff))
                );
            }
        }
    }
}
