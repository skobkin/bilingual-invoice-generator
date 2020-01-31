<?php

namespace App\Invoice;

class InvoiceData
{
    private int $number;
    private \DateTime $issueDate;
    private \DateTime $contractStartDate;
    private \DateTime $accountedMonthDate;

    public function __construct(
        int $number,
        \DateTime $issueDate,
        \DateTime $contractStartDate,
        \DateTime $accountedMonthDate
    ) {
        $this->number = $number;
        $this->issueDate = $issueDate;
        $this->contractStartDate = $contractStartDate;
        $this->accountedMonthDate = $accountedMonthDate;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getIssueDate(): \DateTime
    {
        return $this->issueDate;
    }

    public function getContractStartDate(): \DateTime
    {
        return $this->contractStartDate;
    }

    public function getAccountedMonthDate(): \DateTime
    {
        return $this->accountedMonthDate;
    }
}
