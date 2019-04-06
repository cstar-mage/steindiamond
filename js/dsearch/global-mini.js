/*! jQuery Migrate v1.2.1 | (c) 2005, 2013 jQuery Foundation, Inc. and other contributors | jquery.org/license */
jQuery.migrateMute === void 0 && (jQuery.migrateMute = !0),
    function(e, t, n) {
        function r(n) {
            var r = t.console;
            i[n] || (i[n] = !0, e.migrateWarnings.push(n), r && r.warn && !e.migrateMute && (r.warn("JQMIGRATE: " + n), e.migrateTrace && r.trace && r.trace()))
        }

        function a(t, a, i, o) {
            if (Object.defineProperty) try {
                return Object.defineProperty(t, a, {
                    configurable: !0,
                    enumerable: !0,
                    get: function() {
                        return r(o), i
                    },
                    set: function(e) {
                        r(o), i = e
                    }
                }), n
            } catch (s) {}
            e._definePropertyBroken = !0, t[a] = i
        }
        var i = {};
        e.migrateWarnings = [], !e.migrateMute && t.console && t.console.log && t.console.log("JQMIGRATE: Logging is active"), e.migrateTrace === n && (e.migrateTrace = !0), e.migrateReset = function() {
            i = {}, e.migrateWarnings.length = 0
        }, "BackCompat" === document.compatMode && r("jQuery is not compatible with Quirks Mode");
        var o = e("<input/>", {
                size: 1
            }).attr("size") && e.attrFn,
            s = e.attr,
            u = e.attrHooks.value && e.attrHooks.value.get || function() {
                return null
            },
            c = e.attrHooks.value && e.attrHooks.value.set || function() {
                return n
            },
            l = /^(?:input|button)$/i,
            d = /^[238]$/,
            p = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,
            f = /^(?:checked|selected)$/i;
        a(e, "attrFn", o || {}, "jQuery.attrFn is deprecated"), e.attr = function(t, a, i, u) {
            var c = a.toLowerCase(),
                g = t && t.nodeType;
            return u && (4 > s.length && r("jQuery.fn.attr( props, pass ) is deprecated"), t && !d.test(g) && (o ? a in o : e.isFunction(e.fn[a]))) ? e(t)[a](i) : ("type" === a && i !== n && l.test(t.nodeName) && t.parentNode && r("Can't change the 'type' of an input or button in IE 6/7/8"), !e.attrHooks[c] && p.test(c) && (e.attrHooks[c] = {
                get: function(t, r) {
                    var a, i = e.prop(t, r);
                    return i === !0 || "boolean" != typeof i && (a = t.getAttributeNode(r)) && a.nodeValue !== !1 ? r.toLowerCase() : n
                },
                set: function(t, n, r) {
                    var a;
                    return n === !1 ? e.removeAttr(t, r) : (a = e.propFix[r] || r, a in t && (t[a] = !0), t.setAttribute(r, r.toLowerCase())), r
                }
            }, f.test(c) && r("jQuery.fn.attr('" + c + "') may use property instead of attribute")), s.call(e, t, a, i))
        }, e.attrHooks.value = {
            get: function(e, t) {
                var n = (e.nodeName || "").toLowerCase();
                return "button" === n ? u.apply(this, arguments) : ("input" !== n && "option" !== n && r("jQuery.fn.attr('value') no longer gets properties"), t in e ? e.value : null)
            },
            set: function(e, t) {
                var a = (e.nodeName || "").toLowerCase();
                return "button" === a ? c.apply(this, arguments) : ("input" !== a && "option" !== a && r("jQuery.fn.attr('value', val) no longer sets properties"), e.value = t, n)
            }
        };
        var g, h, v = e.fn.init,
            m = e.parseJSON,
            y = /^([^<]*)(<[\w\W]+>)([^>]*)$/;
        e.fn.init = function(t, n, a) {
            var i;
            return t && "string" == typeof t && !e.isPlainObject(n) && (i = y.exec(e.trim(t))) && i[0] && ("<" !== t.charAt(0) && r("$(html) HTML strings must start with '<' character"), i[3] && r("$(html) HTML text after last tag is ignored"), "#" === i[0].charAt(0) && (r("HTML string cannot start with a '#' character"), e.error("JQMIGRATE: Invalid selector string (XSS)")), n && n.context && (n = n.context), e.parseHTML) ? v.call(this, e.parseHTML(i[2], n, !0), n, a) : v.apply(this, arguments)
        }, e.fn.init.prototype = e.fn, e.parseJSON = function(e) {
            return e || null === e ? m.apply(this, arguments) : (r("jQuery.parseJSON requires a valid JSON string"), null)
        }, e.uaMatch = function(e) {
            e = e.toLowerCase();
            var t = /(chrome)[ \/]([\w.]+)/.exec(e) || /(webkit)[ \/]([\w.]+)/.exec(e) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e) || /(msie) ([\w.]+)/.exec(e) || 0 > e.indexOf("compatible") && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e) || [];
            return {
                browser: t[1] || "",
                version: t[2] || "0"
            }
        }, e.browser || (g = e.uaMatch(navigator.userAgent), h = {}, g.browser && (h[g.browser] = !0, h.version = g.version), h.chrome ? h.webkit = !0 : h.webkit && (h.safari = !0), e.browser = h), a(e, "browser", e.browser, "jQuery.browser is deprecated"), e.sub = function() {
            function t(e, n) {
                return new t.fn.init(e, n)
            }
            e.extend(!0, t, this), t.superclass = this, t.fn = t.prototype = this(), t.fn.constructor = t, t.sub = this.sub, t.fn.init = function(r, a) {
                return a && a instanceof e && !(a instanceof t) && (a = t(a)), e.fn.init.call(this, r, a, n)
            }, t.fn.init.prototype = t.fn;
            var n = t(document);
            return r("jQuery.sub() is deprecated"), t
        }, e.ajaxSetup({
            converters: {
                "text json": e.parseJSON
            }
        });
        var b = e.fn.data;
        e.fn.data = function(t) {
            var a, i, o = this[0];
            return !o || "events" !== t || 1 !== arguments.length || (a = e.data(o, t), i = e._data(o, t), a !== n && a !== i || i === n) ? b.apply(this, arguments) : (r("Use of jQuery.fn.data('events') is deprecated"), i)
        };
        var j = /\/(java|ecma)script/i,
            w = e.fn.andSelf || e.fn.addBack;
        e.fn.andSelf = function() {
            return r("jQuery.fn.andSelf() replaced by jQuery.fn.addBack()"), w.apply(this, arguments)
        }, e.clean || (e.clean = function(t, a, i, o) {
            a = a || document, a = !a.nodeType && a[0] || a, a = a.ownerDocument || a, r("jQuery.clean() is deprecated");
            var s, u, c, l, d = [];
            if (e.merge(d, e.buildFragment(t, a).childNodes), i)
                for (c = function(e) {
                        return !e.type || j.test(e.type) ? o ? o.push(e.parentNode ? e.parentNode.removeChild(e) : e) : i.appendChild(e) : n
                    }, s = 0; null != (u = d[s]); s++) e.nodeName(u, "script") && c(u) || (i.appendChild(u), u.getElementsByTagName !== n && (l = e.grep(e.merge([], u.getElementsByTagName("script")), c), d.splice.apply(d, [s + 1, 0].concat(l)), s += l.length));
            return d
        });
        var Q = e.event.add,
            x = e.event.remove,
            k = e.event.trigger,
            N = e.fn.toggle,
            T = e.fn.live,
            M = e.fn.die,
            S = "ajaxStart|ajaxStop|ajaxSend|ajaxComplete|ajaxError|ajaxSuccess",
            C = RegExp("\\b(?:" + S + ")\\b"),
            H = /(?:^|\s)hover(\.\S+|)\b/,
            A = function(t) {
                return "string" != typeof t || e.event.special.hover ? t : (H.test(t) && r("'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'"), t && t.replace(H, "mouseenter$1 mouseleave$1"))
            };
        e.event.props && "attrChange" !== e.event.props[0] && e.event.props.unshift("attrChange", "attrName", "relatedNode", "srcElement"), e.event.dispatch && a(e.event, "handle", e.event.dispatch, "jQuery.event.handle is undocumented and deprecated"), e.event.add = function(e, t, n, a, i) {
            e !== document && C.test(t) && r("AJAX events should be attached to document: " + t), Q.call(this, e, A(t || ""), n, a, i)
        }, e.event.remove = function(e, t, n, r, a) {
            x.call(this, e, A(t) || "", n, r, a)
        }, e.fn.error = function() {
            var e = Array.prototype.slice.call(arguments, 0);
            return r("jQuery.fn.error() is deprecated"), e.splice(0, 0, "error"), arguments.length ? this.bind.apply(this, e) : (this.triggerHandler.apply(this, e), this)
        }, e.fn.toggle = function(t, n) {
            if (!e.isFunction(t) || !e.isFunction(n)) return N.apply(this, arguments);
            r("jQuery.fn.toggle(handler, handler...) is deprecated");
            var a = arguments,
                i = t.guid || e.guid++,
                o = 0,
                s = function(n) {
                    var r = (e._data(this, "lastToggle" + t.guid) || 0) % o;
                    return e._data(this, "lastToggle" + t.guid, r + 1), n.preventDefault(), a[r].apply(this, arguments) || !1
                };
            for (s.guid = i; a.length > o;) a[o++].guid = i;
            return this.click(s)
        }, e.fn.live = function(t, n, a) {
            return r("jQuery.fn.live() is deprecated"), T ? T.apply(this, arguments) : (e(this.context).on(t, this.selector, n, a), this)
        }, e.fn.die = function(t, n) {
            return r("jQuery.fn.die() is deprecated"), M ? M.apply(this, arguments) : (e(this.context).off(t, this.selector || "**", n), this)
        }, e.event.trigger = function(e, t, n, a) {
            return n || C.test(e) || r("Global events are undocumented and deprecated"), k.call(this, e, t, n || document, a)
        }, e.each(S.split("|"), function(t, n) {
            e.event.special[n] = {
                setup: function() {
                    var t = this;
                    return t !== document && (e.event.add(document, n + "." + e.guid, function() {
                        e.event.trigger(n, null, t, !0)
                    }), e._data(this, n, e.guid++)), !1
                },
                teardown: function() {
                    return this !== document && e.event.remove(document, n + "." + e._data(this, n)), !1
                }
            }
        })
    }(jQuery, window);
/**
 * bootstrap.js v3.0.0 by @fat and @mdo
 * Copyright 2013 Twitter Inc.
 * http://www.apache.org/licenses/LICENSE-2.0
 */
if (!jQuery) throw new Error("Bootstrap requires jQuery"); + function(a) {
    "use strict";

    function b() {
        var a = document.createElement("bootstrap"),
            b = {
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "oTransitionEnd otransitionend",
                transition: "transitionend"
            };
        for (var c in b)
            if (void 0 !== a.style[c]) return {
                end: b[c]
            }
    }
    a.fn.emulateTransitionEnd = function(b) {
        var c = !1,
            d = this;
        a(this).one(a.support.transition.end, function() {
            c = !0
        });
        var e = function() {
            c || a(d).trigger(a.support.transition.end)
        };
        return setTimeout(e, b), this
    }, a(function() {
        a.support.transition = b()
    })
}(window.jQuery), + function(a) {
    "use strict";
    var b = '[data-dismiss="alert"]',
        c = function(c) {
            a(c).on("click", b, this.close)
        };
    c.prototype.close = function(b) {
        function c() {
            f.trigger("closed.bs.alert").remove()
        }
        var d = a(this),
            e = d.attr("data-target");
        e || (e = d.attr("href"), e = e && e.replace(/.*(?=#[^\s]*$)/, ""));
        var f = a(e);
        b && b.preventDefault(), f.length || (f = d.hasClass("alert") ? d : d.parent()), f.trigger(b = a.Event("close.bs.alert")), b.isDefaultPrevented() || (f.removeClass("in"), a.support.transition && f.hasClass("fade") ? f.one(a.support.transition.end, c).emulateTransitionEnd(150) : c())
    };
    var d = a.fn.alert;
    a.fn.alert = function(b) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.alert");
            e || d.data("bs.alert", e = new c(this)), "string" == typeof b && e[b].call(d)
        })
    }, a.fn.alert.Constructor = c, a.fn.alert.noConflict = function() {
        return a.fn.alert = d, this
    }, a(document).on("click.bs.alert.data-api", b, c.prototype.close)
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(c, d) {
        this.$element = a(c), this.options = a.extend({}, b.DEFAULTS, d)
    };
    b.DEFAULTS = {
        loadingText: "loading..."
    }, b.prototype.setState = function(a) {
        var b = "disabled",
            c = this.$element,
            d = c.is("input") ? "val" : "html",
            e = c.data();
        a += "Text", e.resetText || c.data("resetText", c[d]()), c[d](e[a] || this.options[a]), setTimeout(function() {
            "loadingText" == a ? c.addClass(b).attr(b, b) : c.removeClass(b).removeAttr(b)
        }, 0)
    }, b.prototype.toggle = function() {
        var a = this.$element.closest('[data-toggle="buttons"]');
        if (a.length) {
            var b = this.$element.find("input").prop("checked", !this.$element.hasClass("active")).trigger("change");
            "radio" === b.prop("type") && a.find(".active").removeClass("active")
        }
        this.$element.toggleClass("active")
    };
    var c = a.fn.button;
    a.fn.button = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.button"),
                f = "object" == typeof c && c;
            e || d.data("bs.button", e = new b(this, f)), "toggle" == c ? e.toggle() : c && e.setState(c)
        })
    }, a.fn.button.Constructor = b, a.fn.button.noConflict = function() {
        return a.fn.button = c, this
    }, a(document).on("click.bs.button.data-api", "[data-toggle^=button]", function(b) {
        var c = a(b.target);
        c.hasClass("btn") || (c = c.closest(".btn")), c.button("toggle"), b.preventDefault()
    })
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.$indicators = this.$element.find(".carousel-indicators"), this.options = c, this.paused = this.sliding = this.interval = this.$active = this.$items = null, "hover" == this.options.pause && this.$element.on("mouseenter", a.proxy(this.pause, this)).on("mouseleave", a.proxy(this.cycle, this))
    };
    b.DEFAULTS = {
        interval: 5e3,
        pause: "hover",
        wrap: !0
    }, b.prototype.cycle = function(b) {
        return b || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this
    }, b.prototype.getActiveIndex = function() {
        return this.$active = this.$element.find(".item.active"), this.$items = this.$active.parent().children(), this.$items.index(this.$active)
    }, b.prototype.to = function(b) {
        var c = this,
            d = this.getActiveIndex();
        return b > this.$items.length - 1 || 0 > b ? void 0 : this.sliding ? this.$element.one("slid", function() {
            c.to(b)
        }) : d == b ? this.pause().cycle() : this.slide(b > d ? "next" : "prev", a(this.$items[b]))
    }, b.prototype.pause = function(b) {
        return b || (this.paused = !0), this.$element.find(".next, .prev").length && a.support.transition.end && (this.$element.trigger(a.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
    }, b.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    }, b.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    }, b.prototype.slide = function(b, c) {
        var d = this.$element.find(".item.active"),
            e = c || d[b](),
            f = this.interval,
            g = "next" == b ? "left" : "right",
            h = "next" == b ? "first" : "last",
            i = this;
        if (!e.length) {
            if (!this.options.wrap) return;
            e = this.$element.find(".item")[h]()
        }
        this.sliding = !0, f && this.pause();
        var j = a.Event("slide.bs.carousel", {
            relatedTarget: e[0],
            direction: g
        });
        if (!e.hasClass("active")) {
            if (this.$indicators.length && (this.$indicators.find(".active").removeClass("active"), this.$element.one("slid", function() {
                    var b = a(i.$indicators.children()[i.getActiveIndex()]);
                    b && b.addClass("active")
                })), a.support.transition && this.$element.hasClass("slide")) {
                if (this.$element.trigger(j), j.isDefaultPrevented()) return;
                e.addClass(b), e[0].offsetWidth, d.addClass(g), e.addClass(g), d.one(a.support.transition.end, function() {
                    e.removeClass([b, g].join(" ")).addClass("active"), d.removeClass(["active", g].join(" ")), i.sliding = !1, setTimeout(function() {
                        i.$element.trigger("slid")
                    }, 0)
                }).emulateTransitionEnd(600)
            } else {
                if (this.$element.trigger(j), j.isDefaultPrevented()) return;
                d.removeClass("active"), e.addClass("active"), this.sliding = !1, this.$element.trigger("slid")
            }
            return f && this.cycle(), this
        }
    };
    var c = a.fn.carousel;
    a.fn.carousel = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.carousel"),
                f = a.extend({}, b.DEFAULTS, d.data(), "object" == typeof c && c),
                g = "string" == typeof c ? c : f.slide;
            e || d.data("bs.carousel", e = new b(this, f)), "number" == typeof c ? e.to(c) : g ? e[g]() : f.interval && e.pause().cycle()
        })
    }, a.fn.carousel.Constructor = b, a.fn.carousel.noConflict = function() {
        return a.fn.carousel = c, this
    }, a(document).on("click.bs.carousel.data-api", "[data-slide], [data-slide-to]", function(b) {
        var c, d = a(this),
            e = a(d.attr("data-target") || (c = d.attr("href")) && c.replace(/.*(?=#[^\s]+$)/, "")),
            f = a.extend({}, e.data(), d.data()),
            g = d.attr("data-slide-to");
        g && (f.interval = !1), e.carousel(f), (g = d.attr("data-slide-to")) && e.data("bs.carousel").to(g), b.preventDefault()
    }), a(window).on("load", function() {
        a('[data-ride="carousel"]').each(function() {
            var b = a(this);
            b.carousel(b.data())
        })
    })
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(c, d) {
        this.$element = a(c), this.options = a.extend({}, b.DEFAULTS, d), this.transitioning = null, this.options.parent && (this.$parent = a(this.options.parent)), this.options.toggle && this.toggle()
    };
    b.DEFAULTS = {
        toggle: !0
    }, b.prototype.dimension = function() {
        var a = this.$element.hasClass("width");
        return a ? "width" : "height"
    }, b.prototype.show = function() {
        if (!this.transitioning && !this.$element.hasClass("in")) {
            var b = a.Event("show.bs.collapse");
            if (this.$element.trigger(b), !b.isDefaultPrevented()) {
                var c = this.$parent && this.$parent.find("> .panel > .in");
                if (c && c.length) {
                    var d = c.data("bs.collapse");
                    if (d && d.transitioning) return;
                    c.collapse("hide"), d || c.data("bs.collapse", null)
                }
                var e = this.dimension();
                this.$element.removeClass("collapse").addClass("collapsing")[e](0), this.transitioning = 1;
                var f = function() {
                    this.$element.removeClass("collapsing").addClass("in")[e]("auto"), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                };
                if (!a.support.transition) return f.call(this);
                var g = a.camelCase(["scroll", e].join("-"));
                this.$element.one(a.support.transition.end, a.proxy(f, this)).emulateTransitionEnd(350)[e](this.$element[0][g])
            }
        }
    }, b.prototype.hide = function() {
        if (!this.transitioning && this.$element.hasClass("in")) {
            var b = a.Event("hide.bs.collapse");
            if (this.$element.trigger(b), !b.isDefaultPrevented()) {
                var c = this.dimension();
                this.$element[c](this.$element[c]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse").removeClass("in"), this.transitioning = 1;
                var d = function() {
                    this.transitioning = 0, this.$element.trigger("hidden.bs.collapse").removeClass("collapsing").addClass("collapse")
                };
                return a.support.transition ? (this.$element[c](0).one(a.support.transition.end, a.proxy(d, this)).emulateTransitionEnd(350), void 0) : d.call(this)
            }
        }
    }, b.prototype.toggle = function() {
        this[this.$element.hasClass("in") ? "hide" : "show"]()
    };
    var c = a.fn.collapse;
    a.fn.collapse = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.collapse"),
                f = a.extend({}, b.DEFAULTS, d.data(), "object" == typeof c && c);
            e || d.data("bs.collapse", e = new b(this, f)), "string" == typeof c && e[c]()
        })
    }, a.fn.collapse.Constructor = b, a.fn.collapse.noConflict = function() {
        return a.fn.collapse = c, this
    }, a(document).on("click.bs.collapse.data-api", "[data-toggle=collapse]", function(b) {
        var c, d = a(this),
            e = d.attr("data-target") || b.preventDefault() || (c = d.attr("href")) && c.replace(/.*(?=#[^\s]+$)/, ""),
            f = a(e),
            g = f.data("bs.collapse"),
            h = g ? "toggle" : d.data(),
            i = d.attr("data-parent"),
            j = i && a(i);
        g && g.transitioning || (j && j.find('[data-toggle=collapse][data-parent="' + i + '"]').not(d).addClass("collapsed"), d[f.hasClass("in") ? "addClass" : "removeClass"]("collapsed")), f.collapse(h)
    })
}(window.jQuery), + function(a) {
    "use strict";

    function b() {
        a(d).remove(), a(e).each(function(b) {
            var d = c(a(this));
            d.hasClass("open") && (d.trigger(b = a.Event("hide.bs.dropdown")), b.isDefaultPrevented() || d.removeClass("open").trigger("hidden.bs.dropdown"))
        })
    }

    function c(b) {
        var c = b.attr("data-target");
        c || (c = b.attr("href"), c = c && /#/.test(c) && c.replace(/.*(?=#[^\s]*$)/, ""));
        var d = c && a(c);
        return d && d.length ? d : b.parent()
    }
    var d = ".dropdown-backdrop",
        e = "[data-toggle=dropdown]",
        f = function(b) {
            a(b).on("click.bs.dropdown", this.toggle)
        };
    f.prototype.toggle = function(d) {
        var e = a(this);
        if (!e.is(".disabled, :disabled")) {
            var f = c(e),
                g = f.hasClass("open");
            if (b(), !g) {
                if ("ontouchstart" in document.documentElement && !f.closest(".navbar-nav").length && a('<div class="dropdown-backdrop"/>').insertAfter(a(this)).on("click", b), f.trigger(d = a.Event("show.bs.dropdown")), d.isDefaultPrevented()) return;
                f.toggleClass("open").trigger("shown.bs.dropdown"), e.focus()
            }
            return !1
        }
    }, f.prototype.keydown = function(b) {
        if (/(38|40|27)/.test(b.keyCode)) {
            var d = a(this);
            if (b.preventDefault(), b.stopPropagation(), !d.is(".disabled, :disabled")) {
                var f = c(d),
                    g = f.hasClass("open");
                if (!g || g && 27 == b.keyCode) return 27 == b.which && f.find(e).focus(), d.click();
                var h = a("[role=menu] li:not(.divider):visible a", f);
                if (h.length) {
                    var i = h.index(h.filter(":focus"));
                    38 == b.keyCode && i > 0 && i--, 40 == b.keyCode && i < h.length - 1 && i++, ~i || (i = 0), h.eq(i).focus()
                }
            }
        }
    };
    var g = a.fn.dropdown;
    a.fn.dropdown = function(b) {
        return this.each(function() {
            var c = a(this),
                d = c.data("dropdown");
            d || c.data("dropdown", d = new f(this)), "string" == typeof b && d[b].call(c)
        })
    }, a.fn.dropdown.Constructor = f, a.fn.dropdown.noConflict = function() {
        return a.fn.dropdown = g, this
    }, a(document).on("click.bs.dropdown.data-api", b).on("click.bs.dropdown.data-api", ".dropdown form", function(a) {
        a.stopPropagation()
    }).on("click.bs.dropdown.data-api", e, f.prototype.toggle).on("keydown.bs.dropdown.data-api", e + ", [role=menu]", f.prototype.keydown)
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(b, c) {
        this.options = c, this.$element = a(b), this.$backdrop = this.isShown = null, this.options.remote && this.$element.load(this.options.remote)
    };
    b.DEFAULTS = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    }, b.prototype.toggle = function(a) {
        return this[this.isShown ? "hide" : "show"](a)
    }, b.prototype.show = function(b) {
        var c = this,
            d = a.Event("show.bs.modal", {
                relatedTarget: b
            });
        this.$element.trigger(d), this.isShown || d.isDefaultPrevented() || (this.isShown = !0, this.escape(), this.$element.on("click.dismiss.modal", '[data-dismiss="modal"]', a.proxy(this.hide, this)), this.backdrop(function() {
            var d = a.support.transition && c.$element.hasClass("fade");
            c.$element.parent().length || c.$element.appendTo(document.body), c.$element.show(), d && c.$element[0].offsetWidth, c.$element.addClass("in").attr("aria-hidden", !1), c.enforceFocus();
            var e = a.Event("shown.bs.modal", {
                relatedTarget: b
            });
            d ? c.$element.find(".modal-dialog").one(a.support.transition.end, function() {
                c.$element.focus().trigger(e)
            }).emulateTransitionEnd(300) : c.$element.focus().trigger(e)
        }))
    }, b.prototype.hide = function(b) {
        b && b.preventDefault(), b = a.Event("hide.bs.modal"), this.$element.trigger(b), this.isShown && !b.isDefaultPrevented() && (this.isShown = !1, this.escape(), a(document).off("focusin.bs.modal"), this.$element.removeClass("in").attr("aria-hidden", !0).off("click.dismiss.modal"), a.support.transition && this.$element.hasClass("fade") ? this.$element.one(a.support.transition.end, a.proxy(this.hideModal, this)).emulateTransitionEnd(300) : this.hideModal())
    }, b.prototype.enforceFocus = function() {
        a(document).off("focusin.bs.modal").on("focusin.bs.modal", a.proxy(function(a) {
            this.$element[0] === a.target || this.$element.has(a.target).length || this.$element.focus()
        }, this))
    }, b.prototype.escape = function() {
        this.isShown && this.options.keyboard ? this.$element.on("keyup.dismiss.bs.modal", a.proxy(function(a) {
            27 == a.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keyup.dismiss.bs.modal")
    }, b.prototype.hideModal = function() {
        var a = this;
        this.$element.hide(), this.backdrop(function() {
            a.removeBackdrop(), a.$element.trigger("hidden.bs.modal")
        })
    }, b.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
    }, b.prototype.backdrop = function(b) {
        var c = this.$element.hasClass("fade") ? "fade" : "";
        if (this.isShown && this.options.backdrop) {
            var d = a.support.transition && c;
            if (this.$backdrop = a('<div class="modal-backdrop ' + c + '" />').appendTo(document.body), this.$element.on("click.dismiss.modal", a.proxy(function(a) {
                    a.target === a.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus.call(this.$element[0]) : this.hide.call(this))
                }, this)), d && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !b) return;
            d ? this.$backdrop.one(a.support.transition.end, b).emulateTransitionEnd(150) : b()
        } else !this.isShown && this.$backdrop ? (this.$backdrop.removeClass("in"), a.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one(a.support.transition.end, b).emulateTransitionEnd(150) : b()) : b && b()
    };
    var c = a.fn.modal;
    a.fn.modal = function(c, d) {
        return this.each(function() {
            var e = a(this),
                f = e.data("bs.modal"),
                g = a.extend({}, b.DEFAULTS, e.data(), "object" == typeof c && c);
            f || e.data("bs.modal", f = new b(this, g)), "string" == typeof c ? f[c](d) : g.show && f.show(d)
        })
    }, a.fn.modal.Constructor = b, a.fn.modal.noConflict = function() {
        return a.fn.modal = c, this
    }, a(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(b) {
        var c = a(this),
            d = c.attr("href"),
            e = a(c.attr("data-target") || d && d.replace(/.*(?=#[^\s]+$)/, "")),
            f = e.data("modal") ? "toggle" : a.extend({
                remote: !/#/.test(d) && d
            }, e.data(), c.data());
        b.preventDefault(), e.modal(f, this).one("hide", function() {
            c.is(":visible") && c.focus()
        })
    }), a(document).on("show.bs.modal", ".modal", function() {
        a(document.body).addClass("modal-open")
    }).on("hidden.bs.modal", ".modal", function() {
        a(document.body).removeClass("modal-open")
    })
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(a, b) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.$element = null, this.init("tooltip", a, b)
    };
    b.DEFAULTS = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        container: !1
    }, b.prototype.init = function(b, c, d) {
        this.enabled = !0, this.type = b, this.$element = a(c), this.options = this.getOptions(d);
        for (var e = this.options.trigger.split(" "), f = e.length; f--;) {
            var g = e[f];
            if ("click" == g) this.$element.on("click." + this.type, this.options.selector, a.proxy(this.toggle, this));
            else if ("manual" != g) {
                var h = "hover" == g ? "mouseenter" : "focus",
                    i = "hover" == g ? "mouseleave" : "blur";
                this.$element.on(h + "." + this.type, this.options.selector, a.proxy(this.enter, this)), this.$element.on(i + "." + this.type, this.options.selector, a.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = a.extend({}, this.options, {
            trigger: "manual",
            selector: ""
        }) : this.fixTitle()
    }, b.prototype.getDefaults = function() {
        return b.DEFAULTS
    }, b.prototype.getOptions = function(b) {
        return b = a.extend({}, this.getDefaults(), this.$element.data(), b), b.delay && "number" == typeof b.delay && (b.delay = {
            show: b.delay,
            hide: b.delay
        }), b
    }, b.prototype.getDelegateOptions = function() {
        var b = {},
            c = this.getDefaults();
        return this._options && a.each(this._options, function(a, d) {
            c[a] != d && (b[a] = d)
        }), b
    }, b.prototype.enter = function(b) {
        var c = b instanceof this.constructor ? b : a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs." + this.type);
        return clearTimeout(c.timeout), c.hoverState = "in", c.options.delay && c.options.delay.show ? (c.timeout = setTimeout(function() {
            "in" == c.hoverState && c.show()
        }, c.options.delay.show), void 0) : c.show()
    }, b.prototype.leave = function(b) {
        var c = b instanceof this.constructor ? b : a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs." + this.type);
        return clearTimeout(c.timeout), c.hoverState = "out", c.options.delay && c.options.delay.hide ? (c.timeout = setTimeout(function() {
            "out" == c.hoverState && c.hide()
        }, c.options.delay.hide), void 0) : c.hide()
    }, b.prototype.show = function() {
        var b = a.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            if (this.$element.trigger(b), b.isDefaultPrevented()) return;
            var c = this.tip();
            this.setContent(), this.options.animation && c.addClass("fade");
            var d = "function" == typeof this.options.placement ? this.options.placement.call(this, c[0], this.$element[0]) : this.options.placement,
                e = /\s?auto?\s?/i,
                f = e.test(d);
            f && (d = d.replace(e, "") || "top"), c.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(d), this.options.container ? c.appendTo(this.options.container) : c.insertAfter(this.$element);
            var g = this.getPosition(),
                h = c[0].offsetWidth,
                i = c[0].offsetHeight;
            if (f) {
                var j = this.$element.parent(),
                    k = d,
                    l = document.documentElement.scrollTop || document.body.scrollTop,
                    m = "body" == this.options.container ? window.innerWidth : j.outerWidth(),
                    n = "body" == this.options.container ? window.innerHeight : j.outerHeight(),
                    o = "body" == this.options.container ? 0 : j.offset().left;
                d = "bottom" == d && g.top + g.height + i - l > n ? "top" : "top" == d && g.top - l - i < 0 ? "bottom" : "right" == d && g.right + h > m ? "left" : "left" == d && g.left - h < o ? "right" : d, c.removeClass(k).addClass(d)
            }
            var p = this.getCalculatedOffset(d, g, h, i);
            this.applyPlacement(p, d), this.$element.trigger("shown.bs." + this.type)
        }
    }, b.prototype.applyPlacement = function(a, b) {
        var c, d = this.tip(),
            e = d[0].offsetWidth,
            f = d[0].offsetHeight,
            g = parseInt(d.css("margin-top"), 10),
            h = parseInt(d.css("margin-left"), 10);
        isNaN(g) && (g = 0), isNaN(h) && (h = 0), a.top = a.top + g, a.left = a.left + h, d.offset(a).addClass("in");
        var i = d[0].offsetWidth,
            j = d[0].offsetHeight;
        if ("top" == b && j != f && (c = !0, a.top = a.top + f - j), /bottom|top/.test(b)) {
            var k = 0;
            a.left < 0 && (k = -2 * a.left, a.left = 0, d.offset(a), i = d[0].offsetWidth, j = d[0].offsetHeight), this.replaceArrow(k - e + i, i, "left")
        } else this.replaceArrow(j - f, j, "top");
        c && d.offset(a)
    }, b.prototype.replaceArrow = function(a, b, c) {
        this.arrow().css(c, a ? 50 * (1 - a / b) + "%" : "")
    }, b.prototype.setContent = function() {
        var a = this.tip(),
            b = this.getTitle();
        a.find(".tooltip-inner")[this.options.html ? "html" : "text"](b), a.removeClass("fade in top bottom left right")
    }, b.prototype.hide = function() {
        function b() {
            "in" != c.hoverState && d.detach()
        }
        var c = this,
            d = this.tip(),
            e = a.Event("hide.bs." + this.type);
        return this.$element.trigger(e), e.isDefaultPrevented() ? void 0 : (d.removeClass("in"), a.support.transition && this.$tip.hasClass("fade") ? d.one(a.support.transition.end, b).emulateTransitionEnd(150) : b(), this.$element.trigger("hidden.bs." + this.type), this)
    }, b.prototype.fixTitle = function() {
        var a = this.$element;
        (a.attr("title") || "string" != typeof a.attr("data-original-title")) && a.attr("data-original-title", a.attr("title") || "").attr("title", "")
    }, b.prototype.hasContent = function() {
        return this.getTitle()
    }, b.prototype.getPosition = function() {
        var b = this.$element[0];
        return a.extend({}, "function" == typeof b.getBoundingClientRect ? b.getBoundingClientRect() : {
            width: b.offsetWidth,
            height: b.offsetHeight
        }, this.$element.offset())
    }, b.prototype.getCalculatedOffset = function(a, b, c, d) {
        return "bottom" == a ? {
            top: b.top + b.height,
            left: b.left + b.width / 2 - c / 2
        } : "top" == a ? {
            top: b.top - d,
            left: b.left + b.width / 2 - c / 2
        } : "left" == a ? {
            top: b.top + b.height / 2 - d / 2,
            left: b.left - c
        } : {
            top: b.top + b.height / 2 - d / 2,
            left: b.left + b.width
        }
    }, b.prototype.getTitle = function() {
        var a, b = this.$element,
            c = this.options;
        return a = b.attr("data-original-title") || ("function" == typeof c.title ? c.title.call(b[0]) : c.title)
    }, b.prototype.tip = function() {
        return this.$tip = this.$tip || a(this.options.template)
    }, b.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    }, b.prototype.validate = function() {
        this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null)
    }, b.prototype.enable = function() {
        this.enabled = !0
    }, b.prototype.disable = function() {
        this.enabled = !1
    }, b.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    }, b.prototype.toggle = function(b) {
        var c = b ? a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs." + this.type) : this;
        c.tip().hasClass("in") ? c.leave(c) : c.enter(c)
    }, b.prototype.destroy = function() {
        this.hide().$element.off("." + this.type).removeData("bs." + this.type)
    };
    var c = a.fn.tooltip;
    a.fn.tooltip = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.tooltip"),
                f = "object" == typeof c && c;
            e || d.data("bs.tooltip", e = new b(this, f)), "string" == typeof c && e[c]()
        })
    }, a.fn.tooltip.Constructor = b, a.fn.tooltip.noConflict = function() {
        return a.fn.tooltip = c, this
    }
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(a, b) {
        this.init("popover", a, b)
    };
    if (!a.fn.tooltip) throw new Error("Popover requires tooltip.js");
    b.DEFAULTS = a.extend({}, a.fn.tooltip.Constructor.DEFAULTS, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }), b.prototype = a.extend({}, a.fn.tooltip.Constructor.prototype), b.prototype.constructor = b, b.prototype.getDefaults = function() {
        return b.DEFAULTS
    }, b.prototype.setContent = function() {
        var a = this.tip(),
            b = this.getTitle(),
            c = this.getContent();
        a.find(".popover-title")[this.options.html ? "html" : "text"](b), a.find(".popover-content")[this.options.html ? "html" : "text"](c), a.removeClass("fade top bottom left right in"), a.find(".popover-title").html() || a.find(".popover-title").hide()
    }, b.prototype.hasContent = function() {
        return this.getTitle() || this.getContent()
    }, b.prototype.getContent = function() {
        var a = this.$element,
            b = this.options;
        return a.attr("data-content") || ("function" == typeof b.content ? b.content.call(a[0]) : b.content)
    }, b.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    }, b.prototype.tip = function() {
        return this.$tip || (this.$tip = a(this.options.template)), this.$tip
    };
    var c = a.fn.popover;
    a.fn.popover = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.popover"),
                f = "object" == typeof c && c;
            e || d.data("bs.popover", e = new b(this, f)), "string" == typeof c && e[c]()
        })
    }, a.fn.popover.Constructor = b, a.fn.popover.noConflict = function() {
        return a.fn.popover = c, this
    }
}(window.jQuery), + function(a) {
    "use strict";

    function b(c, d) {
        var e, f = a.proxy(this.process, this);
        this.$element = a(c).is("body") ? a(window) : a(c), this.$body = a("body"), this.$scrollElement = this.$element.on("scroll.bs.scroll-spy.data-api", f), this.options = a.extend({}, b.DEFAULTS, d), this.selector = (this.options.target || (e = a(c).attr("href")) && e.replace(/.*(?=#[^\s]+$)/, "") || "") + " .nav li > a", this.offsets = a([]), this.targets = a([]), this.activeTarget = null, this.refresh(), this.process()
    }
    b.DEFAULTS = {
        offset: 10
    }, b.prototype.refresh = function() {
        var b = this.$element[0] == window ? "offset" : "position";
        this.offsets = a([]), this.targets = a([]);
        var c = this;
        this.$body.find(this.selector).map(function() {
            var d = a(this),
                e = d.data("target") || d.attr("href"),
                f = /^#\w/.test(e) && a(e);
            return f && f.length && [
                [f[b]().top + (!a.isWindow(c.$scrollElement.get(0)) && c.$scrollElement.scrollTop()), e]
            ] || null
        }).sort(function(a, b) {
            return a[0] - b[0]
        }).each(function() {
            c.offsets.push(this[0]), c.targets.push(this[1])
        })
    }, b.prototype.process = function() {
        var a, b = this.$scrollElement.scrollTop() + this.options.offset,
            c = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight,
            d = c - this.$scrollElement.height(),
            e = this.offsets,
            f = this.targets,
            g = this.activeTarget;
        if (b >= d) return g != (a = f.last()[0]) && this.activate(a);
        for (a = e.length; a--;) g != f[a] && b >= e[a] && (!e[a + 1] || b <= e[a + 1]) && this.activate(f[a])
    }, b.prototype.activate = function(b) {
        this.activeTarget = b, a(this.selector).parents(".active").removeClass("active");
        var c = this.selector + '[data-target="' + b + '"],' + this.selector + '[href="' + b + '"]',
            d = a(c).parents("li").addClass("active");
        d.parent(".dropdown-menu").length && (d = d.closest("li.dropdown").addClass("active")), d.trigger("activate")
    };
    var c = a.fn.scrollspy;
    a.fn.scrollspy = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.scrollspy"),
                f = "object" == typeof c && c;
            e || d.data("bs.scrollspy", e = new b(this, f)), "string" == typeof c && e[c]()
        })
    }, a.fn.scrollspy.Constructor = b, a.fn.scrollspy.noConflict = function() {
        return a.fn.scrollspy = c, this
    }, a(window).on("load", function() {
        a('[data-spy="scroll"]').each(function() {
            var b = a(this);
            b.scrollspy(b.data())
        })
    })
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(b) {
        this.element = a(b)
    };
    b.prototype.show = function() {
        var b = this.element,
            c = b.closest("ul:not(.dropdown-menu)"),
            d = b.attr("data-target");
        if (d || (d = b.attr("href"), d = d && d.replace(/.*(?=#[^\s]*$)/, "")), !b.parent("li").hasClass("active")) {
            var e = c.find(".active:last a")[0],
                f = a.Event("show.bs.tab", {
                    relatedTarget: e
                });
            if (b.trigger(f), !f.isDefaultPrevented()) {
                var g = a(d);
                this.activate(b.parent("li"), c), this.activate(g, g.parent(), function() {
                    b.trigger({
                        type: "shown.bs.tab",
                        relatedTarget: e
                    })
                })
            }
        }
    }, b.prototype.activate = function(b, c, d) {
        function e() {
            f.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"), b.addClass("active"), g ? (b[0].offsetWidth, b.addClass("in")) : b.removeClass("fade"), b.parent(".dropdown-menu") && b.closest("li.dropdown").addClass("active"), d && d()
        }
        var f = c.find("> .active"),
            g = d && a.support.transition && f.hasClass("fade");
        g ? f.one(a.support.transition.end, e).emulateTransitionEnd(150) : e(), f.removeClass("in")
    };
    var c = a.fn.tab;
    a.fn.tab = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.tab");
            e || d.data("bs.tab", e = new b(this)), "string" == typeof c && e[c]()
        })
    }, a.fn.tab.Constructor = b, a.fn.tab.noConflict = function() {
        return a.fn.tab = c, this
    }, a(document).on("click.bs.tab.data-api", '[data-toggle="tab"], [data-toggle="pill"]', function(b) {
        b.preventDefault(), a(this).tab("show")
    })
}(window.jQuery), + function(a) {
    "use strict";
    var b = function(c, d) {
        this.options = a.extend({}, b.DEFAULTS, d), this.$window = a(window).on("scroll.bs.affix.data-api", a.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", a.proxy(this.checkPositionWithEventLoop, this)), this.$element = a(c), this.affixed = this.unpin = null, this.checkPosition()
    };
    b.RESET = "affix affix-top affix-bottom", b.DEFAULTS = {
        offset: 0
    }, b.prototype.checkPositionWithEventLoop = function() {
        setTimeout(a.proxy(this.checkPosition, this), 1)
    }, b.prototype.checkPosition = function() {
        if (this.$element.is(":visible")) {
            var c = a(document).height(),
                d = this.$window.scrollTop(),
                e = this.$element.offset(),
                f = this.options.offset,
                g = f.top,
                h = f.bottom;
            "object" != typeof f && (h = g = f), "function" == typeof g && (g = f.top()), "function" == typeof h && (h = f.bottom());
            var i = null != this.unpin && d + this.unpin <= e.top ? !1 : null != h && e.top + this.$element.height() >= c - h ? "bottom" : null != g && g >= d ? "top" : !1;
            this.affixed !== i && (this.unpin && this.$element.css("top", ""), this.affixed = i, this.unpin = "bottom" == i ? e.top - d : null, this.$element.removeClass(b.RESET).addClass("affix" + (i ? "-" + i : "")), "bottom" == i && this.$element.offset({
                top: document.body.offsetHeight - h - this.$element.height()
            }))
        }
    };
    var c = a.fn.affix;
    a.fn.affix = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("bs.affix"),
                f = "object" == typeof c && c;
            e || d.data("bs.affix", e = new b(this, f)), "string" == typeof c && e[c]()
        })
    }, a.fn.affix.Constructor = b, a.fn.affix.noConflict = function() {
        return a.fn.affix = c, this
    }, a(window).on("load", function() {
        a('[data-spy="affix"]').each(function() {
            var b = a(this),
                c = b.data();
            c.offset = c.offset || {}, c.offsetBottom && (c.offset.bottom = c.offsetBottom), c.offsetTop && (c.offset.top = c.offsetTop), b.affix(c)
        })
    })
}(window.jQuery);
/*
 * Project: Bootstrap Hover Dropdown
 * Author: Cameron Spear
 * Contributors: Mattia Larentis
 *
 * Dependencies: Bootstrap's Dropdown plugin, jQuery
 *
 * A simple plugin to enable Bootstrap dropdowns to active on hover and provide a nice user experience.
 *
 * License: MIT
 *
 * http://cameronspear.com/blog/bootstrap-dropdown-on-hover-plugin/
 */
(function(e, t, n) {
    if ("ontouchstart" in document) return;
    var r = e();
    e.fn.dropdownHover = function(n) {
        r = r.add(this.parent());
        return this.each(function() {
            var i = e(this),
                s = i.parent(),
                o = {
                    delay: 0,
                    instantlyCloseOthers: !0
                },
                u = {
                    delay: e(this).data("delay"),
                    instantlyCloseOthers: e(this).data("close-others")
                },
                a = e.extend(!0, {}, o, n, u),
                f;
            s.hover(function(n) {
                if (!s.hasClass("open") && !i.is(n.target)) return !0;
                a.instantlyCloseOthers === !0 && r.removeClass("open");
                t.clearTimeout(f);
                s.addClass("open");
                s.trigger(e.Event("show.bs.dropdown"))
            }, function() {
                f = t.setTimeout(function() {
                    s.removeClass("open");
                    s.trigger("hide.bs.dropdown")
                }, a.delay)
            });
            i.hover(function() {
                a.instantlyCloseOthers === !0 && r.removeClass("open");
                t.clearTimeout(f);
                s.addClass("open");
                s.trigger(e.Event("show.bs.dropdown"))
            });
            s.find(".dropdown-submenu").each(function() {
                var n = e(this),
                    r;
                n.hover(function() {
                    t.clearTimeout(r);
                    n.children(".dropdown-menu").show();
                    n.siblings().children(".dropdown-menu").hide()
                }, function() {
                    var e = n.children(".dropdown-menu");
                    r = t.setTimeout(function() {
                        e.hide()
                    }, a.delay)
                })
            })
        })
    };
    e(document).ready(function() {
        e('[data-hover="dropdown"]').dropdownHover()
    })
})(jQuery, this);
/*mousewheel*/
(function(a) {
    function d(b) {
        var c = b || window.event,
            d = [].slice.call(arguments, 1),
            e = 0,
            f = !0,
            g = 0,
            h = 0;
        return b = a.event.fix(c), b.type = "mousewheel", c.wheelDelta && (e = c.wheelDelta / 120), c.detail && (e = -c.detail / 3), h = e, c.axis !== undefined && c.axis === c.HORIZONTAL_AXIS && (h = 0, g = -1 * e), c.wheelDeltaY !== undefined && (h = c.wheelDeltaY / 120), c.wheelDeltaX !== undefined && (g = -1 * c.wheelDeltaX / 120), d.unshift(b, e, g, h), (a.event.dispatch || a.event.handle).apply(this, d)
    }
    var b = ["DOMMouseScroll", "mousewheel"];
    if (a.event.fixHooks)
        for (var c = b.length; c;) a.event.fixHooks[b[--c]] = a.event.mouseHooks;
    a.event.special.mousewheel = {
        setup: function() {
            if (this.addEventListener)
                for (var a = b.length; a;) this.addEventListener(b[--a], d, !1);
            else this.onmousewheel = d
        },
        teardown: function() {
            if (this.removeEventListener)
                for (var a = b.length; a;) this.removeEventListener(b[--a], d, !1);
            else this.onmousewheel = null
        }
    }, a.fn.extend({
        mousewheel: function(a) {
            return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
        },
        unmousewheel: function(a) {
            return this.unbind("mousewheel", a)
        }
    })
})(jQuery);
/*custom scrollbar*/
(function(c) {
    var b = {
            init: function(e) {
                var f = {
                        set_width: false,
                        set_height: false,
                        horizontalScroll: false,
                        scrollInertia: 950,
                        mouseWheel: true,
                        mouseWheelPixels: "auto",
                        autoDraggerLength: true,
                        autoHideScrollbar: false,
                        snapAmount: null,
                        snapOffset: 0,
                        scrollButtons: {
                            enable: false,
                            scrollType: "continuous",
                            scrollSpeed: "auto",
                            scrollAmount: 40
                        },
                        advanced: {
                            updateOnBrowserResize: true,
                            updateOnContentResize: false,
                            autoExpandHorizontalScroll: false,
                            autoScrollOnFocus: true,
                            normalizeMouseWheelDelta: false
                        },
                        contentTouchScroll: true,
                        callbacks: {
                            onScrollStart: function() {},
                            onScroll: function() {},
                            onTotalScroll: function() {},
                            onTotalScrollBack: function() {},
                            onTotalScrollOffset: 0,
                            onTotalScrollBackOffset: 0,
                            whileScrolling: function() {}
                        },
                        theme: "light"
                    },
                    e = c.extend(true, f, e);
                return this.each(function() {
                    var m = c(this);
                    if (e.set_width) {
                        m.css("width", e.set_width)
                    }
                    if (e.set_height) {
                        m.css("height", e.set_height)
                    }
                    if (!c(document).data("mCustomScrollbar-index")) {
                        c(document).data("mCustomScrollbar-index", "1")
                    } else {
                        var t = parseInt(c(document).data("mCustomScrollbar-index"));
                        c(document).data("mCustomScrollbar-index", t + 1)
                    }
                    m.wrapInner("<div class='mCustomScrollBox mCS-" + e.theme + "' id='mCSB_" + c(document).data("mCustomScrollbar-index") + "' style='position:relative; height:100%; overflow:hidden; max-width:100%;' />").addClass("mCustomScrollbar _mCS_" + c(document).data("mCustomScrollbar-index"));
                    var g = m.children(".mCustomScrollBox");
                    if (e.horizontalScroll) {
                        g.addClass("mCSB_horizontal").wrapInner("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />");
                        var k = g.children(".mCSB_h_wrapper");
                        k.wrapInner("<div class='mCSB_container' style='position:absolute; left:0;' />").children(".mCSB_container").css({
                            width: k.children().outerWidth(),
                            position: "relative"
                        }).unwrap()
                    } else {
                        g.wrapInner("<div class='mCSB_container' style='position:relative; top:0;' />")
                    }
                    var o = g.children(".mCSB_container");
                    if (c.support.touch) {
                        o.addClass("mCS_touch")
                    }
                    o.after("<div class='mCSB_scrollTools' style='position:absolute;'><div class='mCSB_draggerContainer'><div class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' style='position:relative;'></div></div><div class='mCSB_draggerRail'></div></div></div>");
                    var l = g.children(".mCSB_scrollTools"),
                        h = l.children(".mCSB_draggerContainer"),
                        q = h.children(".mCSB_dragger");
                    if (e.horizontalScroll) {
                        q.data("minDraggerWidth", q.width())
                    } else {
                        q.data("minDraggerHeight", q.height())
                    }
                    if (e.scrollButtons.enable) {
                        if (e.horizontalScroll) {
                            l.prepend("<a class='mCSB_buttonLeft' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonRight' oncontextmenu='return false;'></a>")
                        } else {
                            l.prepend("<a class='mCSB_buttonUp' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonDown' oncontextmenu='return false;'></a>")
                        }
                    }
                    g.bind("scroll", function() {
                        if (!m.is(".mCS_disabled")) {
                            g.scrollTop(0).scrollLeft(0)
                        }
                    });
                    m.data({
                        mCS_Init: true,
                        mCustomScrollbarIndex: c(document).data("mCustomScrollbar-index"),
                        horizontalScroll: e.horizontalScroll,
                        scrollInertia: e.scrollInertia,
                        scrollEasing: "mcsEaseOut",
                        mouseWheel: e.mouseWheel,
                        mouseWheelPixels: e.mouseWheelPixels,
                        autoDraggerLength: e.autoDraggerLength,
                        autoHideScrollbar: e.autoHideScrollbar,
                        snapAmount: e.snapAmount,
                        snapOffset: e.snapOffset,
                        scrollButtons_enable: e.scrollButtons.enable,
                        scrollButtons_scrollType: e.scrollButtons.scrollType,
                        scrollButtons_scrollSpeed: e.scrollButtons.scrollSpeed,
                        scrollButtons_scrollAmount: e.scrollButtons.scrollAmount,
                        autoExpandHorizontalScroll: e.advanced.autoExpandHorizontalScroll,
                        autoScrollOnFocus: e.advanced.autoScrollOnFocus,
                        normalizeMouseWheelDelta: e.advanced.normalizeMouseWheelDelta,
                        contentTouchScroll: e.contentTouchScroll,
                        onScrollStart_Callback: e.callbacks.onScrollStart,
                        onScroll_Callback: e.callbacks.onScroll,
                        onTotalScroll_Callback: e.callbacks.onTotalScroll,
                        onTotalScrollBack_Callback: e.callbacks.onTotalScrollBack,
                        onTotalScroll_Offset: e.callbacks.onTotalScrollOffset,
                        onTotalScrollBack_Offset: e.callbacks.onTotalScrollBackOffset,
                        whileScrolling_Callback: e.callbacks.whileScrolling,
                        bindEvent_scrollbar_drag: false,
                        bindEvent_content_touch: false,
                        bindEvent_scrollbar_click: false,
                        bindEvent_mousewheel: false,
                        bindEvent_buttonsContinuous_y: false,
                        bindEvent_buttonsContinuous_x: false,
                        bindEvent_buttonsPixels_y: false,
                        bindEvent_buttonsPixels_x: false,
                        bindEvent_focusin: false,
                        bindEvent_autoHideScrollbar: false,
                        mCSB_buttonScrollRight: false,
                        mCSB_buttonScrollLeft: false,
                        mCSB_buttonScrollDown: false,
                        mCSB_buttonScrollUp: false
                    });
                    if (e.horizontalScroll) {
                        if (m.css("max-width") !== "none") {
                            if (!e.advanced.updateOnContentResize) {
                                e.advanced.updateOnContentResize = true
                            }
                        }
                    } else {
                        if (m.css("max-height") !== "none") {
                            var s = false,
                                r = parseInt(m.css("max-height"));
                            if (m.css("max-height").indexOf("%") >= 0) {
                                s = r, r = m.parent().height() * s / 100
                            }
                            m.css("overflow", "hidden");
                            g.css("max-height", r)
                        }
                    }
                    m.mCustomScrollbar("update");
                    if (e.advanced.updateOnBrowserResize) {
                        var i, j = c(window).width(),
                            u = c(window).height();
                        c(window).bind("resize." + m.data("mCustomScrollbarIndex"), function() {
                            if (i) {
                                clearTimeout(i)
                            }
                            i = setTimeout(function() {
                                if (!m.is(".mCS_disabled") && !m.is(".mCS_destroyed")) {
                                    var w = c(window).width(),
                                        v = c(window).height();
                                    if (j !== w || u !== v) {
                                        if (m.css("max-height") !== "none" && s) {
                                            g.css("max-height", m.parent().height() * s / 100)
                                        }
                                        m.mCustomScrollbar("update");
                                        j = w;
                                        u = v
                                    }
                                }
                            }, 150)
                        })
                    }
                    if (e.advanced.updateOnContentResize) {
                        var p;
                        if (e.horizontalScroll) {
                            var n = o.outerWidth()
                        } else {
                            var n = o.outerHeight()
                        }
                        p = setInterval(function() {
                            if (e.horizontalScroll) {
                                if (e.advanced.autoExpandHorizontalScroll) {
                                    o.css({
                                        position: "absolute",
                                        width: "auto"
                                    }).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({
                                        width: o.outerWidth(),
                                        position: "relative"
                                    }).unwrap()
                                }
                                var v = o.outerWidth()
                            } else {
                                var v = o.outerHeight()
                            }
                            if (v != n) {
                                m.mCustomScrollbar("update");
                                n = v
                            }
                        }, 300)
                    }
                })
            },
            update: function() {
                var n = c(this),
                    k = n.children(".mCustomScrollBox"),
                    q = k.children(".mCSB_container");
                q.removeClass("mCS_no_scrollbar");
                n.removeClass("mCS_disabled mCS_destroyed");
                k.scrollTop(0).scrollLeft(0);
                var y = k.children(".mCSB_scrollTools"),
                    o = y.children(".mCSB_draggerContainer"),
                    m = o.children(".mCSB_dragger");
                if (n.data("horizontalScroll")) {
                    var A = y.children(".mCSB_buttonLeft"),
                        t = y.children(".mCSB_buttonRight"),
                        f = k.width();
                    if (n.data("autoExpandHorizontalScroll")) {
                        q.css({
                            position: "absolute",
                            width: "auto"
                        }).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({
                            width: q.outerWidth(),
                            position: "relative"
                        }).unwrap()
                    }
                    var z = q.outerWidth()
                } else {
                    var w = y.children(".mCSB_buttonUp"),
                        g = y.children(".mCSB_buttonDown"),
                        r = k.height(),
                        i = q.outerHeight()
                }
                if (i > r && !n.data("horizontalScroll")) {
                    y.css("display", "block");
                    var s = o.height();
                    if (n.data("autoDraggerLength")) {
                        var u = Math.round(r / i * s),
                            l = m.data("minDraggerHeight");
                        if (u <= l) {
                            m.css({
                                height: l
                            })
                        } else {
                            if (u >= s - 10) {
                                var p = s - 10;
                                m.css({
                                    height: p
                                })
                            } else {
                                m.css({
                                    height: u
                                })
                            }
                        }
                        m.children(".mCSB_dragger_bar").css({
                            "line-height": m.height() + "px"
                        })
                    }
                    var B = m.height(),
                        x = (i - r) / (s - B);
                    n.data("scrollAmount", x).mCustomScrollbar("scrolling", k, q, o, m, w, g, A, t);
                    var D = Math.abs(q.position().top);
                    n.mCustomScrollbar("scrollTo", D, {
                        scrollInertia: 0,
                        trigger: "internal"
                    })
                } else {
                    if (z > f && n.data("horizontalScroll")) {
                        y.css("display", "block");
                        var h = o.width();
                        if (n.data("autoDraggerLength")) {
                            var j = Math.round(f / z * h),
                                C = m.data("minDraggerWidth");
                            if (j <= C) {
                                m.css({
                                    width: C
                                })
                            } else {
                                if (j >= h - 10) {
                                    var e = h - 10;
                                    m.css({
                                        width: e
                                    })
                                } else {
                                    m.css({
                                        width: j
                                    })
                                }
                            }
                        }
                        var v = m.width(),
                            x = (z - f) / (h - v);
                        n.data("scrollAmount", x).mCustomScrollbar("scrolling", k, q, o, m, w, g, A, t);
                        var D = Math.abs(q.position().left);
                        n.mCustomScrollbar("scrollTo", D, {
                            scrollInertia: 0,
                            trigger: "internal"
                        })
                    } else {
                        k.unbind("mousewheel focusin");
                        if (n.data("horizontalScroll")) {
                            m.add(q).css("left", 0)
                        } else {
                            m.add(q).css("top", 0)
                        }
                        y.css("display", "none");
                        q.addClass("mCS_no_scrollbar");
                        n.data({
                            bindEvent_mousewheel: false,
                            bindEvent_focusin: false
                        })
                    }
                }
            },
            scrolling: function(h, p, m, j, w, e, A, v) {
                var k = c(this);
                if (!k.data("bindEvent_scrollbar_drag")) {
                    var n, o;
                    if (c.support.msPointer) {
                        j.bind("MSPointerDown", function(H) {
                            H.preventDefault();
                            k.data({
                                on_drag: true
                            });
                            j.addClass("mCSB_dragger_onDrag");
                            var G = c(this),
                                J = G.offset(),
                                F = H.originalEvent.pageX - J.left,
                                I = H.originalEvent.pageY - J.top;
                            if (F < G.width() && F > 0 && I < G.height() && I > 0) {
                                n = I;
                                o = F
                            }
                        });
                        c(document).bind("MSPointerMove." + k.data("mCustomScrollbarIndex"), function(H) {
                            H.preventDefault();
                            if (k.data("on_drag")) {
                                var G = j,
                                    J = G.offset(),
                                    F = H.originalEvent.pageX - J.left,
                                    I = H.originalEvent.pageY - J.top;
                                D(n, o, I, F)
                            }
                        }).bind("MSPointerUp." + k.data("mCustomScrollbarIndex"), function(x) {
                            k.data({
                                on_drag: false
                            });
                            j.removeClass("mCSB_dragger_onDrag")
                        })
                    } else {
                        j.bind("mousedown touchstart", function(H) {
                            H.preventDefault();
                            H.stopImmediatePropagation();
                            var G = c(this),
                                K = G.offset(),
                                F, J;
                            if (H.type === "touchstart") {
                                var I = H.originalEvent.touches[0] || H.originalEvent.changedTouches[0];
                                F = I.pageX - K.left;
                                J = I.pageY - K.top
                            } else {
                                k.data({
                                    on_drag: true
                                });
                                j.addClass("mCSB_dragger_onDrag");
                                F = H.pageX - K.left;
                                J = H.pageY - K.top
                            }
                            if (F < G.width() && F > 0 && J < G.height() && J > 0) {
                                n = J;
                                o = F
                            }
                        }).bind("touchmove", function(H) {
                            H.preventDefault();
                            H.stopImmediatePropagation();
                            var K = H.originalEvent.touches[0] || H.originalEvent.changedTouches[0],
                                G = c(this),
                                J = G.offset(),
                                F = K.pageX - J.left,
                                I = K.pageY - J.top;
                            D(n, o, I, F)
                        });
                        c(document).bind("mousemove." + k.data("mCustomScrollbarIndex"), function(H) {
                            if (k.data("on_drag")) {
                                var G = j,
                                    J = G.offset(),
                                    F = H.pageX - J.left,
                                    I = H.pageY - J.top;
                                D(n, o, I, F)
                            }
                        }).bind("mouseup." + k.data("mCustomScrollbarIndex"), function(x) {
                            k.data({
                                on_drag: false
                            });
                            j.removeClass("mCSB_dragger_onDrag")
                        })
                    }
                    k.data({
                        bindEvent_scrollbar_drag: true
                    })
                }

                function D(G, H, I, F) {
                    if (k.data("horizontalScroll")) {
                        k.mCustomScrollbar("scrollTo", (j.position().left - (H)) + F, {
                            moveDragger: true,
                            trigger: "internal"
                        })
                    } else {
                        k.mCustomScrollbar("scrollTo", (j.position().top - (G)) + I, {
                            moveDragger: true,
                            trigger: "internal"
                        })
                    }
                }
                if (c.support.touch && k.data("contentTouchScroll")) {
                    if (!k.data("bindEvent_content_touch")) {
                        var l, B, r, s, u, C, E;
                        p.bind("touchstart", function(x) {
                            x.stopImmediatePropagation();
                            l = x.originalEvent.touches[0] || x.originalEvent.changedTouches[0];
                            B = c(this);
                            r = B.offset();
                            u = l.pageX - r.left;
                            s = l.pageY - r.top;
                            C = s;
                            E = u
                        });
                        p.bind("touchmove", function(x) {
                            x.preventDefault();
                            x.stopImmediatePropagation();
                            l = x.originalEvent.touches[0] || x.originalEvent.changedTouches[0];
                            B = c(this).parent();
                            r = B.offset();
                            u = l.pageX - r.left;
                            s = l.pageY - r.top;
                            if (k.data("horizontalScroll")) {
                                k.mCustomScrollbar("scrollTo", E - u, {
                                    trigger: "internal"
                                })
                            } else {
                                k.mCustomScrollbar("scrollTo", C - s, {
                                    trigger: "internal"
                                })
                            }
                        })
                    }
                }
                if (!k.data("bindEvent_scrollbar_click")) {
                    m.bind("click", function(F) {
                        var x = (F.pageY - m.offset().top) * k.data("scrollAmount"),
                            y = c(F.target);
                        if (k.data("horizontalScroll")) {
                            x = (F.pageX - m.offset().left) * k.data("scrollAmount")
                        }
                        if (y.hasClass("mCSB_draggerContainer") || y.hasClass("mCSB_draggerRail")) {
                            k.mCustomScrollbar("scrollTo", x, {
                                trigger: "internal",
                                scrollEasing: "draggerRailEase"
                            })
                        }
                    });
                    k.data({
                        bindEvent_scrollbar_click: true
                    })
                }
                if (k.data("mouseWheel")) {
                    if (!k.data("bindEvent_mousewheel")) {
                        h.bind("mousewheel", function(H, J) {
                            var G, F = k.data("mouseWheelPixels"),
                                x = Math.abs(p.position().top),
                                I = j.position().top,
                                y = m.height() - j.height();
                            if (k.data("normalizeMouseWheelDelta")) {
                                if (J < 0) {
                                    J = -1
                                } else {
                                    J = 1
                                }
                            }
                            if (F === "auto") {
                                F = 100 + Math.round(k.data("scrollAmount") / 2)
                            }
                            if (k.data("horizontalScroll")) {
                                I = j.position().left;
                                y = m.width() - j.width();
                                x = Math.abs(p.position().left)
                            }
                            if ((J > 0 && I !== 0) || (J < 0 && I !== y)) {
                                H.preventDefault();
                                H.stopImmediatePropagation()
                            }
                            G = x - (J * F);
                            k.mCustomScrollbar("scrollTo", G, {
                                trigger: "internal"
                            })
                        });
                        k.data({
                            bindEvent_mousewheel: true
                        })
                    }
                }
                if (k.data("scrollButtons_enable")) {
                    if (k.data("scrollButtons_scrollType") === "pixels") {
                        if (k.data("horizontalScroll")) {
                            v.add(A).unbind("mousedown touchstart MSPointerDown mouseup MSPointerUp mouseout MSPointerOut touchend", i, g);
                            k.data({
                                bindEvent_buttonsContinuous_x: false
                            });
                            if (!k.data("bindEvent_buttonsPixels_x")) {
                                v.bind("click", function(x) {
                                    x.preventDefault();
                                    q(Math.abs(p.position().left) + k.data("scrollButtons_scrollAmount"))
                                });
                                A.bind("click", function(x) {
                                    x.preventDefault();
                                    q(Math.abs(p.position().left) - k.data("scrollButtons_scrollAmount"))
                                });
                                k.data({
                                    bindEvent_buttonsPixels_x: true
                                })
                            }
                        } else {
                            e.add(w).unbind("mousedown touchstart MSPointerDown mouseup MSPointerUp mouseout MSPointerOut touchend", i, g);
                            k.data({
                                bindEvent_buttonsContinuous_y: false
                            });
                            if (!k.data("bindEvent_buttonsPixels_y")) {
                                e.bind("click", function(x) {
                                    x.preventDefault();
                                    q(Math.abs(p.position().top) + k.data("scrollButtons_scrollAmount"))
                                });
                                w.bind("click", function(x) {
                                    x.preventDefault();
                                    q(Math.abs(p.position().top) - k.data("scrollButtons_scrollAmount"))
                                });
                                k.data({
                                    bindEvent_buttonsPixels_y: true
                                })
                            }
                        }

                        function q(x) {
                            if (!j.data("preventAction")) {
                                j.data("preventAction", true);
                                k.mCustomScrollbar("scrollTo", x, {
                                    trigger: "internal"
                                })
                            }
                        }
                    } else {
                        if (k.data("horizontalScroll")) {
                            v.add(A).unbind("click");
                            k.data({
                                bindEvent_buttonsPixels_x: false
                            });
                            if (!k.data("bindEvent_buttonsContinuous_x")) {
                                v.bind("mousedown touchstart MSPointerDown", function(y) {
                                    y.preventDefault();
                                    var x = z();
                                    k.data({
                                        mCSB_buttonScrollRight: setInterval(function() {
                                            k.mCustomScrollbar("scrollTo", Math.abs(p.position().left) + x, {
                                                trigger: "internal",
                                                scrollEasing: "easeOutCirc"
                                            })
                                        }, 17)
                                    })
                                });
                                var i = function(x) {
                                    x.preventDefault();
                                    clearInterval(k.data("mCSB_buttonScrollRight"))
                                };
                                v.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", i);
                                A.bind("mousedown touchstart MSPointerDown", function(y) {
                                    y.preventDefault();
                                    var x = z();
                                    k.data({
                                        mCSB_buttonScrollLeft: setInterval(function() {
                                            k.mCustomScrollbar("scrollTo", Math.abs(p.position().left) - x, {
                                                trigger: "internal",
                                                scrollEasing: "easeOutCirc"
                                            })
                                        }, 17)
                                    })
                                });
                                var g = function(x) {
                                    x.preventDefault();
                                    clearInterval(k.data("mCSB_buttonScrollLeft"))
                                };
                                A.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", g);
                                k.data({
                                    bindEvent_buttonsContinuous_x: true
                                })
                            }
                        } else {
                            e.add(w).unbind("click");
                            k.data({
                                bindEvent_buttonsPixels_y: false
                            });
                            if (!k.data("bindEvent_buttonsContinuous_y")) {
                                e.bind("mousedown touchstart MSPointerDown", function(y) {
                                    y.preventDefault();
                                    var x = z();
                                    k.data({
                                        mCSB_buttonScrollDown: setInterval(function() {
                                            k.mCustomScrollbar("scrollTo", Math.abs(p.position().top) + x, {
                                                trigger: "internal",
                                                scrollEasing: "easeOutCirc"
                                            })
                                        }, 17)
                                    })
                                });
                                var t = function(x) {
                                    x.preventDefault();
                                    clearInterval(k.data("mCSB_buttonScrollDown"))
                                };
                                e.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", t);
                                w.bind("mousedown touchstart MSPointerDown", function(y) {
                                    y.preventDefault();
                                    var x = z();
                                    k.data({
                                        mCSB_buttonScrollUp: setInterval(function() {
                                            k.mCustomScrollbar("scrollTo", Math.abs(p.position().top) - x, {
                                                trigger: "internal",
                                                scrollEasing: "easeOutCirc"
                                            })
                                        }, 17)
                                    })
                                });
                                var f = function(x) {
                                    x.preventDefault();
                                    clearInterval(k.data("mCSB_buttonScrollUp"))
                                };
                                w.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", f);
                                k.data({
                                    bindEvent_buttonsContinuous_y: true
                                })
                            }
                        }

                        function z() {
                            var x = k.data("scrollButtons_scrollSpeed");
                            if (k.data("scrollButtons_scrollSpeed") === "auto") {
                                x = Math.round((k.data("scrollInertia") + 100) / 40)
                            }
                            return x
                        }
                    }
                }
                if (k.data("autoScrollOnFocus")) {
                    if (!k.data("bindEvent_focusin")) {
                        h.bind("focusin", function() {
                            h.scrollTop(0).scrollLeft(0);
                            var x = c(document.activeElement);
                            if (x.is("input,textarea,select,button,a[tabindex],area,object")) {
                                var G = p.position().top,
                                    y = x.position().top,
                                    F = h.height() - x.outerHeight();
                                if (k.data("horizontalScroll")) {
                                    G = p.position().left;
                                    y = x.position().left;
                                    F = h.width() - x.outerWidth()
                                }
                                if (G + y < 0 || G + y > F) {
                                    k.mCustomScrollbar("scrollTo", y, {
                                        trigger: "internal"
                                    })
                                }
                            }
                        });
                        k.data({
                            bindEvent_focusin: true
                        })
                    }
                }
                if (k.data("autoHideScrollbar")) {
                    if (!k.data("bindEvent_autoHideScrollbar")) {
                        h.bind("mouseenter", function(x) {
                            h.addClass("mCS-mouse-over");
                            d.showScrollbar.call(h.children(".mCSB_scrollTools"))
                        }).bind("mouseleave touchend", function(x) {
                            h.removeClass("mCS-mouse-over");
                            if (x.type === "mouseleave") {
                                d.hideScrollbar.call(h.children(".mCSB_scrollTools"))
                            }
                        });
                        k.data({
                            bindEvent_autoHideScrollbar: true
                        })
                    }
                }
            },
            scrollTo: function(e, f) {
                var i = c(this),
                    o = {
                        moveDragger: false,
                        trigger: "external",
                        callbacks: true,
                        scrollInertia: i.data("scrollInertia"),
                        scrollEasing: i.data("scrollEasing")
                    },
                    f = c.extend(o, f),
                    p, g = i.children(".mCustomScrollBox"),
                    k = g.children(".mCSB_container"),
                    r = g.children(".mCSB_scrollTools"),
                    j = r.children(".mCSB_draggerContainer"),
                    h = j.children(".mCSB_dragger"),
                    t = draggerSpeed = f.scrollInertia,
                    q, s, m, l;
                if (!k.hasClass("mCS_no_scrollbar")) {
                    i.data({
                        mCS_trigger: f.trigger
                    });
                    if (i.data("mCS_Init")) {
                        f.callbacks = false
                    }
                    if (e || e === 0) {
                        if (typeof(e) === "number") {
                            if (f.moveDragger) {
                                p = e;
                                if (i.data("horizontalScroll")) {
                                    e = h.position().left * i.data("scrollAmount")
                                } else {
                                    e = h.position().top * i.data("scrollAmount")
                                }
                                draggerSpeed = 0
                            } else {
                                p = e / i.data("scrollAmount")
                            }
                        } else {
                            if (typeof(e) === "string") {
                                var v;
                                if (e === "top") {
                                    v = 0
                                } else {
                                    if (e === "bottom" && !i.data("horizontalScroll")) {
                                        v = k.outerHeight() - g.height()
                                    } else {
                                        if (e === "left") {
                                            v = 0
                                        } else {
                                            if (e === "right" && i.data("horizontalScroll")) {
                                                v = k.outerWidth() - g.width()
                                            } else {
                                                if (e === "first") {
                                                    v = i.find(".mCSB_container").find(":first")
                                                } else {
                                                    if (e === "last") {
                                                        v = i.find(".mCSB_container").find(":last")
                                                    } else {
                                                        v = i.find(e)
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if (v.length === 1) {
                                    if (i.data("horizontalScroll")) {
                                        e = v.position().left
                                    } else {
                                        e = v.position().top
                                    }
                                    p = e / i.data("scrollAmount")
                                } else {
                                    p = e = v
                                }
                            }
                        }
                        if (i.data("horizontalScroll")) {
                            if (i.data("onTotalScrollBack_Offset")) {
                                s = -i.data("onTotalScrollBack_Offset")
                            }
                            if (i.data("onTotalScroll_Offset")) {
                                l = g.width() - k.outerWidth() + i.data("onTotalScroll_Offset")
                            }
                            if (p < 0) {
                                p = e = 0;
                                clearInterval(i.data("mCSB_buttonScrollLeft"));
                                if (!s) {
                                    q = true
                                }
                            } else {
                                if (p >= j.width() - h.width()) {
                                    p = j.width() - h.width();
                                    e = g.width() - k.outerWidth();
                                    clearInterval(i.data("mCSB_buttonScrollRight"));
                                    if (!l) {
                                        m = true
                                    }
                                } else {
                                    e = -e
                                }
                            }
                            var n = i.data("snapAmount");
                            if (n) {
                                e = Math.round(e / n) * n - i.data("snapOffset")
                            }
                            d.mTweenAxis.call(this, h[0], "left", Math.round(p), draggerSpeed, f.scrollEasing);
                            d.mTweenAxis.call(this, k[0], "left", Math.round(e), t, f.scrollEasing, {
                                onStart: function() {
                                    if (f.callbacks && !i.data("mCS_tweenRunning")) {
                                        u("onScrollStart")
                                    }
                                    if (i.data("autoHideScrollbar")) {
                                        d.showScrollbar.call(r)
                                    }
                                },
                                onUpdate: function() {
                                    if (f.callbacks) {
                                        u("whileScrolling")
                                    }
                                },
                                onComplete: function() {
                                    if (f.callbacks) {
                                        u("onScroll");
                                        if (q || (s && k.position().left >= s)) {
                                            u("onTotalScrollBack")
                                        }
                                        if (m || (l && k.position().left <= l)) {
                                            u("onTotalScroll")
                                        }
                                    }
                                    h.data("preventAction", false);
                                    i.data("mCS_tweenRunning", false);
                                    if (i.data("autoHideScrollbar")) {
                                        if (!g.hasClass("mCS-mouse-over")) {
                                            d.hideScrollbar.call(r)
                                        }
                                    }
                                }
                            })
                        } else {
                            if (i.data("onTotalScrollBack_Offset")) {
                                s = -i.data("onTotalScrollBack_Offset")
                            }
                            if (i.data("onTotalScroll_Offset")) {
                                l = g.height() - k.outerHeight() + i.data("onTotalScroll_Offset")
                            }
                            if (p < 0) {
                                p = e = 0;
                                clearInterval(i.data("mCSB_buttonScrollUp"));
                                if (!s) {
                                    q = true
                                }
                            } else {
                                if (p >= j.height() - h.height()) {
                                    p = j.height() - h.height();
                                    e = g.height() - k.outerHeight();
                                    clearInterval(i.data("mCSB_buttonScrollDown"));
                                    if (!l) {
                                        m = true
                                    }
                                } else {
                                    e = -e
                                }
                            }
                            var n = i.data("snapAmount");
                            if (n) {
                                e = Math.round(e / n) * n - i.data("snapOffset")
                            }
                            d.mTweenAxis.call(this, h[0], "top", Math.round(p), draggerSpeed, f.scrollEasing);
                            d.mTweenAxis.call(this, k[0], "top", Math.round(e), t, f.scrollEasing, {
                                onStart: function() {
                                    if (f.callbacks && !i.data("mCS_tweenRunning")) {
                                        u("onScrollStart")
                                    }
                                    if (i.data("autoHideScrollbar")) {
                                        d.showScrollbar.call(r)
                                    }
                                },
                                onUpdate: function() {
                                    if (f.callbacks) {
                                        u("whileScrolling")
                                    }
                                },
                                onComplete: function() {
                                    if (f.callbacks) {
                                        u("onScroll");
                                        if (q || (s && k.position().top >= s)) {
                                            u("onTotalScrollBack")
                                        }
                                        if (m || (l && k.position().top <= l)) {
                                            u("onTotalScroll")
                                        }
                                    }
                                    h.data("preventAction", false);
                                    i.data("mCS_tweenRunning", false);
                                    if (i.data("autoHideScrollbar")) {
                                        if (!g.hasClass("mCS-mouse-over")) {
                                            d.hideScrollbar.call(r)
                                        }
                                    }
                                }
                            })
                        }
                        if (i.data("mCS_Init")) {
                            i.data({
                                mCS_Init: false
                            })
                        }
                    }
                }

                function u(w) {
                    this.mcs = {
                        top: k.position().top,
                        left: k.position().left,
                        draggerTop: h.position().top,
                        draggerLeft: h.position().left,
                        topPct: Math.round((100 * Math.abs(k.position().top)) / Math.abs(k.outerHeight() - g.height())),
                        leftPct: Math.round((100 * Math.abs(k.position().left)) / Math.abs(k.outerWidth() - g.width()))
                    };
                    switch (w) {
                        case "onScrollStart":
                            i.data("mCS_tweenRunning", true).data("onScrollStart_Callback").call(i, this.mcs);
                            break;
                        case "whileScrolling":
                            i.data("whileScrolling_Callback").call(i, this.mcs);
                            break;
                        case "onScroll":
                            i.data("onScroll_Callback").call(i, this.mcs);
                            break;
                        case "onTotalScrollBack":
                            i.data("onTotalScrollBack_Callback").call(i, this.mcs);
                            break;
                        case "onTotalScroll":
                            i.data("onTotalScroll_Callback").call(i, this.mcs);
                            break
                    }
                }
            },
            stop: function() {
                var g = c(this),
                    e = g.children().children(".mCSB_container"),
                    f = g.children().children().children().children(".mCSB_dragger");
                d.mTweenAxisStop.call(this, e[0]);
                d.mTweenAxisStop.call(this, f[0])
            },
            disable: function(e) {
                var j = c(this),
                    f = j.children(".mCustomScrollBox"),
                    h = f.children(".mCSB_container"),
                    g = f.children(".mCSB_scrollTools"),
                    i = g.children().children(".mCSB_dragger");
                f.unbind("mousewheel focusin mouseenter mouseleave touchend");
                h.unbind("touchstart touchmove");
                if (e) {
                    if (j.data("horizontalScroll")) {
                        i.add(h).css("left", 0)
                    } else {
                        i.add(h).css("top", 0)
                    }
                }
                g.css("display", "none");
                h.addClass("mCS_no_scrollbar");
                j.data({
                    bindEvent_mousewheel: false,
                    bindEvent_focusin: false,
                    bindEvent_content_touch: false,
                    bindEvent_autoHideScrollbar: false
                }).addClass("mCS_disabled")
            },
            destroy: function() {
                var e = c(this);
                e.removeClass("mCustomScrollbar _mCS_" + e.data("mCustomScrollbarIndex")).addClass("mCS_destroyed").children().children(".mCSB_container").unwrap().children().unwrap().siblings(".mCSB_scrollTools").remove();
                c(document).unbind("mousemove." + e.data("mCustomScrollbarIndex") + " mouseup." + e.data("mCustomScrollbarIndex") + " MSPointerMove." + e.data("mCustomScrollbarIndex") + " MSPointerUp." + e.data("mCustomScrollbarIndex"));
                c(window).unbind("resize." + e.data("mCustomScrollbarIndex"))
            }
        },
        d = {
            showScrollbar: function() {
                this.stop().animate({
                    opacity: 1
                }, "fast")
            },
            hideScrollbar: function() {
                this.stop().animate({
                    opacity: 0
                }, "fast")
            },
            mTweenAxis: function(g, i, h, f, o, y) {
                var y = y || {},
                    v = y.onStart || function() {},
                    p = y.onUpdate || function() {},
                    w = y.onComplete || function() {};
                var n = t(),
                    l, j = 0,
                    r = g.offsetTop,
                    s = g.style;
                if (i === "left") {
                    r = g.offsetLeft
                }
                var m = h - r;
                q();
                e();

                function t() {
                    if (window.performance && window.performance.now) {
                        return window.performance.now()
                    } else {
                        if (window.performance && window.performance.webkitNow) {
                            return window.performance.webkitNow()
                        } else {
                            if (Date.now) {
                                return Date.now()
                            } else {
                                return new Date().getTime()
                            }
                        }
                    }
                }

                function x() {
                    if (!j) {
                        v.call()
                    }
                    j = t() - n;
                    u();
                    if (j >= g._time) {
                        g._time = (j > g._time) ? j + l - (j - g._time) : j + l - 1;
                        if (g._time < j + 1) {
                            g._time = j + 1
                        }
                    }
                    if (g._time < f) {
                        g._id = _request(x)
                    } else {
                        w.call()
                    }
                }

                function u() {
                    if (f > 0) {
                        g.currVal = k(g._time, r, m, f, o);
                        s[i] = Math.round(g.currVal) + "px"
                    } else {
                        s[i] = h + "px"
                    }
                    p.call()
                }

                function e() {
                    l = 1000 / 60;
                    g._time = j + l;
                    _request = (!window.requestAnimationFrame) ? function(z) {
                        u();
                        return setTimeout(z, 0.01)
                    } : window.requestAnimationFrame;
                    g._id = _request(x)
                }

                function q() {
                    if (g._id == null) {
                        return
                    }
                    if (!window.requestAnimationFrame) {
                        clearTimeout(g._id)
                    } else {
                        window.cancelAnimationFrame(g._id)
                    }
                    g._id = null
                }

                function k(B, A, F, E, C) {
                    switch (C) {
                        case "linear":
                            return F * B / E + A;
                            break;
                        case "easeOutQuad":
                            B /= E;
                            return -F * B * (B - 2) + A;
                            break;
                        case "easeInOutQuad":
                            B /= E / 2;
                            if (B < 1) {
                                return F / 2 * B * B + A
                            }
                            B--;
                            return -F / 2 * (B * (B - 2) - 1) + A;
                            break;
                        case "easeOutCubic":
                            B /= E;
                            B--;
                            return F * (B * B * B + 1) + A;
                            break;
                        case "easeOutQuart":
                            B /= E;
                            B--;
                            return -F * (B * B * B * B - 1) + A;
                            break;
                        case "easeOutQuint":
                            B /= E;
                            B--;
                            return F * (B * B * B * B * B + 1) + A;
                            break;
                        case "easeOutCirc":
                            B /= E;
                            B--;
                            return F * Math.sqrt(1 - B * B) + A;
                            break;
                        case "easeOutSine":
                            return F * Math.sin(B / E * (Math.PI / 2)) + A;
                            break;
                        case "easeOutExpo":
                            return F * (-Math.pow(2, -10 * B / E) + 1) + A;
                            break;
                        case "mcsEaseOut":
                            var D = (B /= E) * B,
                                z = D * B;
                            return A + F * (0.499999999999997 * z * D + -2.5 * D * D + 5.5 * z + -6.5 * D + 4 * B);
                            break;
                        case "draggerRailEase":
                            B /= E / 2;
                            if (B < 1) {
                                return F / 2 * B * B * B + A
                            }
                            B -= 2;
                            return F / 2 * (B * B * B + 2) + A;
                            break
                    }
                }
            },
            mTweenAxisStop: function(e) {
                if (e._id == null) {
                    return
                }
                if (!window.requestAnimationFrame) {
                    clearTimeout(e._id)
                } else {
                    window.cancelAnimationFrame(e._id)
                }
                e._id = null
            },
            rafPolyfill: function() {
                var f = ["ms", "moz", "webkit", "o"],
                    e = f.length;
                while (--e > -1 && !window.requestAnimationFrame) {
                    window.requestAnimationFrame = window[f[e] + "RequestAnimationFrame"];
                    window.cancelAnimationFrame = window[f[e] + "CancelAnimationFrame"] || window[f[e] + "CancelRequestAnimationFrame"]
                }
            }
        };
    d.rafPolyfill.call();
    c.support.touch = !!("ontouchstart" in window);
    c.support.msPointer = window.navigator.msPointerEnabled;
    var a = ("https:" == document.location.protocol) ? "https:" : "http:";
    c.event.special.mousewheel || document.write('<script src="' + a + '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.6/jquery.mousewheel.min.js"><\/script>');
    c.fn.mCustomScrollbar = function(e) {
        if (b[e]) {
            return b[e].apply(this, Array.prototype.slice.call(arguments, 1))
        } else {
            if (typeof e === "object" || !e) {
                return b.init.apply(this, arguments)
            } else {
                c.error("Method " + e + " does not exist")
            }
        }
    }
})(jQuery);
/*
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function(a) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], a)
    } else {
        a(jQuery)
    }
}(function(f) {
    var a = /\+/g;

    function d(i) {
        return b.raw ? i : encodeURIComponent(i)
    }


    function g(i) {
        return b.raw ? i : decodeURIComponent(i)
    }

    function h(i) {
        return d(b.json ? JSON.stringify(i) : String(i))
    }

    function c(i) {
        if (i.indexOf('"') === 0) {
            i = i.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\")
        }
        try {
            i = decodeURIComponent(i.replace(a, " "));
            return b.json ? JSON.parse(i) : i
        } catch (j) {}
    }

    function e(j, i) {
        var k = b.raw ? j : c(j);
        return f.isFunction(i) ? i(k) : k
    }
    var b = f.cookie = function(q, p, v) {
        if (p !== undefined && !f.isFunction(p)) {
            v = f.extend({}, b.defaults, v);
            if (typeof v.expires === "number") {
                var r = v.expires,
                    u = v.expires = new Date();
                u.setDate(u.getDate() + r)
            }
            return (document.cookie = [d(q), "=", h(p), v.expires ? "; expires=" + v.expires.toUTCString() : "", v.path ? "; path=" + v.path : "", v.domain ? "; domain=" + v.domain : "", v.secure ? "; secure" : ""].join(""))
        }
        var w = q ? undefined : {};
        var s = document.cookie ? document.cookie.split("; ") : [];
        for (var o = 0, m = s.length; o < m; o++) {
            var n = s[o].split("=");
            var j = g(n.shift());
            var k = n.join("=");
            if (q && q === j) {
                w = e(k, p);
                break
            }
            if (!q && (k = e(k)) !== undefined) {
                w[j] = k
            }
        }
        return w
    };
    b.defaults = {};
    f.removeCookie = function(j, i) {
        if (f.cookie(j) === undefined) {
            return false
        }
        f.cookie(j, "", f.extend({}, i, {
            expires: -1
        }));
        return !f.cookie(j)
    }
}));
var is_touchable = Boolean("ontouchstart" in window);
is_touchable = false;
jQuery.fn.popover.Constructor.prototype.tip = function() {
    if (!this.$tip) {
        this.$tip = $(this.options.template)
    }
    if (this.options["class"]) {
        this.$tip.addClass(this.options["class"])
    }
    return this.$tip
};

function initPopoverForTouch(a) {
    var b = jQuery(a).popover({
        trigger: "manual"
    });
    b.click(function() {
        var c = $(this);
        if ($(".popover.in").length) {
            $(".be-backpop").remove();
            $(".popover.in").remove()
        } else {
            c.popover("show")
        }
        this.stopPropagation()
    });
    b.on("shown.bs.popover", function() {
        var c = $(this);
        var e = $(".popover.in");
        var d = $('<div class="be-backpop"></div>').css({
            position: "fixed",
            top: 0,
            bottom: 0,
            right: 0,
            left: 0,
            "z-index": 1005
        });
        d.insertAfter(e);
        $(".be-backpop").click(function() {
            $(".popover.in").remove();
            $(".be-backpop").remove()
        })
    })
}
$(function() {
    if ("ontouchstart" in window) {
        initPopoverForTouch("[rel=popover-hold],[data-toggle=popover]")
    } else {
        jQuery("[rel=popover-hold]").popover({
            animation: false
        }).on("mouseenter", function() {
            var a = this;
            $(this).popover("show");
            $(this).siblings(".popover").on("mouseleave", function() {
                $(a).popover("hide")
            })
        }).on("mouseleave", function() {
            var a = this;
            setTimeout(function() {
                if (!$(".popover:hover").length) {
                    $(a).popover("hide")
                }
            }, 200)
        });
        jQuery("a[data-toggle=popover]").popover()
    }
    jQuery("a[data-toggle=tooltip]").tooltip()
});
if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "")
    }
};

function createBackdrop(b) {
    $(".be-dropdown-backdrop").remove();
    var a = $('<div class="be-dropdown-backdrop"></div>');
    a.click(function() {
        $(".dropdown .open").removeClass("open");
        $(".be-dropdown-backdrop").remove()
    });
    a.insertAfter($(b))
}

function init_topnav_popup(a) {
    if ("ontouchstart" in document) {
        var b = $(a);
        b.attr("onclick", "javascript: return false;");
        b.click(function() {
            if ($(this).parent().hasClass("open")) {
                window.location.href = this.href
            } else {
                var c = window.innerWidth ? window.innerWidth : $(window).width();
                $(this).parent().parent().parent().children().children(".open").removeClass("open");
                if (c > 767) {
                    createBackdrop(this)
                }
                $(this).parent().addClass("open");
                $(this).parent().trigger("show.bs.dropdown")
            }
        })
    } else {
        $(a).dropdownHover()
    }
};

function update_single_icon(a) {
    var b = $('[data-wishlistitems="' + a + '"]');
    b.addClass("niwl");
    b.removeAttr("data-wishlistitems")
}

function validata_before_move_to_cart(d) {
    var b = $(d).closest(".item");
    var c = b.find("select");
    var e = [];
    var a = true;
    c.each(function(f, g) {
        var h = $(g);
        if (!h.val()) {
            a = false;
            e.push(h.closest("form").find(".label-name").html())
        }
    });
    return {
        result: a,
        message: e.join(",") + " required."
    }
}

function echeck(a) {
    if (a.match(/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/)) {
        return true
    } else {
        if (a != "") {
            alert("Invalid E-Mail Address")
        } else {
            alert("Please enter your Email Address")
        }
        return false
    }
};

function goto_page(a) {
    window.location.href = a;
    return false
}

function number_with_commas(a) {
    return a.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function delay_run(b, c) {
    if (typeof b !== "function") {
        return
    }
    var c = jQuery.extend(true, {
        gid: "globalTimeout",
        time_out: 1000
    }, c);
    if (typeof c.time_out !== "number") {
        c.time_out = 1000
    }
    if (typeof c.gid !== "string") {
        c.gid = "globalTimeout"
    }
    var a = c.gid;
    var d = c.time_out;
    if (window[a] !== undefined) {
        clearTimeout(window[a]);
        window[a] = undefined
    }
    window[a] = setTimeout(function() {
        b();
        window[a] = undefined
    }, d)
};
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(b) {
        var a = this.length >>> 0;
        var c = Number(arguments[1]) || 0;
        c = (c < 0) ? Math.ceil(c) : Math.floor(c);
        if (c < 0) {
            c += a
        }
        for (; c < a; c++) {
            if (c in this && this[c] === b) {
                return c
            }
        }
        return -1
    }
}
if (!String.prototype.startsWith) {
    Object.defineProperty(String.prototype, "startsWith", {
        enumerable: false,
        configurable: false,
        writable: false,
        value: function(b, a) {
            a = a || 0;
            return this.indexOf(b, a) === a
        }
    })
}
if (!String.prototype.endsWith) {
    Object.defineProperty(String.prototype, "endsWith", {
        enumerable: false,
        configurable: false,
        writable: false,
        value: function(b, a) {
            a = a || this.length;
            a = a - b.length;
            var c = this.lastIndexOf(b);
            return c !== -1 && c === a
        }
    })
}
var Sys = {};
var ua = navigator.userAgent.toLowerCase();
if (window.ActiveXObject) {
    Sys.ie = ua.match(/msie ([\d.]+)/)[1]
}
Sys.is_ipad = ua.match(/ipad/i) !== null;
Sys.is_android = ua.match(/android/i) !== null;
Sys.is_iphone = ua.match(/iphone/i) !== null;
jQuery.ajaxSetup({
    cache: false
});

function readCookie_utmz() {
    var f = "_utmz";
    var b = f + "=";
    var e = document.cookie.split(";");
    for (var a = 0; a < e.length; a++) {
        var d = e[a];
        if (d.indexOf(f) != -1) {
            return unescape(d.substring(d.indexOf(b) + b.length, d.length))
        }
    }
    return "174573681.1260263105.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)"
}
var beCKutmz = readCookie_utmz();

function change_currency(b) {
    var a = $(b).val();
    window.location.href = "/change_currency/?to=" + a
}

function auto_popup() {
    var b = parent.location.search;
    var e = {};
    if (b.indexOf("?") != -1) {
        var g = b.substr(b.indexOf("?") + 1);
        strs = g.split("&");
        for (var d = 0; d < strs.length; d++) {
            e[strs[d].split("=")[0]] = unescape(strs[d].split("=")[1])
        }
    }
    var c = jQuery.cookie("view_ct");
    var a = 0;
    if (c) {
        a = parseInt(c, 10)
    }
    var f;
    if (a <= 10) {
        f = a + 1
    }
    if (no_popup() && f == 1) {
        f = 0
    }
    jQuery.cookie("view_ct", f, {
        expires: 365,
        path: "/"
    });
    if (f == 1) {
        $("#join-our-newsletter").modal("show")
    }
}

function no_popup() {
    var c = window.location.href;
    var b = "[hH][tT]{2}[pP]://[\\w:.]{1,30}/(?:";
    var d = new Array("contact_success", "product_detail_contact_success", "email_success", "ring_sizer_success", "Subscribed.aspx", "schedule_appointment_success", "contact", "review/.*", "request-info/.*", "shop/.*");
    var a = b + d.join("|") + ")[/]{0,1}";
    if (c.match(a)) {
        return true
    }
    return false
}

function popup(b, a, c) {
    c = "scrollbars=yes,width=810,height=550,top=0,left=0";
    a = "popup";
    window.open(b, a, c)
}
var phone_number = "18006910952";

function GetPhoneNumber() {
    return phone_number
}

function async_script(d, c, e) {
    var a = document.createElement("script");
    a.type = "text/javascript";
    a.setAttribute("async", "true");
    a.src = d;
    var b;
    if (c == "id") {
        b = document.getElementById(e)
    }
    if (c == "tag") {
        b = document.getElementsByTagName(e)[0]
    }
    if (b !== undefined) {
        b.parentNode.insertBefore(a, b);
        return a
    }
}

function set_version_to_cookie(e, c, f, a) {
    var d = "pc";
    if (is_mobile) {
        d = "m"
    }
    if (is_ipad) {
        d = "t"
    }
    var b = [d, e, c, f, a].join("|");
    jQuery.cookie("_dist", b, {
        path: "/"
    })
};