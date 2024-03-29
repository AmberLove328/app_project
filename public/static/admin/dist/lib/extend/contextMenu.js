
layui.define(["jquery"], function(a) {
    var c = layui.jquery;
    var b = {
        bind: function(e, d) {
            c(e).bind("contextmenu", function(f) {
                b.show(d, f.clientX, f.clientY);
                return false
            })
        },
        getHtml: function(e, d) {
            var h = "";
            for (var f = 0; f < e.length; f++) {
                var g = e[f];
                g.itemId = "ctxMenu-" + d + f;
                if (g.subs && g.subs.length > 0) {
                    h += '<div class="ctxMenu-item haveMore" lay-id="' + g.itemId + '">';
                    h += "<a>";
                    if (g.icon) {
                        h += '<i class="' + g.icon + ' ctx-icon"></i>'
                    }
                    h += g.name;
                    h += '<i class="layui-icon layui-icon-right icon-more"></i>';
                    h += "</a>";
                    h += '<div class="ctxMenu-sub" style="display: none;">';
                    h += b.getHtml(g.subs, d + f);
                    h += "</div>"
                } else {
                    h += '<div class="ctxMenu-item" lay-id="' + g.itemId + '">';
                    h += "<a>";
                    if (g.icon) {
                        h += '<i class="' + g.icon + ' ctx-icon"></i>'
                    }
                    h += g.name;
                    h += "</a>"
                }
                h += "</div>";
                if (g.hr == true) {
                    h += "<hr/>"
                }
            }
            return h
        },
        setEvents: function(d) {
            for (var e = 0; e < d.length; e++) {
                var f = d[e];
                if (f.click) {
                    c(".ctxMenu").on("click", '[lay-id="' + f.itemId + '"]', f.click)
                }
                if (f.subs && f.subs.length > 0) {
                    b.setEvents(f.subs)
                }
            }
        },
        remove: function() {
            var h = top.window.frames;
            for (var d = 0; d < h.length; d++) {
                var f = h[d];
                try {
                    f.layui.jquery("body>.ctxMenu").remove()
                } catch (g) {}
            }
            try {
                top.layui.jquery("body>.ctxMenu").remove()
            } catch (g) {}
        },
        getPageHeight: function() {
            return document.documentElement.clientHeight || document.body.clientHeight
        },
        getPageWidth: function() {
            return document.documentElement.clientWidth || document.body.clientWidth
        },
        show: function(e, d, i) {
            var f = "left: " + d + "px; top: " + i + "px;";
            var h = '<div class="ctxMenu" style="' + f + '">';
            h += b.getHtml(e, "");
            h += "   </div>";
            b.remove();
            c("body").append(h);
            var g = c(".ctxMenu");
            if (d + g.outerWidth() > b.getPageWidth()) {
                d -= g.outerWidth()
            }
            if (i + g.outerHeight() > b.getPageHeight()) {
                i = i - g.outerHeight();
                if (i < 0) {
                    i = 0
                }
            }
            g.css({
                "top": i,
                "left": d
            });
            b.setEvents(e);
            c(".ctxMenu-item.haveMore").on("mouseenter", function() {
                var j = c(this).find(">a");
                var k = c(this).find(">.ctxMenu-sub");
                var m = j.offset().top;
                var l = j.offset().left + j.outerWidth();
                if (l + k.outerWidth() > b.getPageWidth()) {
                    l = j.offset().left - k.outerWidth()
                }
                if (m + k.outerHeight() > b.getPageHeight()) {
                    m = m - k.outerHeight() + j.outerHeight();
                    if (m < 0) {
                        m = 0
                    }
                }
                c(this).find(">.ctxMenu-sub").css({
                    "top": m,
                    "left": l,
                    "display": "block"
                })
            }).on("mouseleave", function() {
                c(this).find(">.ctxMenu-sub").css("display", "none")
            })
        },
        getCommonCss: function() {
            var d = ".ctxMenu, .ctxMenu-sub {";
            d += "        max-width: 250px;";
            d += "        min-width: 110px;";
            d += "        background: white;";
            d += "        border-radius: 2px;";
            d += "        padding: 5px 0;";
            d += "        white-space: nowrap;";
            d += "        position: fixed;";
            d += "        z-index: 2147483647;";
            d += "        box-shadow: 0 2px 4px rgba(0, 0, 0, .12);";
            d += "        border: 1px solid #d2d2d2;";
            d += "        overflow: visible;";
            d += "   }";
            d += "   .ctxMenu-item {";
            d += "        position: relative;";
            d += "   }";
            d += "   .ctxMenu-item > a {";
            d += "        font-size: 14px;";
            d += "        color: #666;";
            d += "        padding: 0 26px 0 35px;";
            d += "        cursor: pointer;";
            d += "        display: block;";
            d += "        line-height: 36px;";
            d += "        text-decoration: none;";
            d += "        position: relative;";
            d += "   }";
            d += "   .ctxMenu-item > a:hover {";
            d += "        background: #f2f2f2;";
            d += "        color: #666;";
            d += "   }";
            d += "   .ctxMenu-item > a > .icon-more {";
            d += "        position: absolute;";
            d += "        right: 5px;";
            d += "        top: 0;";
            d += "        font-size: 12px;";
            d += "        color: #666;";
            d += "   }";
            d += "   .ctxMenu-item > a > .ctx-icon {";
            d += "        position: absolute;";
            d += "        left: 12px;";
            d += "        top: 0;";
            d += "        font-size: 15px;";
            d += "        color: #666;";
            d += "   }";
            d += "   .ctxMenu hr {";
            d += "        background-color: #e6e6e6;";
            d += "        clear: both;";
            d += "        margin: 5px 0;";
            d += "        border: 0;";
            d += "        height: 1px;";
            d += "   }";
            d += "   .ctx-ic-lg {";
            d += "        font-size: 18px !important;";
            d += "        left: 11px !important;";
            d += "    }";
            return d
        }
    };
    c(document).off("click.ctxMenu").on("click.ctxMenu", function() {
        b.remove()
    });
    c(document).off("click.ctxMenuMore").on("click.ctxMenuMore", ".ctxMenu-item", function(d) {
        if (c(this).hasClass("haveMore")) {
            if (d !== void 0) {
                d.preventDefault();
                d.stopPropagation()
            }
        } else {
            b.remove()
        }
    });
    c("head").append("<style>" + b.getCommonCss() + "</style>");
    a("contextMenu", b)
});
