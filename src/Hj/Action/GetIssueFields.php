<?php

namespace Hj\Action;

use Hj\Collector\Collector;
use Hj\Helper\DateDiffCalculator;
use Hj\Helper\ResolutionDateFormatter;
use JiraRestApi\Issue\Issue;

class GetIssueFields implements Action
{
    const DATE_FORMAT = 'd/m/Y';
    /**
     * @var array
     */
    private $issueFields;

    /**
     * @var Collector
     */
    private $collector;

    /**
     * @var ResolutionDateFormatter
     */
    private $resolutionDateFormatter;

    /**
     * @var DateDiffCalculator
     */
    private $dateDiffCalculator;

    /**
     * GetIssueFields constructor.
     * @param $collector
     * @param $resolutionDateFormatter
     * @param $dateDiffCalculator
     */
    public function __construct($collector, $resolutionDateFormatter, $dateDiffCalculator)
    {
        $this->collector = $collector;
        $this->resolutionDateFormatter = $resolutionDateFormatter;
        $this->dateDiffCalculator = $dateDiffCalculator;
    }

    /**
     * @param Issue $issue
     * @throws \Exception
     */
    public function apply(Issue $issue)
    {
        $field = $issue->fields;

        $dueDate = '';
        if (null != $field->duedate) {
            $dueDateString = $field->duedate;
            $dueDateAsDatetime = new \DateTime($dueDateString);
            $dueDate = $dueDateAsDatetime->format(self::DATE_FORMAT);
        }

        $createdDateAsDateTime = $field->created;
        $resolutionDateAsString = '';
        $timeOfResolution = '';
        if ('' != $field->resolutiondate) {
            $resolutionDateAsDatetime = $this->resolutionDateFormatter->getResolutionDateAsDateTime($field->resolutiondate);
            $timeOfResolution = $this->dateDiffCalculator->calculate($createdDateAsDateTime, $resolutionDateAsDatetime);
            $resolutionDateAsString = $resolutionDateAsDatetime->format(self::DATE_FORMAT);
        }
        $labels = $field->labels;
        $this->collector->collect($labels);
        $labelHd = '';
        $labelHd .= implode(",", $labels);
        $rootCauseCategory = '';
        $rootCause = '';
        $typeDepot = $field->customFields['customfield_11201']->value;
        $produit = $field->customFields['customfield_11700']->value;
        $severite = $field->customFields['customfield_11102']->value;

        $categorieIncidentValue = '';
        if (isset($field->customFields['customfield_11700']->child)) {
            $categorieIncidentValue = $field->customFields['customfield_11700']->child->value;
        }

        if (isset($field->customFields['customfield_14126'])) {
            $rootCauseCategory = $field->customFields['customfield_14126']->value;
        }
        if (isset($field->customFields['customfield_13005'])) {
            $rootCause = $field->customFields['customfield_13005'];
        }
        $this->issueFields[] =  [
            $issue->key,
            $field->summary,
            $field->status->name,
            $field->reporter->name,
            $field->created->format(self::DATE_FORMAT),
            $dueDate,
            $resolutionDateAsString,
            $timeOfResolution,
            $field->assignee->name,
            $severite,
            $rootCauseCategory,
            $rootCause,
            $labelHd,
            $typeDepot,
            $produit,
            $categorieIncidentValue,
            $field->description,
        ];
    }

    /**
     * @return array
     */
    public function getIssueFields()
    {
        return $this->issueFields;
    }
}