<?php


namespace YWatchman\LaravelEPP\Support\Traits\Commands;

use YWatchman\LaravelEPP\Exceptions\EppException;

trait HasScheduledDeletion
{

    /**
     * Cancellation enabled status.
     * @var bool
     */
    protected $planned_cancellation = false;

    /**
     * Enable scheduled deletion for request.
     */
    public function enabledScheduledDeletion()
    {
        $this->planned_cancellation = true;
        if (! in_array('planned-cancellation', $this->extensions)) {
            $this->extensions[] = 'planned-cancellation';
        }
    }

    /**
     * Scheduled Cancellation node for extensions.
     * Operations:
     * - setDate # Set a cancellation date
     * - setDateToEndOfSubscriptionPeriod # Cancel domain at the end of the subscription.
     * - cancel # Cancel the planned cancellation.
     *
     * @param string $operation
     * @param string|null $date
     * @return mixed
     *
     * @throws EppException
     */
    private function scheduledCancellationNode(string $operation, string $date = null)
    {
        $node = $this->createElement('scheduledDelete:update');

        if (! in_array($operation, [
            'setDate',
            'setDateToEndOfSubscriptionPeriod',
            'cancel'
        ])) {
            throw EppException::InvalidOperation($operation);
        }

        $nodeOpts = [];
        $nodeOpts[] = $this->createElement('scheduledDelete:operation', $operation);

        if ($operation === 'setDate') {
            $nodeOpts[] = $this->createElement('scheduledDelete:date', $date);
        }

        return $node;
    }
}
