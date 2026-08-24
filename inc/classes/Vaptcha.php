<?php

namespace Sakura\API;

class Vaptcha
{
    /**
     * 本地验签有效期（秒）。
     * 官方校验窗口为 30 秒，本地验签官方建议 3 秒；如遇服务器时钟偏差可适当调大。
     */
    const TOKEN_TTL = 3;

    /**
     * 输出前端初始化脚本（VAPTCHA V4 新版 SDK）
     */
    public function script()
    {
        $vid   = iro_opt('vaptcha_vid');
        $color = iro_opt('theme_skin');
        $lang  = iro_opt('vaptcha_lang') ?: 'zh-CN';
    
        $vidJs   = $this->js($vid);
        $langJs  = $this->js($lang);
        $colorJs = $color ? ', color: ' . $this->js($color) : '';
    
        return <<<JS
    <script src="https://c4.vaptcha.com/src/v4.js"></script>
    <script>
    (function () {
        var form = document.getElementById('loginform');
        if (!form) return;
    
        var mount     = document.getElementById('vaptchaContainer');
        var verifyBtn = document.getElementById('vaptcha-verify-btn');
        var actionRow = document.getElementById('vaptcha-action-row');
    
        var vaptchaObj = null;
        var ready = false;
        var checking = false;
        var passed = false;
    
        var BTN_IDLE = '点击完成人机验证';
        var BTN_LOADING = '验证中...';
        var BTN_PASSED = '验证通过';
    
        function setButton(text, state) {
            if (verifyBtn) verifyBtn.textContent = text;
            if (actionRow) actionRow.classList.toggle('is-passed', state === 'passed');
        }
    
        function setField(name, value) {
            var input = form.querySelector('input[name="' + name + '"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                form.appendChild(input);
            }
            input.value = value;
        }
    
        function init() {
            ready = false;
            passed = false;
            form.removeAttribute('data-vaptcha-checked');
            setButton(BTN_IDLE, '');
    
            if (mount) mount.innerHTML = '';
    
            if (typeof window.vaptcha !== 'function') {
                setButton(BTN_IDLE, '');
                return;
            }
    
            window.vaptcha({
                vid: {$vidJs},
                container: '#vaptchaContainer',
                lang: {$langJs}{$colorJs}
            }).then(function (obj) {
                vaptchaObj = obj;
                window.vaptchaObj = obj;
                ready = true;
            }).catch(function () {
                ready = false;
                vaptchaObj = null;
            });
        }
    
        function doValidate() {
            if (checking) return;
    
            if (!ready || !vaptchaObj) {
                setButton(BTN_IDLE, '');
                return;
            }
    
            checking = true;
            if (verifyBtn) verifyBtn.disabled = true;
            setButton(BTN_LOADING, '');
    
            vaptchaObj.validate().then(function (result) {
                checking = false;
                if (verifyBtn) verifyBtn.disabled = false;
    
                if (!result || !result.token || !result.knock) {
                    // 刷新验证图 / 关闭浮层 → 视为取消，不是失败
                    setButton(BTN_IDLE, '');
                    return;
                }
    
                setField('vaptcha_token', result.token || '');
                setField('vaptcha_knock', result.knock || '');
                setField('vaptcha_dfu', result.dfu || '');
                setField('vaptcha_ip', result.ip || '');
    
                passed = true;
                form.setAttribute('data-vaptcha-checked', '1');
                setButton(BTN_PASSED, 'passed');
            }).catch(function () {
                checking = false;
                if (verifyBtn) verifyBtn.disabled = false;
                setButton(BTN_IDLE, '');
            });
        }
    
        if (verifyBtn) verifyBtn.addEventListener('click', doValidate);
    
        form.addEventListener('submit', function (e) {
            if (form.getAttribute('data-vaptcha-checked') === '1') return;
    
            e.preventDefault();
    
            if (!passed) {
                doValidate();
            }
        });
    
        init();
    })();
    </script>
JS;
    }

    /**
     * 输出挂载容器
     */
   public function html()
    {
        $styles = <<<CSS
    .vaptcha-demo{margin:10px 0}
    .vaptcha-demo .demo-action-row{position:relative;z-index:3;display:flex;width:100%;height:34px;border-radius:6px;overflow:hidden;background:#2f78ff;box-shadow:0 4px 10px rgba(47,120,255,.16);transition:background .26s ease,box-shadow .26s ease}
    .vaptcha-demo .demo-action-row.is-passed{background:#10b96c;box-shadow:0 4px 10px rgba(16,185,108,.18)}
    .vaptcha-demo .demo-verify-button{flex:1;height:34px;color:#fff;border:0;background:transparent;cursor:pointer;font-weight:700;font-size:13px;line-height:34px;-webkit-tap-highlight-color:transparent;transition:background .2s ease,opacity .2s ease}
    .vaptcha-demo .demo-verify-button:disabled{cursor:not-allowed;opacity:.58}
    .vaptcha-demo .demo-verify-button:not(:disabled):hover{background:rgba(255,255,255,.1)}
    .vaptcha-demo .vaptcha-mount{position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;overflow:visible}
    CSS;
    
        return <<<HTML
    <style>{$styles}</style>
    <div class="vaptcha-demo">
        <div class="demo-action-row" id="vaptcha-action-row">
            <button class="demo-verify-button" id="vaptcha-verify-btn" type="button">点击完成人机验证</button>
        </div>
        <div class="vaptcha-mount" id="vaptchaContainer" aria-hidden="true"></div>
    </div>
HTML;
    }

    /**
     * 本地二次校验（官方推荐方式一）
     *
     * @param string $token 前端返回的 token
     * @param string $knock 前端返回的 knock
     * @param string $dfu   前端返回的 dfu
     * @param string $ip    前端返回的签名快照 IP（必须原样使用）
     * @return bool
     */
    public function checkVaptcha($token, $knock, $dfu, $ip)
    {
        $vkey = iro_opt('vaptcha_key');
        if (empty($vkey)) {
            return false;
        }

        if (!$this->verifyToken($token, $vkey, $knock, $dfu, $ip, self::TOKEN_TTL)) {
            return false;
        }

        // 防复用：同一个 token 只放行一次
        $cacheKey = 'vaptcha_used_' . md5($token);
        if (get_transient($cacheKey)) {
            return false;
        }
        set_transient($cacheKey, 1, 30);

        return true;
    }

    /**
     * 本地验签：
     * token 格式：timestamp.token_id.signature
     * signature = hex(HMAC-SHA256(timestamp.ip.dfu.knock, vkey))
     *
     * @return bool
     */
    public function verifyToken($token, $vkey, $knock = '', $dfu = '', $ip = '', $ttlSeconds = 3)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($timestamp, $tokenId, $signature) = $parts;

        if (!is_numeric($timestamp) || $tokenId === '' || $signature === '') {
            return false;
        }

        if (abs(time() - intval($timestamp)) > $ttlSeconds) {
            return false;
        }

        $data     = $timestamp . '.' . $ip . '.' . $dfu . '.' . $knock;
        $expected = hash_hmac('sha256', $data, $vkey);

        return hash_equals(strtolower($expected), strtolower($signature));
    }

    /**
     * 官方 verify 接口（可选方式二）
     *
     * POST https://v41.vaptcha.com/api/verify
     * 返回：{ code, msg, data: { code, note, result, vid } }
     *
     * @return array{success: bool, code: int, msg: string}
     */
    public function checkVaptchaRemote($token, $knock, $dfu, $ip)
    {
        $response = wp_remote_post('https://v41.vaptcha.com/api/verify', [
            'timeout' => 15,
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => wp_json_encode([
                'vid'   => iro_opt('vaptcha_vid'),
                'vkey'  => iro_opt('vaptcha_key'),
                'token' => $token,
                'knock' => $knock,
                'dfu'   => $dfu,
                'ip'    => $ip,
            ]),
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'code' => -1, 'msg' => $response->get_error_message()];
        }

        $body = json_decode(wp_remote_retrieve_body($response));
        if (!$body) {
            return ['success' => false, 'code' => -1, 'msg' => '远程验证接口响应异常'];
        }

        $data = isset($body->data) ? $body->data : null;
        if (!$data) {
            return ['success' => false, 'code' => -1, 'msg' => isset($body->msg) ? $body->msg : '远程验证失败'];
        }

        // 严格通过：data.result = true 且 data.code = 0
        $passed = (!empty($data->result)) && ((int) $data->code === 0);

        return [
            'success' => $passed,
            'code'    => (int) $data->code,
            'msg'     => isset($data->note) ? $data->note : '',
        ];
    }

    /**
     * 输出 JavaScript 安全字符串
     *
     * @param mixed $value
     * @return string
     */
    private function js($value)
    {
        return json_encode((string) $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
