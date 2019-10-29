<?php
include_once 'classes/Reportage.php';

// Given array
$reportage = [
  [
    'user' => 'Melissa Jones',
    'reportsTo' => false
  ],
  [
    'user' => 'Sam Little',
    'reportsTo' => 'Jason Beake'
  ],
  [
    'user' => 'Colleen Adams',
    'reportsTo' => 'Amy Barnes'
  ],
  [
    'user' => 'Amy Barnes',
    'reportsTo' => 'Melissa Jones'
  ],
  [
    'user' => 'Allison Meyers',
    'reportsTo' => 'Colleen Adams'
  ],
  [
    'user' => 'Jason Beake',
    'reportsTo' => 'Amy Barnes'
  ],
];

// We initiate our Object and enter given array
$Object = new Reportage($reportage);

// Task A. Return the name of the user with highest number of received reports.
// I made universal function with option to enter field (user or reportsTo) so we can find not only person who sent more reports but person who received more reports.
// It can be more than one person with highest number of sent reports, so output will be an array.
$highestNumberOfReportsReceived = $Object->highestNumberOfReports('reportsTo'); // can be 'user' or 'reportsTo'

echo '<h4>Task A. Users with highest number of reports</h4>';
echo '<ul>';
foreach ($highestNumberOfReportsReceived as $value) {
  echo '<li>'.$value.'</li>';
}
echo '</ul>';


//Task B. Return the whole tree in descending order as an array above the user.
// I created function where we can set user name and get all users above that user in descending order.
$descendingTree = $Object->descendingTree('Allison Meyers');

echo '<h4>Task B (first version). List of users in descending order</h4>';
echo '<ul>';
foreach ($descendingTree as $value) {
  echo '<li>'.$value.'</li>';
}
echo '</ul>';

// Task B. Correct.
$correctTree = $Object->userTreeReverse('Allison Meyers');

echo '<h4>Task B (correct). Tree of users in descending order</h4>';
echo '<ul>';
foreach ($correctTree as $value) {
  echo '<li>'.$value.'</li>';
}
echo '</ul>';