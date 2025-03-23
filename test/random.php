<?php

$cardsList = [
    [
        "m_gacha_id" => 1,
        "m_character_id" => 1,
        "weight" => 66,
        "name" => "R",
        "title" => "power A"
    ],
    [
        "m_gacha_id" => 1,
        "m_character_id" => 2,
        "weight" => 66,
        "name" => "R",
        "title" => "power B"
    ],
    [
        "m_gacha_id" => 1,
        "m_character_id" => 3,
        "weight" => 20,
        "name" => "SR",
        "title" => "cute A"
    ],
    [
        "m_gacha_id" => 1,
        "m_character_id" => 4,
        "weight" => 20,
        "name" => "SR",
        "title" => "cute B"
    ],
    [
        "m_gacha_id" => 1,
        "m_character_id" => 5,
        "weight" => 5,
        "name" => "SSR",
        "title" => "pure A"
    ],
];

$cardsListSrGuaranteed = array_values(array_filter($cardsList, function($cardInfo) {
    return $cardInfo['name'] == 'SR' ||
    $cardInfo['name'] == 'SSR';
}));

function getRandomCharacter($data) {
    $weightedPool = [];

    foreach ($data as $entry) {
        for ($i = 0; $i < $entry['weight']; $i++) {
            $weightedPool[] = $entry['m_character_id'];
        }
    }

    return $weightedPool[array_rand($weightedPool)];
}

function pullOne($cardsList) {
    $randomCharacterId = getRandomCharacter($cardsList);
    $cardIndex = array_search($randomCharacterId, array_column($cardsList, 'm_character_id'));

    if (is_numeric($cardIndex)) {
        return $cardsList[$cardIndex];
    } else {
        echo "No match found.";
    }
    echo "Selected m_character_id: " . $randomCharacterId ."<br />";
}

function pullTen($cardsList, $cardsListSrGuaranteed) {
    $result = array();
    for($i = 0; $i < 9; $i++) {
        $card = pullOne($cardsList);
        array_push($result, $card);
    }
    $card10 = pullOne($cardsListSrGuaranteed);
    array_push($result, $card10);
    return $result;
}

$pullResult = pullTen($cardsList, $cardsListSrGuaranteed);
var_dump($pullResult);