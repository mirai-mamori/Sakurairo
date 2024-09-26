# OpenAI API Client in PHP

<br />

<br />

> ### ChatGPT API is currently supported, [click here](#chat-as-known-as-chatgpt-api) for the implementation introductions.

<br />

*A message from creator,<br />Thank you for visiting the __@orhanerday/open-ai__ repository! If you find this repository helpful or useful, we encourage you to **star** it
on GitHub. Starring a repository is a way to show your support for the project. It also helps to increase the visibility
of the project and to let the community know that it is valuable. Thanks again for your support and we hope you find the
repository useful! <br /><br /> Orhan*

<br />

<br />


[![Latest Version on Packagist](https://img.shields.io/packagist/v/orhanerday/open-ai.svg?style=flat-square)](https://packagist.org/packages/orhanerday/open-ai)
[![Total Downloads](https://img.shields.io/packagist/dt/orhanerday/open-ai.svg?style=flat-square)](https://packagist.org/packages/orhanerday/open-ai)

<br />

<br />

<img src="./openai-elephpant.svg" width="1250" height="300" alt="orhanerday-open-ai-logo">

<br />

<br />

# Featured in


[![Jetbrains Blog](https://user-images.githubusercontent.com/22305274/222431781-86591161-ccd5-4889-bd80-97a0fd0fdf0d.png)](https://blog.jetbrains.com/phpstorm/2022/12/php-annotated-december-2022/#:~:text=orhanerday/open%2Dai%20%E2%80%93%20A%20PHP%20SDK%20for%20accessing%20the%20OpenAI%20GPT%2D3%20API)

[![Laravel News](https://user-images.githubusercontent.com/22305274/222430084-be097d59-e6bc-408d-8adb-7b751d5a05b2.png)](https://laravel-news.com/openai-sdk-for-php)

[![日思录](https://user-images.githubusercontent.com/22305274/222431699-f3a8a146-e27c-4fe3-8c93-1d762559752f.png)](http://tubring.cn/articles/59)

[![logo_new](https://github.com/orhanerday/open-ai/assets/22305274/398b3a1e-7323-46f3-8a53-a9f115cf2281)](https://site-analyzer.ru/articles/openai-gpt-3-first-samples/)



# Comparison With Other Packages

| Project Name           | Required PHP Version (Lower is better) | Description                                                                                                                                                | Type (Official / Community) | Support                                                                                                                                  |
|------------------------|----------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------|------------------------------------------------------------------------------------------------------------------------------------------|
| **orhanerday/open-ai** | **PHP 7.4+**                           | **Most downloaded, forked, contributed, huge community supported, and used PHP SDK for OpenAI GPT-3 and DALL-E. It also supports chatGPT-like streaming.** | Community                   | Available, ([Community driven Discord Server](https://discord.gg/xpGUD528XJ) or personal mail [orhann@duck.com](mailto:orhann@duck.com)) |
| openai-** */c****t     | PHP 8.1+                               | OpenAI PHP API client.                                                                                                                                     | Community                   | -                                                                                                                                        |


<br />

## About this package

Fully open-source and secure community-maintained, PHP SDK for accessing the OpenAI GPT-3 API.

> #### For more information, you can read laravel news [blog post](https://laravel-news.com/openai-sdk-for-php).
> #### Free support is available. [Join our discord server](#join-our-discord-server)
> #### To get started with this package, you'll first want to be familiar with the [OpenAI API documentation](https://beta.openai.com/docs/introduction) and [examples](https://beta.openai.com/examples). Also you can get help from our discord channel that called [#api-support](https://discord.gg/R9CpVUdqQR)

## News

- orhanerday/open-ai added to community libraries php [section](https://beta.openai.com/docs/libraries/php).
- orhanerday/open-ai featured
  on [PHPStorm blog post](https://blog.jetbrains.com/phpstorm/2022/12/php-annotated-december-2022/#:~:text=orhanerday/open%2Dai%20%E2%80%93%20A%20PHP%20SDK%20for%20accessing%20the%20OpenAI%20GPT%2D3%20API),
  thanks JetBrains!

> Requires PHP 7.4+

## Join our discord server

![Discord Banner 2](https://discordapp.com/api/guilds/1047074572488417330/widget.png?style=banner2)

[Click here to join the Discord server](https://discord.gg/xpGUD528XJ)

## Support this project

As you may know, OpenAI PHP is an open-source project wrapping tool for OpenAI. We rely on the support of our community
to continue developing and maintaining the project, and one way that you can help is by making a donation.

Donations allow us to cover expenses such as hosting costs(for testing), development tools, and other resources that are
necessary to keep the project running smoothly. Every contribution, no matter how small, helps us to continue improving
OpenAI PHP for everyone.

If you have benefited from using OpenAI PHP and would like to support its continued development, we would greatly
appreciate a donation of any amount. You can make a donation through;

* [Buy me a coffee](https://www.buymeacoffee.com/orhane)
* [Patreon](https://patreon.com/orhann)

Thank you for considering a donation to Orhanerday/OpenAI PHP SDK. Your support is greatly appreciated and helps to
ensure that the project can continue to grow and improve.

*Sincerely,*

**Orhan Erday** / Creator.

# Documentation
Please visit https://orhanerday.gitbook.io/openai-php-api-1/

# Endpoint Support

- Chat
    - [x] [ChatGPT API](#chat-as-known-as-chatgpt-api)
- Models
    - [x] [List models](https://beta.openai.com/docs/api-reference/models/list)
    - [x] [Retrieve model](https://beta.openai.com/docs/api-reference/models/retrieve)
- Completions
    - [x] [Create completion](https://beta.openai.com/docs/api-reference/completions/create)
- Edits
    - [x] [Create edits](https://beta.openai.com/docs/api-reference/edits/create)
- Images
    - [x] [Create image](https://beta.openai.com/docs/api-reference/images/create)
    - [x] [Create image edit](https://beta.openai.com/docs/api-reference/images/create-edit)
    - [x] [Create image variation](https://beta.openai.com/docs/api-reference/images/create-variation)
- Embeddings
    - [x] [Create embeddings](https://beta.openai.com/docs/api-reference/embeddings/create)
- Audio
    - [x] [Text to Speech (TTS)](https://platform.openai.com/docs/guides/text-to-speech)
    - [x] [Create transcription](https://platform.openai.com/docs/api-reference/audio/create)
    - [x] [Create translation](https://platform.openai.com/docs/api-reference/audio/create)
- Files
    - [x] [List files](https://beta.openai.com/docs/api-reference/files/list)
    - [x] [Upload file](https://beta.openai.com/docs/api-reference/files/upload)
    - [x] [Delete file](https://beta.openai.com/docs/api-reference/files/delete)
    - [x] [Retrieve file](https://beta.openai.com/docs/api-reference/files/retrieve)
    - [x] [Retrieve file content](https://beta.openai.com/docs/api-reference/files/retrieve-content)
- Fine-tunes
    - [x] [Create fine-tune (beta)](https://beta.openai.com/docs/api-reference/fine-tunes/create)
    - [x] [List fine-tunes (beta)](https://beta.openai.com/docs/api-reference/fine-tunes/list)
    - [x] [Retrieve fine-tune (beta)](https://beta.openai.com/docs/api-reference/fine-tunes/retrieve)
    - [x] [Cancel fine-tune (beta)](https://beta.openai.com/docs/api-reference/fine-tunes/cancel)
    - [x] [List fine-tune events (beta)](https://beta.openai.com/docs/api-reference/fine-tunes/events)
    - [x] [Delete fine-tune model (beta)](https://beta.openai.com/docs/api-reference/fine-tunes/delete-model)
- Moderation
    - [x] [Create moderation](https://beta.openai.com/docs/api-reference/moderations/create)
- ~~Engines~~ *(deprecated)*
    - ~~[List engines](https://beta.openai.com/docs/api-reference/engines/list)~~
    - ~~[Retrieve engine](https://beta.openai.com/docs/api-reference/engines/retrieve)~~
- Assistants (beta)
    - [x] [Create assistant](https://platform.openai.com/docs/api-reference/assistants/createAssistant)
    - [x] [Retrieve assistant](https://platform.openai.com/docs/api-reference/assistants/getAssistant)
    - [x] [Modify assistant](https://platform.openai.com/docs/api-reference/assistants/modifyAssistant)
    - [x] [Delete assistant](https://platform.openai.com/docs/api-reference/assistants/deleteAssistant)
    - [x] [Lists assistants](https://platform.openai.com/docs/api-reference/assistants/listAssistants)
    - [x] [Create assistant file](https://platform.openai.com/docs/api-reference/assistants/createAssistantFile)
    - [x] [Retrieve assistant file](https://platform.openai.com/docs/api-reference/assistants/getAssistantFile)
    - [x] [Delete assistant file](https://platform.openai.com/docs/api-reference/assistants/deleteAssistantFile)
    - [x] [List assistant files](https://platform.openai.com/docs/api-reference/assistants/listAssistantFiles)
- Threads (beta)
    - [x] [Create thread](https://platform.openai.com/docs/api-reference/threads/createThread)
    - [x] [Retrieve thread](https://platform.openai.com/docs/api-reference/threads/getThread)
    - [x] [Modify thread](https://platform.openai.com/docs/api-reference/threads/modifyThread)
    - [x] [Delete thread](https://platform.openai.com/docs/api-reference/threads/deleteThread)
- Messages (beta)
    - [x] [Create message](https://platform.openai.com/docs/api-reference/messages/createMessage)
    - [x] [Retrieve message](https://platform.openai.com/docs/api-reference/messages/getMessage)
    - [x] [Modify message](https://platform.openai.com/docs/api-reference/messages/modifyMessage)
    - [x] [Lists messages](https://platform.openai.com/docs/api-reference/messages/listMessages)
    - [x] [Retrieve message file](https://platform.openai.com/docs/api-reference/messages/getMessageFile)
    - [x] [List message files](https://platform.openai.com/docs/api-reference/messages/listMessageFiles)
- Runs (beta)
    - [x] [Create run](https://platform.openai.com/docs/api-reference/runs/createRun)
    - [x] [Retrieve run](https://platform.openai.com/docs/api-reference/runs/getRun)
    - [x] [Modify run](https://platform.openai.com/docs/api-reference/runs/modifyRun)
    - [x] [Lists runs](https://platform.openai.com/docs/api-reference/runs/listRuns)
    - [x] [Submit tool outputs](https://platform.openai.com/docs/api-reference/runs/submitToolOutputs)
    - [x] [Cancel run](https://platform.openai.com/docs/api-reference/runs/cancelRun)
    - [x] [Create thread and run](https://platform.openai.com/docs/api-reference/runs/createThreadAndRun)
    - [x] [Retrieve run step](https://platform.openai.com/docs/api-reference/runs/getRunStep)
    - [x] [List run steps](https://platform.openai.com/docs/api-reference/runs/listRunSteps)

## Installation

You can install the package via composer:

```bash
composer require orhanerday/open-ai
```

## Quick Start ⚡

Before you get starting, you should set OPENAI_API_KEY as ENV key, and set OpenAI key as env value with the following
commands;

_Powershell_

```powershell
$Env:OPENAI_API_KEY = "sk-gjtv....."
```

_Cmd_

```cmd
set OPENAI_API_KEY=sk-gjtv.....
```

_Linux or macOS_

```shell
export OPENAI_API_KEY=sk-gjtv.....
```

> Getting issues while setting up env? Please read
> the [article](https://help.openai.com/en/articles/5112595-best-practices-for-api-key-safety) or you can check
> my [StackOverflow answer](https://stackoverflow.com/a/73904271/15196622) for the Windows® ENV setup.

Create your `index.php` file and paste the following code part into the file.

```php
<?php

require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.

use Orhanerday\OpenAi\OpenAi;

$open_ai_key = getenv('OPENAI_API_KEY');
$open_ai = new OpenAi($open_ai_key);

$chat = $open_ai->chat([
   'model' => 'gpt-3.5-turbo',
   'messages' => [
       [
           "role" => "system",
           "content" => "You are a helpful assistant."
       ],
       [
           "role" => "user",
           "content" => "Who won the world series in 2020?"
       ],
       [
           "role" => "assistant",
           "content" => "The Los Angeles Dodgers won the World Series in 2020."
       ],
       [
           "role" => "user",
           "content" => "Where was it played?"
       ],
   ],
   'temperature' => 1.0,
   'max_tokens' => 4000,
   'frequency_penalty' => 0,
   'presence_penalty' => 0,
]);


var_dump($chat);
echo "<br>";
echo "<br>";
echo "<br>";
// decode response
$d = json_decode($chat);
// Get Content
echo($d->choices[0]->message->content);
```

_Run the server with the following command_

```shell
php -S localhost:8000 -t .
```

## NVIDIA NIM INTEGRATION

orhanerday/open-ai supports Nvidia NIM. The below example is MixtralAI. Check https://build.nvidia.com/explore/discover for more examples.

```php
<?php

require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.

use Orhanerday\OpenAi\OpenAi;

$nvidia_ai_key = getenv('NVIDIA_AI_API_KEY');
error_log($open_ai_key);
$open_ai = new OpenAi($nvidia_ai_key);
$open_ai->setBaseURL("https://integrate.api.nvidia.com");
$chat = $open_ai->chat([
    'model' => 'mistralai/mixtral-8x7b-instruct-v0.1',
    'messages' => [["role" => "user", "content" => "Write a limmerick about the wonders of GPU computing."]],
    'temperature' => 0.5,
    'max_tokens' => 1024,
    'top_p' => 1,
]);

var_dump($chat);
echo "<br>";
echo "<br>";
echo "<br>";
// decode response
$d = json_decode($chat);
// Get Content
echo ($d->choices[0]->message->content);

```


## Usage

### Load your key from an environment variable.

> According to the following code `$open_ai` is the base variable for all open-ai operations.

```php
use Orhanerday\OpenAi\OpenAi;

$open_ai = new OpenAi(env('OPEN_AI_API_KEY'));
```

## Requesting organization

For users who belong to multiple organizations, you can pass a header to specify which organization is used for an API
request.
Usage from these API requests will count against the specified organization's subscription quota.

````php
$open_ai_key = getenv('OPENAI_API_KEY');
$open_ai = new OpenAi($open_ai_key);
$open_ai->setORG("org-IKN2E1nI3kFYU8ywaqgFRKqi");
````

## Base URL

You can specify Origin URL with `setBaseURL()` method;

````php
$open_ai_key = getenv('OPENAI_API_KEY');
$open_ai = new OpenAi($open_ai_key,$originURL);
$open_ai->setBaseURL("https://ai.example.com/");
````

## Use Proxy

You can use some proxy servers for your requests api;

````php
$open_ai->setProxy("http://127.0.0.1:1086");
````

## Set header

 ```php
$open_ai->setHeader(["Connection"=>"keep-alive"]);
```

## Get cURL request info

> ### !!! WARNING:Your API key will expose if you add this method to your code, therefore remove the method before deployment. Be careful !
You can get cURL info after the request.

````php
$open_ai = new OpenAi($open_ai_key);
echo $open_ai->listModels(); // you should execute the request FIRST!
var_dump($open_ai->getCURLInfo()); // You can call the request
````

## Chat (as known as ChatGPT API)

Given a chat conversation, the model will return a chat completion response.

 ```php
$complete = $open_ai->chat([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        [
            "role" => "system",
            "content" => "You are a helpful assistant."
        ],
        [
            "role" => "user",
            "content" => "Who won the world series in 2020?"
        ],
        [
            "role" => "assistant",
            "content" => "The Los Angeles Dodgers won the World Series in 2020."
        ],
        [
            "role" => "user",
            "content" => "Where was it played?"
        ],
    ],
    'temperature' => 1.0,
    'max_tokens' => 4000,
    'frequency_penalty' => 0,
    'presence_penalty' => 0,
]);
```

## Accessing the Element

```php
<?php
// Dummy Response For Chat API
$j = '
{
   "id":"chatcmpl-*****",
   "object":"chat.completion",
   "created":1679748856,
   "model":"gpt-3.5-turbo-0301",
   "usage":{
      "prompt_tokens":9,
      "completion_tokens":10,
      "total_tokens":19
   },
   "choices":[
      {
         "message":{
            "role":"assistant",
            "content":"This is a test of the AI language model."
         },
         "finish_reason":"length",
         "index":0
      }
   ]
}
';

// decode response
$d = json_decode($j);

// Get Content
echo($d->choices[0]->message->content);
```

> ### Related: [ChatGPT Clone Project](#chatgpt-clone-project)

## Completions

Given a prompt, the model will return one or more predicted completions, and can also return the probabilities of
alternative tokens at each position.

 ```php
$complete = $open_ai->completion([
    'model' => 'gpt-3.5-turbo-instruct',
    'prompt' => 'Hello',
    'temperature' => 0.9,
    'max_tokens' => 150,
    'frequency_penalty' => 0,
    'presence_penalty' => 0.6,
]);
```

### Stream Example

This feature might sound familiar from [ChatGPT](https://chat.openai.com/chat).

<hr>

#### ChatGPT Clone Project

Video of demo:

https://user-images.githubusercontent.com/22305274/219878695-c76a58c0-5081-402c-a1b5-2b1fd971735a.mp4

ChatGPT clone is a simple web application powered by the OpenAI library and built with PHP. It allows users to chat with
an AI language model that responds in real-time. Chat history is saved using cookies, and the project requires the use
of an API key and enabled SQLite3.

Url of The ChatGPT-Clone Repo https://github.com/orhanerday/ChatGPT

<hr>

Whether to stream back partial progress. If set, tokens will be sent as
data-only [server-sent events](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events#event_stream_format)
as they become available, with the stream terminated by a data: [DONE] message.

 ````php
$open_ai = new OpenAi(env('OPEN_AI_API_KEY'));

$opts = [
    'prompt' => "Hello",
    'temperature' => 0.9,
    "max_tokens" => 150,
    "frequency_penalty" => 0,
    "presence_penalty" => 0.6,
    "stream" => true,
];

header('Content-type: text/event-stream');
header('Cache-Control: no-cache');

$open_ai->completion($opts, function ($curl_info, $data) {
    echo $data . "<br><br>";
    echo PHP_EOL;
    ob_flush();
    flush();
    return strlen($data);
});

````

Add this part inside `<body>` of the HTML

 ````php

<div id="divID">Hello</div>
<script>
    var eventSource = new EventSource("/");
    var div = document.getElementById('divID');


    eventSource.onmessage = function (e) {
       if(e.data == "[DONE]")
       {
           div.innerHTML += "<br><br>Hello";
       }
        div.innerHTML += JSON.parse(e.data).choices[0].text;
    };
    eventSource.onerror = function (e) {
        console.log(e);
    };
</script>
````

You should see a response like the in video;

https://user-images.githubusercontent.com/22305274/209847128-f72c9345-dd34-46f0-bbc5-daf1d7b6121f.mp4

## Edits

Creates a new edit for the provided input, instruction, and parameters

 ```php
    $result = $open_ai->createEdit([
        "model" => "text-davinci-edit-001",
        "input" => "What day of the wek is it?",
        "instruction" => "Fix the spelling mistakes",
    ]);
```

## Images (DALL·E)

> All DALL·E Examples available in this [repo](https://github.com/orhanerday/DALLE-Examples).

Given a prompt, the model will return one or more generated images as urls or base64 encoded.

### Create image

Creates an image given a prompt.

 ```php
$complete = $open_ai->image([
    "prompt" => "A cat drinking milk",
    "n" => 1,
    "size" => "256x256",
    "response_format" => "url",
]);
```

### Create image edit

Creates an edited or extended image given an original image and a prompt.
> You need HTML upload for image edit or variation? Please
> check [DALL·E Examples](https://github.com/orhanerday/DALLE-Examples)

````php
$otter = curl_file_create(__DIR__ . './files/otter.png');
$mask = curl_file_create(__DIR__ . './files/mask.jpg');

$result = $open_ai->imageEdit([
    "image" => $otter,
    "mask" => $mask,
    "prompt" => "A cute baby sea otter wearing a beret",
    "n" => 2,
    "size" => "1024x1024",
]);
````

### Create image variation

Creates a variation of a given image.

````php
$otter = curl_file_create(__DIR__ . './files/otter.png');

$result = $open_ai->createImageVariation([
    "image" => $otter,
    "n" => 2,
    "size" => "256x256",
]);
````

## Searches

**_(Deprecated)_**
> This endpoint is deprecated and will be removed on December 3rd, 2022
> OpenAI developed new methods with better
> performance. [Learn more.](https://help.openai.com/en/articles/6272952-search-transition-guide)

Given a query and a set of documents or labels, the model ranks each document based on its semantic similarity to the
provided query.

```php
$search = $open_ai->search([
    'engine' => 'ada',
    'documents' => ['White House', 'hospital', 'school'],
    'query' => 'the president',
]);
```

## Embeddings

Get a vector representation of a given input that can be easily consumed by machine learning models and algorithms.

Related guide: [Embeddings](https://beta.openai.com/docs/guides/embeddings)

### Create embeddings

```php
$result = $open_ai->embeddings([
    "model" => "text-similarity-babbage-001",
    "input" => "The food was delicious and the waiter..."
]);
```

## Answers

**_(Deprecated)_**

> This endpoint is deprecated and will be removed on December 3rd, 2022
> We’ve developed new methods with better
> performance. [Learn more](https://help.openai.com/en/articles/6233728-answers-transition-guide).

Given a question, a set of documents, and some examples, the API generates an answer to the question based on the
information in the set of documents. This is useful for question-answering applications on sources of truth, like
company documentation or a knowledge base.

  ```php
$answer = $open_ai->answer([
    'documents' => ['Puppy A is happy.', 'Puppy B is sad.'],
    'question' => 'which puppy is happy?',
    'search_model' => 'ada',
    'model' => 'curie',
    'examples_context' => 'In 2017, U.S. life expectancy was 78.6 years.',
    'examples' => [['What is human life expectancy in the United States?', '78 years.']],
    'max_tokens' => 5,
    'stop' => ["\n", '<|endoftext|>'],
]);
```

## Classifications

**_(Deprecated)_**
> This endpoint is deprecated and will be removed on December 3rd, 2022
> OpenAI developed new methods with better
> performance. [Learn more.](https://help.openai.com/en/articles/6272941-classifications-transition-guide)

Given a query and a set of labeled examples, the model will predict the most likely label for the query. Useful as a
drop-in replacement for any ML classification or text-to-label task.

 ```php
$classification = $open_ai->classification([
    'examples' => [
        ['A happy moment', 'Positive'],
        ['I am sad.', 'Negative'],
        ['I am feeling awesome', 'Positive'],
    ],
    'labels' => ['Positive', 'Negative', 'Neutral'],
    'query' => 'It is a raining day =>(',
    'search_model' => 'ada',
    'model' => 'curie',
]);
```

## Content Moderations

Given a input text, outputs if the model classifies it as violating OpenAI's content policy.

```php
$flags = $open_ai->moderation([
    'input' => 'I want to kill them.'
]);
```

Know more about Content Moderations here: [OpenAI Moderations](https://beta.openai.com/docs/api-reference/moderations)

## List engines

**_(Deprecated)_**

> The Engines endpoints are deprecated.
> Please use their replacement, [Models](#list-models), instead. [Learn more](TODO?).

Lists the currently available engines, and provides basic information about each one such as the owner and availability.

 ```php
$engines = $open_ai->engines();
```

## Audio

### Text To Speech (TTS)

```php

$result = $open_ai->tts([
    "model" => "tts-1", // tts-1-hd
    "input" => "I'm going to use the stones again. Hey, we'd be going in short-handed, you know",
    "voice" => "alloy", // echo, fable, onyx, nova, and shimmer
]);

// Save audio file
file_put_contents('tts-result.mp3', $result);
```

### Create Transcription

Transcribes audio into the input language.

```php
$c_file = curl_file_create(__DIR__ . '/files/en-marvel-endgame.m4a');

$result = $open_ai->transcribe([
    "model" => "whisper-1",
    "file" => $c_file,
]);
```
#### Response

```json
{
  "text": "I'm going to use the stones again. Hey, we'd be going in short-handed, you know. Look, he's still got the stones, so... So let's get them. Use them to bring everyone back. Just like that? Yeah, just like that. Even if there's a small chance that we can undo this, I mean, we owe it to everyone who's not in this room to try. If we do this, how do we know it's going to end any differently than it did before? Because before you didn't have me. Hey, little girl, everybody in this room is about that superhero life. And if you don't mind my asking, where the hell have you been all this time? There are a lot of other planets in the universe. But unfortunately, they didn't have you guys. I like this one. Let's go get this son of a bitch."
}
```

### Create Translation

Translates audio into English.

_I use Turkish voice for translation thanks to famous science YouTuber [Barış Özcan](https://youtu.be/r2dQgdktUJg?t=90)_

```php
$c_file = curl_file_create(__DIR__ . '/files/tr-baris-ozcan-youtuber.m4a');

$result = $open_ai->translate([
    "model" => "whisper-1",
    "file" => $c_file,
]);
```
#### Response

```json
{
  "text": "GPT-3. Last month, the biggest leap in the world of artificial intelligence in recent years happened silently. Maybe the biggest leap of all time. GPT-3's beta version was released by OpenAI. When you hear such a sentence, you may think, what kind of leap is this? But be sure, this is the most advanced language model with the most advanced language model with the most advanced language ability. It can answer these artificial intelligence questions, it can translate and even write poetry. Those who have gained access to the API or API of GPT-3 have already started to make very interesting experiments. Let's look at a few examples together. Let's start with an example of aphorism. This site produces beautiful words that you can tweet. Start to actually do things with your words instead of just thinking about them."
}
```

 #### Need HTML upload for audio? Check [this](#upload-file-with-html-form) section and change api references. Example :

```php
...
    echo $open_ai->translate(
        [
            "purpose" => "answers",
            "file" => $c_file,
        ]
    );
...
// OR
...
    echo $open_ai->transcribe(
        [
            "purpose" => "answers",
            "file" => $c_file,
        ]
    );
...
```

## Files

Files are used to upload documents that can be used across features like Answers, Search, and Classifications

### List files

Returns a list of files that belong to the user's organization.

```php
$files = $open_ai->listFiles();
```

### Upload file

Upload a file that contains document(s) to be used across various endpoints/features. Currently, the size of all the
files uploaded by one organization can be up to 1 GB. Please contact OpenAI if you need to increase the storage limit.

```php
$c_file = curl_file_create(__DIR__ . 'files/sample_file_1.jsonl');
$result = $open_ai->uploadFile([
            "purpose" => "answers",
            "file" => $c_file,
]);
```

### Upload file with HTML Form

```php
<form action="index.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>
<?php
require __DIR__ . '/vendor/autoload.php';

use Orhanerday\OpenAi\OpenAi;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ob_clean();
    $open_ai = new OpenAi(env('OPEN_AI_API_KEY'));
    $tmp_file = $_FILES['fileToUpload']['tmp_name'];
    $file_name = basename($_FILES['fileToUpload']['name']);
    $c_file = curl_file_create($tmp_file, $_FILES['fileToUpload']['type'], $file_name);

    echo "[";
    echo $open_ai->uploadFile(
        [
            "purpose" => "answers",
            "file" => $c_file,
        ]
    );
    echo ",";
    echo $open_ai->listFiles();
    echo "]";

}

```

### Delete file

 ```php
$result = $open_ai->deleteFile('file-xxxxxxxx');
```

### Retrieve file

 ```php
$file = $open_ai->retrieveFile('file-xxxxxxxx');
```

### Retrieve file content

 ```php
$file = $open_ai->retrieveFileContent('file-xxxxxxxx');
```

## Fine-tunes

Manage fine-tuning jobs to tailor a model to your specific training data.

### Create fine-tune

 ```php
$result = $open_ai->createFineTune([
        "model" => "gpt-3.5-turbo-1106",
        "training_file" => "file-U3KoAAtGsjUKSPXwEUDdtw86",
]);
```

### List fine-tune

 ```php
$fine_tunes = $open_ai->listFineTunes();
```

### Retrieve fine-tune

 ```php
$fine_tune = $open_ai->retrieveFineTune('ft-AF1WoRqd3aJAHsqc9NY7iL8F');
```

### Cancel fine-tune

 ```php
$result = $open_ai->cancelFineTune('ft-AF1WoRqd3aJAHsqc9NY7iL8F');
```

### List fine-tune events

 ```php
$fine_tune_events = $open_ai->listFineTuneEvents('ft-AF1WoRqd3aJAHsqc9NY7iL8F');
```

### Delete fine-tune model

 ```php
$result = $open_ai->deleteFineTune('curie:ft-acmeco-2021-03-03-21-44-20');
```

## Retrieve engine

**_(Deprecated)_**

Retrieves an engine instance, providing basic information about the engine such as the owner and availability.

 ```php
$engine = $open_ai->engine('davinci');
```

## Models

List and describe the various models available in the API.

### List models

Lists the currently available models, and provides basic information about each one such as the owner and availability.

 ```php
$result = $open_ai->listModels();
```

### Retrieve model

Retrieves a model instance, providing basic information about the model such as the owner and permissioning.

 ```php
$result = $open_ai->retrieveModel("text-ada-001");
```

## Printing results *i.e.* `$search`

 ```php
echo $search;
```
## Assistants (beta)

Allows you to build AI assistants within your own applications.

### Create assistant

Create an assistant with a model and instructions.

```php
$data = [
    'model' => 'gpt-3.5-turbo',
    'name' => 'my assistant',
    'description' => 'my assistant description',
    'instructions' => 'you should cordially help me',
    'tools' => [],
    'file_ids' => [],
];

$assistant = $open_ai->createAssistant($data);
```

### Retrieve assistant

```php
$assistantId = 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz';

$assistant = $open_ai->retrieveAssistant($assistantId);
```

### Modify assistant

```php
$assistantId = 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz';
$data = [
    'name' => 'my modified assistant',
    'instructions' => 'you should cordially help me again',
];

$assistant = $open_ai->modifyAssistant($assistantId, $data);
```

### Delete assistant

```php
$assistantId = 'asst_DgiOnXK7nRfyvqoXWpFlwESc';

$assistant = $open_ai->deleteAssistant($assistantId);
```

### Lists assistants

Returns a list of assistants.

```php
$query = ['limit' => 10];

$assistants = $open_ai->listAssistants($query);
```

### Create assistant file

Create an assistant file by attaching a File to an assistant.

```php
$assistantId = 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz';
$fileId = 'file-jrNZZZBAPGnhYUKma7CblGoR';

$file = $open_ai->createAssistantFile($assistantId, $fileId);
```

### Retrieve assistant file

```php
$assistantId = 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz';
$fileId = 'file-jrNZZZBAPGnhYUKma7CblGoR';

$file = $open_ai->retrieveAssistantFile($assistantId, $fileId);
```

### Delete assistant file

```php
$assistantId = 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz';
$fileId = 'file-jrNZZZBAPGnhYUKma7CblGoR';

$file = $open_ai->deleteAssistantFile($assistantId, $fileId);
```

### List assistant files

Returns a list of assistant files.

```php
$assistantId = 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz';
$query = ['limit' => 10];

$files = $open_ai->listAssistantFiles($assistantId, $query);
```

## Threads (beta)

Create threads that assistants can interact with.

### Create thread

```php
$data = [
    'messages' => [
        [
            'role' => 'user',
            'content' => 'Hello, what is AI?',
            'file_ids' => [],
        ],
    ],
];

$thread = $open_ai->createThread($data);
```

### Retrieve thread

```php
$threadId = 'thread_YKDArENVWFDO2Xz3POifFYlp';

$thread = $open_ai->retrieveThread($threadId);
```

### Modify thread

```php
$threadId = 'thread_YKDArENVWFDO2Xz3POifFYlp';
$data = [
    'metadata' => ['test' => '1234abcd'],
];

$thread = $open_ai->modifyThread($threadId, $data);
```

### Delete thread

```php
$threadId = 'thread_YKDArENVWFDO2Xz3POifFYlp';

$thread = $open_ai->deleteThread($threadId);
```

## Messages (beta)

Create messages within threads.

### Create message

```php
$threadId = 'thread_YKDArENVWFDO2Xz3POifFYlp';
$data = [
    'role' => 'user',
    'content' => 'How does AI work? Explain it in simple terms.',
];

$message = $open_ai->createThreadMessage($threadId, $data);
```

### Retrieve message

```php
$threadId = 'thread_d86alfR2rfF7rASyV4V7hicz';
$messageId = 'msg_d37P5XgREsm6BItOcppnBO1b';

$message = $open_ai->retrieveThreadMessage($threadId, $messageId);
```

### Modify message

```php
$threadId = 'thread_d86alfR2rfF7rASyV4V7hicz';
$messageId = 'msg_d37P5XgREsm6BItOcppnBO1b';
$data = [
    'metadata' => ['test' => '1234abcd'],
];

$message = $open_ai->modifyThreadMessage($threadId, $messageId, $data);
```

### Lists messages

Returns a list of messages for a given thread.

```php
$threadId = 'thread_d86alfR2rfF7rASyV4V7hicz';
$query = ['limit' => 10];

$messages = $open_ai->listThreadMessages($threadId, $query);
```

### Retrieve message file

```php
$threadId = 'thread_d86alfR2rfF7rASyV4V7hicz';
$messageId = 'msg_CZ47kAGZugAfeHMX6bmJIukP';
$fileId = 'file-CRLcY63DiHphWuBrmDWZVCgA';

$file = $open_ai->retrieveMessageFile($threadId, $messageId, $fileId);
```

### List message files

Returns a list of message files.

```php
$threadId = 'thread_d86alfR2rfF7rASyV4V7hicz';
$messageId = 'msg_CZ47kAGZugAfeHMX6bmJIukP';
$query = ['limit' => 10];

$files = $open_ai->listMessageFiles($threadId, $messageId, $query);
```

## Runs (beta)

Represents an execution run on a thread.

### Create run

```php
$threadId = 'thread_d86alfR2rfF7rASyV4V7hicz';
$data = ['assistant_id' => 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz'];

$run = $open_ai->createRun($threadId, $data);
```

### Retrieve run

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$runId = 'run_xBKYFcD2Jg3gnfrje6fhiyXj';

$run = $open_ai->retrieveRun($threadId, $runId);
```

### Modify run

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$runId = 'run_xBKYFcD2Jg3gnfrje6fhiyXj';
$data = [
    'metadata' => ['test' => 'abcd1234'],
];

$run = $open_ai->modifyRun($threadId, $runId, $data);
```

### Lists runs

Returns a list of runs belonging to a thread.

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$query = ['limit' => 10];

$runs = $open_ai->listRuns($threadId, $query);
```

### Submit tool outputs

When a run has the status: "requires_action" and required_action.type is submit_tool_outputs, this endpoint can be used to submit the outputs from the tool calls once they're all completed. All outputs must be submitted in a single request.

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$runId = 'run_xBKYFcD2Jg3gnfrje6fhiyXj';
$outputs = [
    'tool_outputs' => [
        ['tool_call_id' => 'call_abc123', 'output' => '28C'],
    ],
];

$run = $open_ai->submitToolOutputs($threadId, $runId, $outputs);
```

### Cancel run

Cancels a run that is "in_progress".

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$runId = 'run_xBKYFcD2Jg3gnfrje6fhiyXj';

$run = $open_ai->cancelRun($threadId, $runId);
```

### Create thread and run

Create a thread and run it in one request.

```php
$data = [
    'assistant_id' => 'asst_zT1LLZ8dWnuFCrMFzqxFOhzz',
    'thread' => [
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Hello, what is AI?',
                'file_ids' => [],
            ],
        ],
    ],
];

$run = $open_ai->createThreadAndRun($data);
```

### Retrieve run step

Retrieves a step in execution of a run.

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$runId = 'run_xBKYFcD2Jg3gnfrje6fhiyXj';
$stepId = 'step_kwLG0vPQjqVyQHVoL7GVK3aG';

$step = $open_ai->retrieveRunStep($threadId, $runId, $stepId);
```

### List run steps

Returns a list of run steps belonging to a run.

```php
$threadId = 'thread_JZbzCYpYgpNb79FNeneO3cGI';
$runId = 'run_xBKYFcD2Jg3gnfrje6fhiyXj';
$query = ['limit' => 10];

$steps = $open_ai->listRunSteps($threadId, $runId, $query);
```

## Testing

To run all tests:

```bash
composer test
```

To run only those tests that work for most user (exclude those that require a missing folder or that hit deprecated
endpoints no longer available to most users):

```bash
./vendor/bin/pest --group=working
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please report security vulnerabilities to [orhanerday@gmail.com](mailto:orhanerday@gmail.com)

## Credits

- [Orhan Erday](https://github.com/orhanerday)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Donation

<a href="https://www.buymeacoffee.com/orhane" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>

## Star History

[![Star History Chart](https://api.star-history.com/svg?repos=orhanerday/open-ai&type=Date)](https://star-history.com/#orhanerday/open-ai&Date)
