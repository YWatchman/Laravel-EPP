<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

use YWatchman\LaravelEPP\Exceptions\EppException;

trait HasScheduledDeletion
{
    /** @var string */
    protected $scheduledDate;

    /** @var string */
    protected $scheduledOperation;

    /**
     * Cancellation enabled status.
     *
     * @var bool
     */
    protected $planned_cancellation = false;

    /**
     * Enable scheduled deletion for request.
     */
    public function enabledScheduledDeletion()
    {
        $this->planned_cancellation = true;
        $this->setScheduledOperation($this->extensions['scheduledDelete']['operation']);
        if (isset($this->extensions['scheduledDelete']['date'])) {
            $this->setScheduledDate($this->extensions['scheduledDelete']['date']);
        }
    }

    /**
     * Scheduled Cancellation node for extensions.
     * Operations:
     * - setDate # Set a cancellation date
     * - setDateToEndOfSubscriptionPeriod # Cancel domain at the end of the subscription.
     * - cancel # Cancel the planned cancellation.
     *
     * @return mixed
     */
    private function scheduledCancellationNode()
    {
        $node = $this->createElement('scheduledDelete:update');
        $node->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');

        $nodeOpts = [];
        $nodeOpts[] = $this->createElement('scheduledDelete:operation', $this->getScheduledOperation());

        if ($this->getScheduledOperation() === 'setDate') {
            $nodeOpts[] = $this->createElement('scheduledDelete:date', $this->getScheduledDate());
        }

        foreach ($nodeOpts as $opt) {
            $node->appendChild($opt);
        }

        return $node;
    }

    /**
     * @return string
     */
    public function getScheduledDate(): string
    {
        return $this->scheduledDate;
    }

    /**
     * @param string $scheduledDate
     */
    public function setScheduledDate(string $scheduledDate): void
    {
        $this->scheduledDate = $scheduledDate;
    }

    /**
     * @return string
     */
    public function getScheduledOperation(): string
    {
        return $this->scheduledOperation;
    }

    /**
     * @param string $scheduledOperation
     */
    public function setScheduledOperation(string $scheduledOperation): void
    {
        if (!in_array($scheduledOperation,
            [
                'setDate',
                'setDateToEndOfSubscriptionPeriod',
                'cancel',
            ]
        )) {
            throw EppException::InvalidOperation($scheduledOperation);
        }
        $this->scheduledOperation = $scheduledOperation;
    }
}
