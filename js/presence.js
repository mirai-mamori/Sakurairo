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
  var connLabel = root.querySelector('.presence-connection-label');
  var helpBtn = root.querySelector('.presence-help');
  var helpPanel = root.getElementById('footer-presence-help-panel');

  var presenceId = getCookie(COOKIE_NAME);
  var pollTimer = null;
  var eventSource = null;
  var hiddenIntervalMultiplier = 3;

  var labels = _iro.presence_labels || {};
  var intervalMs = Math.max(3000, (_iro.presence_interval || 5) * 1000);
  var useSse = !!_iro.presence_use_sse;

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
        connLabel.textContent = labels.connected || 'Connected';
      } else if (status === 'reconnecting' || status === 'connecting') {
        connLabel.textContent = labels.connecting || 'Connecting…';
      } else if (status === 'error') {
        connLabel.textContent = labels.error || 'Disconnected';
      }
    }
  }

  function updateCount(n) {
    if (countEl && typeof n === 'number' && !isNaN(n)) {
      countEl.textContent = String(n);
    }
  }

  function apiUrl(path) {
    var base = (_iro.iro_api || '').replace(/\/$/, '');
    return base + path;
  }

  function restFetch(url, options) {
    options = options || {};
    var headers = options.headers || {};
    headers['Content-Type'] = 'application/json';
    if (_iro.nonce) {
      headers['X-WP-Nonce'] = _iro.nonce;
    }
    return fetch(url, {
      method: options.method || 'GET',
      credentials: 'same-origin',
      headers: headers,
      body: options.body ? JSON.stringify(options.body) : undefined,
    }).then(function (res) {
      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }
      return res.json();
    });
  }

  function ping() {
    return restFetch(apiUrl('/presence/ping'), {
      method: 'POST',
      body: { presence_id: presenceId || undefined },
    }).then(function (data) {
      if (data.presence_id) {
        presenceId = data.presence_id;
        var ttl = data.ttl || 90;
        setCookie(COOKIE_NAME, presenceId, ttl + 60);
      }
      if (typeof data.count === 'number') {
        updateCount(data.count);
      }
      setStatus('connected');
      return data;
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
    var ms = intervalMs * multiplier;
    pollTimer = setInterval(function () {
      ping().catch(onError);
    }, ms);
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
    setStatus('connecting');
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
        startPolling(1);
      };
      ping().catch(onError);
    } catch (e) {
      startPolling(multiplier);
      ping().catch(onError);
    }
  }

  function initHelp() {
    if (!helpBtn || !helpPanel) {
      return;
    }
    helpBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = helpPanel.hasAttribute('hidden');
      if (open) {
        helpPanel.removeAttribute('hidden');
        helpBtn.setAttribute('aria-expanded', 'true');
      } else {
        helpPanel.setAttribute('hidden', '');
        helpBtn.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('click', function (e) {
      if (!root.contains(e.target)) {
        helpPanel.setAttribute('hidden', '');
        helpBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function init() {
    setStatus('connecting');
    initHelp();

    ping()
      .then(function () {
        if (useSse && typeof EventSource !== 'undefined') {
          startSse();
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
