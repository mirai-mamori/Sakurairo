# ChatGPT PHP SDK | [Package](https://packagist.org/packages/haozi-team/chatgpt-php)

[![Total Downloads](https://poser.pugx.org/HaoZi-Team/ChatGPT-PHP/d/total.svg)](https://packagist.org/packages/haozi-team/chatgpt-php)
[![Latest Stable Version](https://poser.pugx.org/HaoZi-Team/ChatGPT-PHP/v/stable.svg)](https://packagist.org/packages/haozi-team/chatgpt-php)
[![License](https://poser.pugx.org/HaoZi-Team/ChatGPT-PHP/license.svg)](https://packagist.org/packages/haozi-team/chatgpt-php)

Official and Reverse Engineered ChatGPT API for PHP.

Reconstruct from @acheong08's [ChatGPT](https://github.com/acheong08/ChatGPT)

# Installation

`composer require haozi-team/chatgpt-php`

# V1 Web ChatGPT
## Notice: As of 2023/03/15 - OpenAI has deactivated some accounts using V1. We recommend using V2 to avoid getting banned.

> Uses `chat.openai.com`
> - Free
> - Rate limited
> - Needs Bypassing Cloudflare

> Default api endpoint is `https://bypass.churchless.tech/`
> 
> Rate limit at 5 requests / 10 seconds due to small server
> 
> OpenAI rate limit: 50 requests per hour on free accounts. You can get around it with multi-account cycling
>
> Plus accounts has around 150 requests per hour rate limit

## Configuration

1. Create account on [OpenAI's ChatGPT](https://chat.openai.com/)
2. Save your email and password

### Authentication

#### - Access token

Login OpenAI account and go to [https://chat.openai.com/api/auth/session](https://chat.openai.com/api/auth/session)
to get your access_token.

```json
{
  "access_token": "<access_token>"
}
```

The access_token is valid for 30 days.

## Developer API

### Basic example

```php
<?php
use HaoZiTeam\ChatGPT\V1 as ChatGPTV1;

$chatGPT = new ChatGPTV1();
$chatGPT->addAccount('<your_access_token>');

$answers = $chatGPT->ask('Hello, how are you?');
foreach ($answers as $item) {
    print_r($item);
}
//Array(
//    'answer' => 'I am fine, thank you.',
//    'conversation_id' => '<uuid>',
//    'parent_id' => '<uuid>',
//    'model' => 'text-davinci-002-render-sha',
//    'account' => '0',
//)
```

### Advanced example

You can pass "baseUrl" to the first parameter to set a custom API endpoint.

```php
<?php
use HaoZiTeam\ChatGPT\V1 as ChatGPTV1;

$chatGPT = new ChatGPTV1('https://chat.openai.com/backend-api/');
```

More refer to the [wiki](https://github.com/HaoZi-Team/ChatGPT-PHP/wiki/V1-Advanced-examples) for advanced developer
usage.

# V2 Official ChatGPT

> Recently released by OpenAI
> - Costs money

Get API key from https://platform.openai.com/account/api-keys

## Developer API

### Basic example

```php
<?php
use HaoZiTeam\ChatGPT\V2 as ChatGPTV2;

$chatGPT = new ChatGPTV2('sk-<your_api_key>');
$chatGPT->addMessage('You are ChatGPT, a large language model trained by OpenAI. Answer as concisely as possible.', 'system');

$answers = $chatGPT->ask('Hello, how are you?');
foreach ($answers as $item) {
    print_r($item);
}
```

### Advanced example

You can pass "baseUrl" to the second parameter to set a custom API endpoint.

```php
<?php
use HaoZiTeam\ChatGPT\V2 as ChatGPTV2;

$chatGPT = new ChatGPTV2('sk-<your_api_key>', 'https://api.openai.com/');
```

You can use `addMessage` to add messages to the conversation.

```php
<?php
use HaoZiTeam\ChatGPT\V2 as ChatGPTV2;

$chatGPT = new ChatGPTV2('sk-<your_api_key>');
$chatGPT->addMessage('You are ChatGPT, a large language model trained by OpenAI. Answer as concisely as possible.', 'system');
$chatGPT->addMessage('Hello, how are you?', 'user');
$chatGPT->addMessage('I am fine, thank you.', 'assistant');

$answers = $chatGPT->ask('What did I ask you before?');
foreach ($answers as $item) {
    print_r($item);
}
//Array(
//    'answer' => 'Hello, how are you?',
//    'id' => 'cmpl-xxxxx',
//    'model' => 'gpt-3.5-turbo',
//    'usage' => [
//        "prompt_tokens": 9,
//        "completion_tokens": 12,
//        "total_tokens": 21,
//    ],
//)
```

You can set the `stream` parameter to `true` to get a stream for output answers as they are generated.

```php
<?php
use HaoZiTeam\ChatGPT\V2 as ChatGPTV2;

$chatGPT = new ChatGPTV2('sk-<your_api_key>');
$chatGPT->addMessage('You are ChatGPT, a large language model trained by OpenAI. Answer as concisely as possible.', 'system');

$answers = $chatGPT->ask('Hello, how are you?', null, true);// A Generator
foreach ($answers as $item) {
    print_r($item);
}
```

# Disclaimers

This is not an official OpenAI product. This is a personal project and is not affiliated with OpenAI in any way. Don't
sue me.

# Credits

- [acheong08](https://github.com/acheong08) - Python ChatGPT API
