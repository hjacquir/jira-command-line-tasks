<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

class GetBeginLastHdInfo implements Action{

    private $infos = [];

    public function apply(Issue $issue)
    {
        $field = $issue->fields;
        $labels = $field->labels;
        $firstHd = 'NR';
        $endHd = 'NR';
        if (!empty($labels)) {
            $firstHd = reset($labels);
            $endHd = end($labels);
        }
        $typeDepot = $field->customFields['customfield_11201']->value;
        $produit = $field->customFields['customfield_11700']->value;
        $engagementFriendly = $field->customFields['customfield_14124']->ongoingCycle->remainingTime->friendly;
        $engagementMillis = $field->customFields['customfield_14124']->ongoingCycle->remainingTime->millis;
        $rootCause = '';
        if (isset($field->customFields['customfield_14126'])) {
            $rootCause = $field->customFields['customfield_14126']->value;
        }
        array_push($this->infos, [
            $issue->key,
            $firstHd,
            $endHd,
            $field->summary,
            $typeDepot,
            $produit,
            $field->description,
            $rootCause,
            $engagementFriendly,
            $engagementMillis,
        ]);
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }
}