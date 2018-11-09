<?php

namespace PhpSchema\Demo\Entity;

use PhpSchema\Models\SchemaModel;
use PhpSchema\Traits\MethodAccess;

class Invoice extends SchemaModel
{
    use MethodAccess;

    protected static $schema = [
        '$ref' => 'file://' . __DIR__ . '/../Schemas/invoice.json'
    ];

    public function __construct(Person $person, Address $address, float $taxRate)
    {
        $lineItems = [];
        parent::__construct(compact('person', 'address', 'lineItems', 'taxRate'));
    }

    public function addLineItem($product, $amount)
    {
        $this->lineItems()->push(new LineItem($product, $amount));

        return $this;
    }

    public function removeLineItem($product)
    {
        $lineItems = $this->lineItems();

        foreach ($lineItems as $index => $lineItem) {
            if ($lineItem->product == $product) {
                unset($lineItems[$index]);
                break;
            }
        }
    }

    public function total()
    {
        return $this->lineItems()->reduce(function ($total, $lineItem) {
            return $total += $lineItem->amount;
        }, 0);
    }

    public function taxDue()
    {
        return round($this->taxRate() * $this->total());
    }

    public function totalDue()
    {
        return $this->taxDue() + $this->total();
    }

    public function balanceDue()
    {
        return $this->totalDue() - $this->pay();
    }

    public function salesReceipt()
    {
        echo "Sales receipt for " . $this->person()->fullName() . PHP_EOL;
        echo $this->lineItems()->count() . " Items: " . PHP_EOL;
        foreach ($this->lineItems() as $lineItem) {
            echo $lineItem->product . " - " . static::formatMoney($lineItem->amount) . PHP_EOL;
        }
        echo "Total: " . static::formatMoney($this->total()) . PHP_EOL;
        echo "Tax: " . static::formatMoney($this->taxDue()) . PHP_EOL;
        echo "Total Due: " . static::formatMoney($this->totalDue()) . PHP_EOL;
        echo "Paid: " . static::formatMoney($this->pay()) . PHP_EOL;
        echo "Balance Due: " . static::formatMoney($this->balanceDue()) . PHP_EOL;
    }

    protected static function formatMoney($amount): string
    {
        return "$" . number_format(($amount / 100), 2);
    }
}