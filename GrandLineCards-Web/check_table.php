<?php

use Illuminate\Support\Facades\Schema;

echo "Checking columns for 'price_points' table...\n";
$columns = Schema::getColumnListing('price_points');
print_r($columns);

echo "\nChecking columns for 'cards' table...\n";
$cardsColumns = Schema::getColumnListing('cards');
print_r($cardsColumns);
