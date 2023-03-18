* 'chatgpt_base_url': chatgpt api baseurl
* 'chatgpt_access_token':
* 'chatgpt_init_prompt': 人设，默认值"You are a excerpt generator. " .
    "You can summarize articles given their title and full text. " .
    "You should use the same language as the article for your excerpt. " .
    "You do not need to write in third person."
* 'chatgpt_ask_prompt':
* 'chatgpt_article_summarize':是否使用chatgpt为没有提供摘要的文章生成摘要

开启本功能后保存/更新文章时，如果这篇文章没有自定义摘要，则会调用ChatGPT API，并在metadata添加ai_summon_excerpt字段。