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
$invoice->addLineItem("Hotdog", 400);
$invoice->addLineItem("Soda", 150);
$invoice->addLineItem("Chips", 50);
$invoice->addLineItem("Chips", 50);

try {
    $invoice->addLineItem("Soda", 1.50);
}catch (ValidationException $e){
    echo $e->getMessage() . PHP_EOL;
}

try {
    $invoice->removeLineItem("Chips");
}catch (ValidationException $e) {
    dd( $e->getErrors() );
}


$invoice->pay(578);

echo $invoice->salesReceipt();