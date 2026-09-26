/**
 * ============================================================================
 * COOKIE CONSENT — script reutilizable, sin dependencias, cumple RGPD/LSSI
 * ============================================================================
 * Uso básico (justo antes de </body>):
 *
 *   <link rel="stylesheet" href="/assets/css/cookie-consent.css">
 *   <script src="/assets/js/cookie-consent.js"></script>
 *   <script>
 *     CookieConsent.init({
 *       lang: 'es',                 // 'es' | 'en' | auto (detecta navegador)
 *       policyUrl: '/es/politica-cookies',
 *       cookieDomain: '.ikusa.net', // opcional, para compartir consentimiento entre subdominios
 *       onConsentChange: function (consent) {
 *         if (consent.analytics) { /* cargar GA4, etc. *\/ }
 *         if (consent.marketing) { /* cargar Meta Pixel, etc. *\/ }
 *       }
 *     });
 *   </script>
 *
 * Para bloquear scripts de terceros hasta que haya consentimiento, en vez de:
 *   <script src="https://www.googletagmanager.com/gtag/js"></script>
 * usar:
 *   <script type="text/plain" data-cookie-category="analytics"
 *           data-cookie-src="https://www.googletagmanager.com/gtag/js"></script>
 * CookieConsent los activa automáticamente cuando el usuario acepta esa categoría.
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  // ==========================================================================
  // 1. TEXTOS / TRADUCCIONES — añade aquí más idiomas (ej. 'eu') si hace falta
  // ==========================================================================
  var I18N = {
    es: {
      bannerTitle: 'Utilizamos cookies',
      bannerText:
        'Usamos cookies propias y de terceros para el funcionamiento del sitio, ' +
        'analizar el tráfico y personalizar contenido. Puedes aceptar todas, ' +
        'rechazar las no necesarias o configurar tus preferencias.',
      btnAcceptAll: 'Aceptar todo',
      btnRejectAll: 'Rechazar todo',
      btnPreferences: 'Configurar',
      btnSave: 'Guardar preferencias',
      btnBack: 'Volver',
      policyLinkText: 'Más información',
      modalTitle: 'Preferencias de cookies',
      modalIntro:
        'Elige qué tipos de cookies quieres permitir. Puedes cambiar esta ' +
        'decisión en cualquier momento desde el enlace "Cookies" del pie de página.',
      alwaysActive: 'Siempre activas',
      categories: {
        necessary: {
          title: 'Necesarias',
          desc: 'Imprescindibles para que la web funcione correctamente (navegación, seguridad, formularios). No se pueden desactivar.'
        },
        analytics: {
          title: 'Analíticas',
          desc: 'Nos permiten medir visitas y fuentes de tráfico para mejorar el rendimiento del sitio (ej. Google Analytics).'
        },
        marketing: {
          title: 'Marketing',
          desc: 'Utilizadas para mostrar publicidad relevante y medir la eficacia de campañas (ej. Meta Pixel, Google Ads).'
        },
        preferences: {
          title: 'Preferencias',
          desc: 'Recuerdan opciones como idioma o región para personalizar tu experiencia.'
        }
      }
    },
    en: {
      bannerTitle: 'We use cookies',
      bannerText:
        'We use first-party and third-party cookies to run the site, analyze ' +
        'traffic and personalize content. You can accept all, reject non-essential ' +
        'cookies, or manage your preferences.',
      btnAcceptAll: 'Accept all',
      btnRejectAll: 'Reject all',
      btnPreferences: 'Manage preferences',
      btnSave: 'Save preferences',
      btnBack: 'Back',
      policyLinkText: 'Learn more',
      modalTitle: 'Cookie preferences',
      modalIntro:
        'Choose which types of cookies you want to allow. You can change this ' +
        'at any time via the "Cookies" link in the footer.',
      alwaysActive: 'Always active',
      categories: {
        necessary: {
          title: 'Necessary',
          desc: 'Required for the site to function properly (navigation, security, forms). Cannot be disabled.'
        },
        analytics: {
          title: 'Analytics',
          desc: 'Let us measure visits and traffic sources to improve site performance (e.g. Google Analytics).'
        },
        marketing: {
          title: 'Marketing',
          desc: 'Used to show relevant ads and measure campaign performance (e.g. Meta Pixel, Google Ads).'
        },
        preferences: {
          title: 'Preferences',
          desc: 'Remember choices like language or region to personalize your experience.'
        }
      }
    }
  };

  // ==========================================================================
  // 2. CONFIGURACIÓN POR DEFECTO — sobreescribible en CookieConsent.init(opts)
  // ==========================================================================
  var DEFAULTS = {
    lang: null,                    // null = autodetecta con navigator.language
    cookieName: 'cookie_consent',
    cookieDays: 365,               // caducidad del consentimiento (RGPD recomienda revalidar ~13 meses)
    cookieDomain: '',              // ej. '.ikusa.net' para compartir entre subdominios
    cookiePath: '/',
    policyUrl: '/politica-cookies',
    categories: ['necessary', 'analytics', 'marketing', 'preferences'],
    requiredCategories: ['necessary'], // siempre true, no editables por el usuario
    position: 'bottom',            // 'bottom' | 'top'
    onConsentChange: function () {}
  };

  var state = {
    config: null,
    t: null,               // textos del idioma activo
    consent: null,
    bannerEl: null,
    modalEl: null
  };

  // ==========================================================================
  // 3. FUNCIONES DE COOKIES (genéricas, sin dependencias)
  // ==========================================================================
  function setCookie(name, value, days, domain, path) {
    var expires = '';
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = '; expires=' + date.toUTCString();
    }
    var domainStr = domain ? '; domain=' + domain : '';
    document.cookie =
      name + '=' + encodeURIComponent(value) + expires +
      '; path=' + (path || '/') + domainStr + '; SameSite=Lax';
  }

  function getCookie(name) {
    var match = document.cookie.match(
      new RegExp('(^|;\\s*)' + name.replace(/[-.]/g, '\\$&') + '=([^;]*)')
    );
    return match ? decodeURIComponent(match[2]) : null;
  }

  function deleteCookie(name, domain, path) {
    setCookie(name, '', -1, domain, path);
  }

  // ==========================================================================
  // 4. NÚCLEO DE CONSENTIMIENTO
  // ==========================================================================
  function detectLang(cfgLang) {
    if (cfgLang && I18N[cfgLang]) return cfgLang;
    var nav = (navigator.language || 'es').slice(0, 2).toLowerCase();
    return I18N[nav] ? nav : 'es';
  }

  function readStoredConsent() {
    var raw = getCookie(state.config.cookieName);
    if (!raw) return null;
    try {
      var parsed = JSON.parse(raw);
      if (parsed && typeof parsed === 'object' && parsed.categories) return parsed;
      return null;
    } catch (e) {
      return null;
    }
  }

  function buildConsentObject(categoriesState) {
    var obj = {};
    state.config.categories.forEach(function (cat) {
      obj[cat] = state.config.requiredCategories.indexOf(cat) !== -1
        ? true
        : !!categoriesState[cat];
    });
    return {
      categories: obj,
      timestamp: new Date().toISOString(),
      version: 1
    };
  }

  function persistConsent(consentObj) {
    setCookie(
      state.config.cookieName,
      JSON.stringify(consentObj),
      state.config.cookieDays,
      state.config.cookieDomain,
      state.config.cookiePath
    );
    state.consent = consentObj;
  }

  function applyConsent(consentObj) {
    persistConsent(consentObj);
    activateGatedScripts(consentObj.categories);
    document.dispatchEvent(
      new CustomEvent('cookieconsent:updated', { detail: consentObj.categories })
    );
    state.config.onConsentChange(consentObj.categories);
    hideBanner();
    hideModal();
  }

  // Activa <script type="text/plain" data-cookie-category="...">
  // una vez que el usuario dio consentimiento para esa categoría.
  function activateGatedScripts(categories) {
    var nodes = document.querySelectorAll('script[data-cookie-category]');
    nodes.forEach(function (node) {
      var cat = node.getAttribute('data-cookie-category');
      if (!categories[cat] || node.getAttribute('data-cookie-activated') === '1') return;

      var newScript = document.createElement('script');
      var src = node.getAttribute('data-cookie-src');
      if (src) {
        newScript.src = src;
      } else {
        newScript.text = node.textContent;
      }
      // copiar atributos adicionales (async, defer, id, etc.)
      Array.prototype.slice.call(node.attributes).forEach(function (attr) {
        if (['type', 'data-cookie-category', 'data-cookie-src'].indexOf(attr.name) === -1) {
          newScript.setAttribute(attr.name, attr.value);
        }
      });
      node.setAttribute('data-cookie-activated', '1');
      node.parentNode.insertBefore(newScript, node.nextSibling);
    });
  }

  // ==========================================================================
  // 5. VISTA — banner + modal de preferencias (HTML generado por JS, sin build tools)
  // ==========================================================================
  function renderBanner() {
    var t = state.t;
    var el = document.createElement('div');
    el.className = 'cc-banner cc-banner--' + state.config.position;
    el.setAttribute('role', 'dialog');
    el.setAttribute('aria-live', 'polite');
    el.setAttribute('aria-label', t.bannerTitle);

    el.innerHTML =
      '<div class="cc-banner__inner">' +
        '<div class="cc-banner__text">' +
          '<p class="cc-banner__title">' + t.bannerTitle + '</p>' +
          '<p class="cc-banner__desc">' + t.bannerText + ' ' +
            '<a href="' + state.config.policyUrl + '" class="cc-banner__link">' + t.policyLinkText + '</a>' +
          '</p>' +
        '</div>' +
        '<div class="cc-banner__actions">' +
          '<button type="button" class="cc-btn cc-btn--ghost" data-cc-action="preferences">' + t.btnPreferences + '</button>' +
          '<button type="button" class="cc-btn cc-btn--outline" data-cc-action="reject">' + t.btnRejectAll + '</button>' +
          '<button type="button" class="cc-btn cc-btn--solid" data-cc-action="accept">' + t.btnAcceptAll + '</button>' +
        '</div>' +
      '</div>';

    document.body.appendChild(el);
    state.bannerEl = el;

    el.querySelector('[data-cc-action="accept"]').addEventListener('click', acceptAll);
    el.querySelector('[data-cc-action="reject"]').addEventListener('click', rejectAll);
    el.querySelector('[data-cc-action="preferences"]').addEventListener('click', showPreferences);
  }

  function renderModal() {
    var t = state.t;
    var current = state.consent ? state.consent.categories : {};

    var el = document.createElement('div');
    el.className = 'cc-modal';
    el.setAttribute('role', 'dialog');
    el.setAttribute('aria-modal', 'true');
    el.setAttribute('aria-label', t.modalTitle);

    var rows = state.config.categories.map(function (cat) {
      var meta = t.categories[cat];
      if (!meta) return '';
      var isRequired = state.config.requiredCategories.indexOf(cat) !== -1;
      var checked = isRequired || current[cat] ? 'checked' : '';
      var disabled = isRequired ? 'disabled' : '';
      return (
        '<div class="cc-modal__row">' +
          '<div class="cc-modal__row-header">' +
            '<span class="cc-modal__row-title">' + meta.title + '</span>' +
            (isRequired
              ? '<span class="cc-modal__badge">' + t.alwaysActive + '</span>'
              : '<label class="cc-switch">' +
                  '<input type="checkbox" data-cc-category="' + cat + '" ' + checked + ' ' + disabled + '>' +
                  '<span class="cc-switch__slider"></span>' +
                '</label>') +
          '</div>' +
          '<p class="cc-modal__row-desc">' + meta.desc + '</p>' +
        '</div>'
      );
    }).join('');

    el.innerHTML =
      '<div class="cc-modal__backdrop" data-cc-action="close"></div>' +
      '<div class="cc-modal__panel">' +
        '<h2 class="cc-modal__title">' + t.modalTitle + '</h2>' +
        '<p class="cc-modal__intro">' + t.modalIntro + '</p>' +
        '<div class="cc-modal__list">' + rows + '</div>' +
        '<div class="cc-modal__actions">' +
          '<button type="button" class="cc-btn cc-btn--outline" data-cc-action="reject">' + t.btnRejectAll + '</button>' +
          '<button type="button" class="cc-btn cc-btn--solid" data-cc-action="save">' + t.btnSave + '</button>' +
        '</div>' +
      '</div>';

    document.body.appendChild(el);
    state.modalEl = el;

    el.querySelector('[data-cc-action="close"]').addEventListener('click', hideModal);
    el.querySelector('[data-cc-action="reject"]').addEventListener('click', rejectAll);
    el.querySelector('[data-cc-action="save"]').addEventListener('click', function () {
      var categoriesState = {};
      el.querySelectorAll('[data-cc-category]').forEach(function (input) {
        categoriesState[input.getAttribute('data-cc-category')] = input.checked;
      });
      applyConsent(buildConsentObject(categoriesState));
    });
  }

  function showBanner() {
    if (!state.bannerEl) renderBanner();
    state.bannerEl.classList.add('cc-banner--visible');
  }
  function hideBanner() {
    if (state.bannerEl) state.bannerEl.classList.remove('cc-banner--visible');
  }
  function showPreferences() {
    if (!state.modalEl) renderModal();
    state.modalEl.classList.add('cc-modal--visible');
  }
  function hideModal() {
    if (state.modalEl) state.modalEl.classList.remove('cc-modal--visible');
  }

  function acceptAll() {
    var all = {};
    state.config.categories.forEach(function (c) { all[c] = true; });
    applyConsent(buildConsentObject(all));
  }
  function rejectAll() {
    var none = {};
    state.config.categories.forEach(function (c) { none[c] = false; });
    applyConsent(buildConsentObject(none));
  }

  // ==========================================================================
  // 6. API PÚBLICA
  // ==========================================================================
  var CookieConsent = {
    init: function (options) {
      state.config = Object.assign({}, DEFAULTS, options || {});
      state.config.lang = detectLang(state.config.lang);
      state.t = I18N[state.config.lang];

      var stored = readStoredConsent();
      if (stored) {
        state.consent = stored;
        activateGatedScripts(stored.categories);
        document.dispatchEvent(new CustomEvent('cookieconsent:updated', { detail: stored.categories }));
        state.config.onConsentChange(stored.categories);
      } else {
        showBanner();
      }

      // Enlace reutilizable en el footer: <a href="#" data-cookie-settings>Cookies</a>
      document.querySelectorAll('[data-cookie-settings]').forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          showPreferences();
        });
      });
    },
    // API para consultar/forzar consentimiento desde otros scripts de la web
    hasConsent: function (category) {
      return !!(state.consent && state.consent.categories && state.consent.categories[category]);
    },
    getConsent: function () {
      return state.consent ? state.consent.categories : null;
    },
    acceptAll: acceptAll,
    rejectAll: rejectAll,
    showPreferences: showPreferences,
    // Permite reabrir el banner / reset (ej. tras cambio de política de cookies)
    reset: function () {
      deleteCookie(state.config.cookieName, state.config.cookieDomain, state.config.cookiePath);
      state.consent = null;
      showBanner();
    }
  };

  window.CookieConsent = CookieConsent;
})(window, document);
