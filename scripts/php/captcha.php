<?php
session_start();

function generateCaptcha() {
    $numbers = [
        'null', 'eins', 'zwei', 'drei', 'vier', 'fünf', 
        'sechs', 'sieben', 'acht', 'neun', 'zehn'
    ];
    
    $operations = [
        'plus' => function($a, $b) { return $a + $b; },
        'minus' => function($a, $b) { return $a - $b; },
        'mal' => function($a, $b) { return $a * $b; }
    ];

    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $operation = array_rand($operations);

    if ($operation == 'minus' && $num1 < $num2) {
        list($num1, $num2) = [$num2, $num1];
    }

    $question = $numbers[$num1] . ' ' . $operation . ' ' . $numbers[$num2];
    $answer = $operations[$operation]($num1, $num2);

    $_SESSION['captcha_answer'] = $answer;
    return $question;
}

function validateCaptcha($userInput) {
    return isset($_SESSION['captcha_answer']) && (int)$userInput === $_SESSION['captcha_answer'];
}
