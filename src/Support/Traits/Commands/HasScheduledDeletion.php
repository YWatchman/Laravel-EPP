<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

use YWatchman\LaravelEPP\Exceptions\EppException;

trait HasScheduledDeletion
{
    protected string $scheduledDate;

    protected string $scheduledOperation;

    /**
     * Cancellation enabled status.
     */
    protected bool $planned_cancellation = false;

    /**
     * Enable scheduled deletion for request.
     *
     * @throws EppException
     */
    public function enabledScheduledDeletion(): void
    {
        $this->planned_cancellation = true;
        $this->setScheduledOperation($this->extensions['scheduledDelete']['operation']);
        if (isset($this->extensions['scheduledDelete']['date'])) {
            $this->setScheduledDate($this->extensions['scheduledDelete']['date']);
        }
    }

    public function getScheduledDate(): string
    {
        return $this->scheduledDate;
    }

    public function setScheduledDate(string $scheduledDate): void
    {
        $this->scheduledDate = $scheduledDate;
    }

    public function getScheduledOperation(): string
    {
        return $this->scheduledOperation;
    }

    /**
     * @throws EppException
     */
    public function setScheduledOperation(string $scheduledOperation): void
    {
        if (! in_array(
            $scheduledOperation,
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

    /**
     * Scheduled Cancellation node for extensions.
     * Operations:
     * - setDate # Set a cancellation date
     * - setDateToEndOfSubscriptionPeriod # Cancel domain at the end of the subscription.
     * - cancel # Cancel the planned cancellation.
     */
    private function scheduledCancellationNode(): mixed
    {
        $node = $this->createElement('scheduledDelete:update');
        $node->setAttribute('xmlns:scheduledDelete', 'http://rxsd.domain-registry.nl/sidn-ext-epp-scheduled-delete-1.0');

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
}
