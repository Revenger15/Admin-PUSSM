<?php
$pool = ["1643808220584"=> [
    "date"=> "January 3, 2022",
    "mental"=> "95",
    "overall"=> "84",
    "physical"=> "73"
  ],
  "1643808220585"=> [
    "date"=> "February 2, 2022",
    "mental"=> "88",
    "overall"=> "90",
    "physical"=> "92"
  ],
  "1643808220586"=> [
    "date"=> "February 16, 2022",
    "mental"=> "80",
    "overall"=> "76",
    "physical"=> "72"
  ],
  "1643808220587"=> [
    "date"=> "March 1, 2022",
    "mental"=> "92",
    "overall"=> "90",
    "physical"=> "88"
  ],
  "1643808220588"=> [
    "date"=> "March 15, 2022",
    "mental"=> "76",
    "overall"=> "77",
    "physical"=> "78"
  ],
  "1643808220589"=> [
    "date"=> "March 20, 2022",
    "mental"=> "82",
    "overall"=> "86",
    "physical"=> "95"
  ],
  "1643808220590"=> [
    "date"=> "March 22, 2022",
    "mental"=> "79",
    "overall"=> "85",
    "physical"=> "90"
  ]];
$sorted = [];

foreach($pool as $ts => $data) {
    $sorted[date('M', $ts/1000)][$ts] = $data;
}
var_dump($sorted);

foreach($sorted as $month => $data) {
    $t = 0;
    $men = 0;
    $phy = 0;
    $overall = 0;
    foreach($data as $ts => $data2) {
        $t++;
        $men+=$data2['mental'];
        $overall+=$data2['overall'];
        $phy+=$data2['physical'];
        echo $men . ' - ';
        echo $phy. ' - ';
        echo $overall. '<br>';
    }
    $sorted[$month]['mental'] = $men/$t;
    $sorted[$month]['physical'] = $phy/$t;
    $sorted[$month]['overall'] = $overall/$t;

    echo $sorted[$month]['mental'] . '<br>';
    echo $sorted[$month]['physical'] . '<br>';
    echo $sorted[$month]['overall'] . '<br>';
}