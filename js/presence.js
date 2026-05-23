(function () {
  'use strict';

  if (typeof _iro === 'undefined' || !_iro.presence_enabled) {
    return;
  }

  var COOKIE_NAME = 'sakurairo_presence_id';
  var root = document.getElementById('footer-online-count');
  if (!root) {
    return;
  }

  var countEl = root.querySelector('.count');
  var dotEl = root.querySelector('.presence-dot');
  var connLabel = document.querySelector('#footer-presence-help-panel .presence-connection-label');
  var pollTimer = null;
  var eventSource = null;
  var hiddenIntervalMultiplier = 3;

  var labels = _iro.presence_labels || {};
  var intervalMs = Math.max(3000, (_iro.presence_interval || 5) * 1000);
  var useSse = !!_iro.presence_use_sse;

  var presenceId = getCookie(COOKIE_NAME);

  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : '';
  }

  function setCookie(name, value, maxAge) {
    var secure = location.protocol === 'https:' ? '; Secure' : '';
    document.cookie =
      name +
      '=' +
      encodeURIComponent(value) +
      '; path=/; max-age=' +
      maxAge +
      '; SameSite=Lax' +
      secure;
  }

  function setStatus(status) {
    if (dotEl) {
      dotEl.setAttribute('data-status', status);
    }
    if (connLabel) {
      connLabel.setAttribute('data-status', status);
      if (status === 'connected') {
        connLabel.textContent = labels.connected || '已连接';
      } else if (status === 'reconnecting' || status === 'connecting') {
        connLabel.textContent = labels.connecting || '连接中…';
      } else if (status === 'error') {
        connLabel.textContent = labels.error || '已断开';
      }
    }
  }

  function updateCount(n) {
    if (countEl && typeof n === 'number' && !isNaN(n)) {
      countEl.textContent = String(n);
    }
  }

  function apiUrl(path, query) {
    var base = (_iro.iro_api || '').replace(/\/$/, '');
    var url = base + path;
    if (query) {
      var parts = [];
      Object.keys(query).forEach(function (key) {
        if (query[key] !== undefined && query[key] !== null && query[key] !== '') {
          parts.push(encodeURIComponent(key) + '=' + encodeURIComponent(query[key]));
        }
      });
      if (parts.length) {
        url += (url.indexOf('?') > -1 ? '&' : '?') + parts.join('&');
      }
    }
    return url;
  }

  function applyPayload(data) {
    if (data && data.presence_id) {
      presenceId = data.presence_id;
      setCookie(COOKIE_NAME, presenceId, (data.ttl || 90) + 60);
    }
    if (data && typeof data.count === 'number') {
      updateCount(data.count);
    }
    setStatus('connected');
    return data;
  }

  function restFetch(url, options) {
    options = options || {};
    var method = (options.method || 'GET').toUpperCase();
    var headers = options.headers ? Object.assign({}, options.headers) : {};

    if (method !== 'GET' && method !== 'HEAD') {
      headers['Content-Type'] = 'application/json';
    }
    if (_iro.nonce) {
      headers['X-WP-Nonce'] = _iro.nonce;
    }

    return fetch(url, {
      method: method,
      credentials: 'same-origin',
      headers: headers,
      body: options.body ? JSON.stringify(options.body) : undefined,
    }).then(function (res) {
      if (!res.ok) {
        throw new Error('Request failed: HTTP ' + res.status);
      }
      return res.json();
    });
  }

  function ping() {
    var payload = { presence_id: presenceId || undefined };

    return restFetch(apiUrl('/presence/ping'), {
      method: 'POST',
      body: payload,
    })
      .then(applyPayload)
      .catch(function () {
        return restFetch(
          apiUrl('/presence/ping', { presence_id: presenceId || undefined }),
          { method: 'GET' }
        ).then(applyPayload);
      });
  }

  function fetchCount() {
    return restFetch(apiUrl('/presence/count')).then(function (data) {
      if (typeof data.count === 'number') {
        updateCount(data.count);
      }
      setStatus('connected');
      return data;
    });
  }

  function leaveBeacon() {
    if (!presenceId || !navigator.sendBeacon) {
      return;
    }
    var url =
      apiUrl('/presence/leave') +
      '?presence_id=' +
      encodeURIComponent(presenceId);
    try {
      navigator.sendBeacon(url);
    } catch (e) {
      /* ignore */
    }
  }

  function stopPolling() {
    if (pollTimer) {
      clearInterval(pollTimer);
      pollTimer = null;
    }
  }

  function stopSse() {
    if (eventSource) {
      eventSource.close();
      eventSource = null;
    }
  }

  function startPolling(multiplier) {
    stopPolling();
    multiplier = multiplier || 1;
    pollTimer = setInterval(function () {
      ping().catch(onError);
    }, intervalMs * multiplier);
  }

  function onError() {
    setStatus('reconnecting');
    fetchCount().catch(function () {
      setStatus('error');
    });
  }

  function startSse(multiplier) {
    stopSse();
    stopPolling();
    multiplier = multiplier || 1;
    pollTimer = setInterval(function () {
      ping().catch(onError);
    }, intervalMs * multiplier);
    try {
      eventSource = new EventSource(apiUrl('/presence/stream'));
      eventSource.onmessage = function (ev) {
        try {
          var data = JSON.parse(ev.data);
          if (typeof data.count === 'number') {
            updateCount(data.count);
          }
          setStatus('connected');
        } catch (e) {
          /* ignore parse errors */
        }
      };
      eventSource.onerror = function () {
        stopSse();
        setStatus('reconnecting');
      };
      ping().catch(onError);
    } catch (e) {
      startPolling(multiplier);
      ping().catch(onError);
    }
  }

  function init() {
    setStatus('connecting');

    ping()
      .then(function () {
        if (useSse && typeof EventSource !== 'undefined') {
          startSse(1);
        } else {
          startPolling(1);
        }
      })
      .catch(function () {
        setStatus('reconnecting');
        startPolling(1);
        fetchCount().catch(function () {
          setStatus('error');
        });
      });

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        stopSse();
        if (useSse && typeof EventSource !== 'undefined') {
          startSse(hiddenIntervalMultiplier);
        } else {
          startPolling(hiddenIntervalMultiplier);
        }
      } else {
        ping().catch(onError);
        if (useSse && typeof EventSource !== 'undefined') {
          startSse(1);
        } else {
          startPolling(1);
        }
      }
    });

    window.addEventListener('beforeunload', leaveBeacon);
    window.addEventListener('pagehide', leaveBeacon);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
