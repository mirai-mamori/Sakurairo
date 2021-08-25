<?php

namespace Sakura\API;

class Vaptcha
{
  public function script()
  {
    $vid = iro_opt('vaptcha_vid');
    $color = iro_opt('theme_skin');
    $scene = iro_opt('vaptcha_scene');
    return <<<JS
      <script src="https://v-cn.vaptcha.com/v3.js"></script>
      <script>
        vaptcha({
            vid:  `{$vid}`, 
            type: 'click', 
            scene: `{$scene}`, 
            container: '#vaptchaContainer', 
            color: `{$color}`
        }).then(function (vaptchaObj) {
            vaptchaObj.renderTokenInput('wp-login.php');
            vaptchaObj.render();
        });
      </script>
    JS;
  }

  public function html()
  {
    return <<<HTML
      <p>
        <label for="user_login">验证码</label>
      </p>
      <div id="vaptchaContainer" class="vaptchaContainer">
        <div class="vaptcha-init-main">
            <div class="vaptcha-init-loading">
                <a href="/" target="_blank">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="48px"
                    height="60px"
                    viewBox="0 0 24 30"
                    style="enable-background: new 0 0 50 50; width: 14px; height: 14px; vertical-align: middle"
                    xml:space="preserve"
                    >
                        <rect x="0" y="9.22656" width="4" height="12.5469" fill="#CCCCCC">
                            <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                        <animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                        </rect>
                        <rect x="10" y="5.22656" width="4" height="20.5469" fill="#CCCCCC">
                            <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                        <animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                        </rect>
                        <rect x="20" y="8.77344" width="4" height="13.4531" fill="#CCCCCC">
                            <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                        <animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                        </rect>
                    </svg>
                </a>
                <span class="vaptcha-text">Vaptcha Initializing...</span>
            </div>
        </div>
      </div>
    HTML;
  }

  public function checkVaptcha($url, $token, $ip)
  {
    $param = [
      'id' => iro_opt('vaptcha_vid'),
      'secretkey' => iro_opt('vaptcha_key'),
      'scene' => iro_opt('vaptcha_scene'),
      'token' => $token,
      'ip' => $ip,
    ];
    return $this->post($url, $param);
  }

  public function post($url, $param = [])
  {
    $response = wp_safe_remote_post($url, [
      'timeout' => 15,
      'body' => $param,
    ]);
    if (is_wp_error($response)) {
      $errorMessage = $response->get_error_message();
      return $errorMessage;
    } else {
      return json_decode($response['body']);
    }
  }
}
