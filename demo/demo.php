<?php

namespace PhpSchema\Demo;

require __DIR__ . '/../vendor/autoload.php';

use PhpSchema\Demo\Entity\Person;
use PhpSchema\Demo\Entity\Invoice;
use PhpSchema\Demo\Entity\Address;
use PhpSchema\ValidationException;

$person = new Person("Bob", "Smith");
$address = new Address("123 Walker Rd", "Apt 101", "Charleston", "SC", "29492");

$invoice = new Invoice($person, $address, 0.05);
$invoice->addLineItem("Hotdog", 400)
        ->addLineItem("Soda", 150)
        ->addLineItem("Chips", 50)
        ->addLineItem("Chips", 50);

try {
    $invoice->addLineItem("Soda", 1.50);
}catch (ValidationException $e){
    echo $e->getMessage() . PHP_EOL . PHP_EOL; // There are errors in the following properties: amount
}

$invoice->pay(578);

echo $invoice->salesReceipt();
/**
Sales receipt for Bob Smith
4 Items:
Hotdog - $4.00
Soda - $1.50
Chips - $0.50
Chips - $0.50
Total: $6.50
Tax: $0.33
Total Due: $6.83
Paid: $5.78
Balance Due: $1.05
 */