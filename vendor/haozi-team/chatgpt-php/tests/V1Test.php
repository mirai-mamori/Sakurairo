<?php

use HaoZiTeam\ChatGPT\V1;

$accessToken = getenv('OPENAI_ACCESS_TOKEN');
$chatGPT = new V1();
$chatGPT->addAccount($accessToken);
$test = $chatGPT->ask('Hello');
foreach ($test as $answer) {
    $conversationId = $answer['conversation_id'];
    $parentId = $answer['id'];
}

it('should get a new conversation', function () use ($chatGPT) {
    $return = $chatGPT->ask('Hello');
    foreach ($return as $answer) {
        $this->assertArrayHasKey('answer', $answer);
    }
})->group('working');

it('should get a conversations array', function () use ($chatGPT) {
    $return = $chatGPT->getConversations();
    $this->assertIsArray($return);
})->group('working');

it('should get an array of a conversation', function () use ($chatGPT, $conversationId, $parentId) {
    $return = $chatGPT->getConversationMessages($conversationId);
    $this->assertIsArray($return);
})->group('working');

it('should auto generate conversation title', function () use ($chatGPT, $conversationId, $parentId) {
    $return = $chatGPT->generateConversationTitle($conversationId, $parentId);
    $this->assertTrue($return);
})->group('working');

it('should setting conversation title', function () use ($chatGPT, $conversationId, $parentId) {
    $return = $chatGPT->updateConversationTitle($conversationId, 'test');
    $this->assertTrue($return);
})->group('working');

it('should delete conversation', function () use ($chatGPT, $conversationId, $parentId) {
    $return = $chatGPT->deleteConversation($conversationId);
    $this->assertTrue($return);
})->group('working');

it('should delete conversations', function () use ($chatGPT) {
    $return = $chatGPT->clearConversations();
    $this->assertTrue($return);
})->group('working');
