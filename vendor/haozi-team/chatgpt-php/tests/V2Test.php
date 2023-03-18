<?php

use HaoZiTeam\ChatGPT\V2;

$secretKey = getenv('OPENAI_SECRET_KEY');
$chatGPT = new V2($secretKey);
$chatGPT->addMessage('You are ChatGPT, a large language model trained by OpenAI. Answer as concisely as possible.', 'system');

it('should get a new conversation', function () use ($chatGPT) {
    $return = $chatGPT->ask('Hello, how are you?');
    $this->assertArrayHasKey('answer', $return);
})->group('working');

it('should get a answer contact the context', function () use ($chatGPT) {
    $chatGPT->ask('Hello, how are you?');
    $return = $chatGPT->ask('What did I ask you just now?');
    $this->assertArrayHasKey('answer', $return);
    $this->assertStringContainsString('Hello, how are you?', $return['answer']);
})->group('working');
