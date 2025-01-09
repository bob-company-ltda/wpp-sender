jQuery(document).ready(function (e) {
  var t = window.location.pathname
      .toLowerCase()
      .includes('whatsapp-api-pricing-brazil')
      ? '#getStartModal--port'
      : '#getStartModal--eng',
    i = '';
  function n(e) {
    var t = { method: 'POST', body: JSON.stringify(e) };
    fetch(`${wati_wp_env.billing_service_url}/api/v2/chargebee/etmlLog`, t);
  }
  function o(e) {
    var t, i;
    return (
      (t = e),
      (i = window.location.search),
      new URLSearchParams(i).get(t) ??
        (function e(t) {
          for (var i = document.cookie.split(';'), n = 0; n < i.length; n++) {
            var o = i[n].trim();
            if (o.startsWith(`${t}=`)) return o.substring(`${t}=`.length);
          }
          return null;
        })(e)
    );
  }
  if (
    (e('.price_btn').on('click', function () {
      e(this).attr('data-plan') &&
        ((i = e(this).attr('data-plan')),
        e('.getStartCheckAgree').prop('checked', !1),
        e('.getstart-btn').css('display', 'none'),
        e('.getstart-btn').prop('disabled', !0),
        e('[data-cb-item-0=' + e(this).attr('data-plan') + ']').css(
          'display',
          'block'
        ),
        e(t).modal());
    }),
    e('.getStartCheckAgree').on('click', function () {
      e(this).prop('checked')
        ? e('.getstart-btn').prop('disabled', !1)
        : e('.getstart-btn').prop('disabled', !0);
    }),
    window.Chargebee)
  ) {
    var a = Chargebee.getInstance(),
      r = (function e() {
        for (
          var t,
            i = [],
            n = window.location.href
              .slice(window.location.href.indexOf('?') + 1)
              .split('&'),
            o = 0;
          o < n.length;
          o++
        )
          (t = n[o].split('=')), i.push(t[0]), (i[t[0]] = t[1]);
        return i;
      })().discountCode;
    r &&
      e('[data-cb-type=checkout]').each((e) => {
        var t = document.querySelectorAll('[data-cb-type=checkout]')[e];
        a.getProduct(t).addCoupon(r);
      }),
      a.setCheckoutCallbacks(function (i) {
        return {
          success: function (a) {
            (function i(a, r) {
              e('#cb-container').css('display', 'none'),
                e(t).modal('hide'),
                e('#waiting-redirect').modal(),
                e(window).bind('beforeunload', function () {
                  return 'Please wait until payment complete';
                });
              var c = {
                url: `${wati_wp_env.billing_service_url}/api/v2/chargebee/getSubscriptionIdFromHostedPageId?hpId=${a}`,
                method: 'GET',
                timeout: 0,
              };
              function s(t = null) {
                e('#waiting-redirect').modal('hide'),
                  e('#error-redirect').modal(),
                  e(window).off('beforeunload'),
                  e('#button-help-new-WATI-setup').click(() => {
                    window.location.href =
                      'https://wa.me/19285998010?text=I%20was%20not%20able%20to%20access%20my%20WATI%20account%20after%20completing%20my%20payment.';
                  });
              }
              function ps(f, l, e, a, c) {
                if(typeof(growsumo) !== "undefined" && growsumo !== null){
                  console.log("Registering on Partnerstack - Started"),
                  growsumo.data.name = f + " " + l,
                  growsumo.data.email = e,
                  growsumo.data.customer_key = e,
                  growsumo.data.amount = a,
                  growsumo.data.currency = c,
                  growsumo.createSignup(),
                  console.log("Registering on Partnerstack - Completed")
                }
              }
              e.ajax(c)
                .done(function (t) {
                  var i = '',
                    a = '-',
                    c = 0;
                  if (
                    (t
                      ? ((i = t.clientEmail),
                        (a = t.subscriptionId || 'not available'),
                        ps(t.clientFirstName, t.clientLastName, t.clientEmail, t.revenue, t.currencyCode))
                      : s(),
                    t && t.subscriptionId)
                  ) {
                    t.invoiceId &&
                      ((c = t.amount / 100),
                      console.log(
                        'chargebee return data',
                        c,
                        t.currencyCode,
                        t.invoiceId
                      ),
                      window.tap('conversion', t.invoiceId, c, {
                        customer_id: t.customerId,
                        currency: t.currencyCode,
                      }),
                      r && r[0] && r[0].items && r[0].items[0]
                        ? window.dataLayer.push({
                            event: 'transactionComplete',
                            ecommerce: {
                              currencyCode: t.currencyCode,
                              actionField: {
                                id: t.subscriptionId,
                                revenue: c,
                                tax: 0,
                                shipping: 0,
                              },
                              products: [
                                {
                                  name: r[0].items[0].item_price_id,
                                  id: r[0].items[0].item_price_id,
                                  price: c,
                                  category: r[0].items[0].item_price_id,
                                  quantity: r[0].items[0].quantity,
                                },
                              ],
                            },
                          })
                        : console.log("can't get product item", r)),
                      (d = t.customerId),
                      (u = t.invoiceId),
                      (l = c),
                      console.log('loadETMedialabsIntegrationScript'),
                      (p = window),
                      (m = document),
                      (g = 'script'),
                      (f = 'ga'),
                      (p.GoogleAnalyticsObject = f),
                      (p.ga =
                        p.ga ||
                        function () {
                          (p.ga.q = p.ga.q || []).push(arguments);
                        }),
                      (p.ga.l = 1 * new Date()),
                      (h = m.createElement(g)),
                      (b = m.getElementsByTagName(g)[0]),
                      (h.async = 1),
                      (h.src = 'https://www.googleanalytics.com/analytics.js'),
                      b.parentNode.insertBefore(h, b),
                      ga('create', 'UA-153526598-1', 'auto', 'ei'),
                      (y = ($ = document.cookie.match(
                        '(^|;)\\s*_fbp\\s*=\\s*([^;]+)'
                      ))
                        ? $.pop()
                        : ''),
                      (I = (v = document.cookie.match(
                        '(^|;)\\s*_fbc\\s*=\\s*([^;]+)'
                      ))
                        ? v.pop()
                        : ''),
                      ga('ei.set', 'sendHitTask', function (e) {
                        var t = new XMLHttpRequest();
                        t.open(
                          'POST',
                          'https://d.easyinsights.in/ga/f5926dnh6cufbyg20txhsype037maarh',
                          !0
                        ),
                          t.send(JSON.stringify({ q: e.get('hitPayload') }));
                      }),
                      ga('ei.set', 'dimension16', d),
                      ga('ei.set', 'dimension17', y),
                      ga('ei.set', 'dimension18', u),
                      ga('ei.set', 'dimension19', l),
                      ga('ei.set', 'dimension20', I),
                      ga('ei.send', 'pageview'),
                      console.log('loadETMedialabsIntegrationScript', 'finish');
                    var d,
                      u,
                      l,
                      p,
                      m,
                      g,
                      f,
                      h,
                      b,
                      $,
                      y,
                      v,
                      I,
                      w,
                      C,
                      _ = new Date();
                    (function e(t) {
                      fetch('https://data.adbytzz.com/ecommerce/', {
                        method: 'POST',
                        timeout: 0,
                        mode: 'no-cors',
                        body: JSON.stringify(t),
                      })
                        .then((e) => e.text())
                        .then((e) => {
                          n({ success: !0, data: t, responseText: e });
                        })
                        .catch((e) => {
                          n({ success: !1, error: e });
                        });
                    })({
                      key: 'testtopic',
                      timestamp: parseInt(_.getTime() / 1e3),
                      entity_id: a,
                      userid: t.customerId,
                      event: 'new',
                      revenue: t.amount,
                      currency: t.currencyCode,
                      discount: t.discount,
                      offer_code: t.offerCode,
                      attributes: { attribute01: t.country },
                      email_sha256: t.emailSha256,
                      phone_sha256: t.phoneSha256,
                    }),
                      (w = {
                        timestamp: Date.parse(_),
                        entity_id: a,
                        userid: t.customerId,
                        event: 'new',
                        revenue: t.amount,
                        currency: t.currencyCode,
                        discount: t.discount,
                        offer_code: t.offerCode,
                        gclid: o('gclid'),
                        fclid: o('fbclid'),
                        ts: _.toString(),
                      }),
                      (C = {
                        url: `${wati_wp_env.ads_service_url}/trigger`,
                        method: 'POST',
                        timeout: 0,
                        headers: { 'Content-Type': 'application/json' },
                        data: JSON.stringify(w),
                      }),
                      e.ajax(C).done(function (e) {
                        console.log('sendAdsEvent', e);
                      });
                    var k = `${wati_wp_env.billing_service_url}/api/v2/cloudApiAndWatiProvision/getIsRedirectToEmptyWatiEnabled`,
                      S = `${wati_wp_env.wati_url}/payment?subscriptionId=${t.subscriptionId}&setPasswordToken=${t.setPasswordToken}`;
                    e.ajax(k)
                      .done(function (i) {
                        e(window).off('beforeunload'),
                          i
                            ? (window.location.href = `${wati_wp_env.wati_url}/paymentsuccess?subscriptionId=${t.subscriptionId}&setPasswordToken=${t.setPasswordToken}`)
                            : (window.location.href = S);
                      })
                      .fail(function (t) {
                        e(window).off('beforeunload'),
                          (window.location.href = S);
                      });
                  }
                })
                .fail(function (e) {
                  s(e);
                });
            })(a, i.products);
          },
          error: function (e) {},
        };
      });
  }
});
