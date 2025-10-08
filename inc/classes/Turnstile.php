<?php

namespace Sakura\API;

class Turnstile
{
    public function script()
    {
        return <<<JS
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>

    JS;
    }

    public function html()
    {
        $site_key = iro_opt('turnstile_site_key');
        $theme = iro_opt('turnstile_theme');
        return <<<HTML
         <div class="cf-turnstile" 
         data-sitekey="{$site_key}"
         data-theme="{$theme}"
         data-size="flexible"
         >
         </div>
        HTML;
    }

    public function post($url, $param = [])
  {
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($param)
        ]
    ];
// 因为一些不知道是什么的原因，用wp_safe_remote_post这个方法去请求的话response会什么也获取不到。
// 这里采用官方example的写法
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) {
      return ['success' => false, 'error-codes' => ['internal-error']];
    }
//      return json_decode($response['body'],true);
      return json_decode($response, true);
  }

  public function checkTurnstile($url, $token, $ip)
  {
    $param = [
      'secret' => iro_opt('turnstile_secret'),
      'response' => $token,
      'remoteip' => $ip,
    ];
    return $this->post($url, $param);
  }

}