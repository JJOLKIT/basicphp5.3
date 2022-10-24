// as is 인증번호 발급 공통
function _fncGotoSafeKey() {
    var keyURL = "http://c.coway.co.kr/Safekey/auth/index.aspx?mcode=GNBBOT_01";
    if (location.href.toLowerCase().indexOf('www.coway.co.kr') > -1) {
        keyURL = "http://c.coway.co.kr/Safekey/auth/index.aspx";
    }

    var wintitle = "SafeKey발급";
    var width = 501;
    var height = 560;
    var attr = "toolbar=0, menubar=0, location=0, directories=0, status=0, scrollbars=no, resizable=0, top=5, left=50";


    if (navigator.userAgent.indexOf("MSIE 8") > -1
    || navigator.userAgent.indexOf("MSIE 9") > -1
    || navigator.userAgent.indexOf("MSIE 10") > -1
    || navigator.userAgent.indexOf("MSIE 11") > -1) {
            width = width - 5;
    }
    else if (navigator.userAgent.indexOf("Chrome") > -1) { width = width + 0; height = height + 12; }

    var pop = window.open(keyURL, wintitle, 'width=' + width + 'px, height=' + height + 'px,' + attr);
    pop.focus();
}

if (typeof Object.create != 'function') {
	Object.create = (function () {
		var Temp = function () { };
		return function (prototype) {
			if (arguments.length > 1) {
				throw Error('Second argument not supported');
			}
			if (prototype !== Object(prototype) && prototype !== null) {
				throw TypeError('Argument must be an object or null');
			}
			if (prototype === null) {
				throw Error('null [[Prototype]] not supported');
			}
			Temp.prototype = prototype;
			var result = new Temp();
			Temp.prototype = null;
			return result;
		};
	})();	/*	End Object.create	*/
};

var jCoway = (function (a, b, undefined) {
	var p = {	/*	p	*/
		version: "1.0.0",
		config: {
			IS_LOCAL_URL: location.hostname.toLowerCase().match(/localhost/i),
			IS_TEST_URL: location.hostname.toLowerCase().match(/ncwu.coway.co.kr/i),
			IS_REAL_URL: location.hostname.toLowerCase().match(/www.coway.co.kr/i),
			IS_REAL_N_URL: location.hostname.toLowerCase().match(/www.coway.co.kr/i),
			IS_DEV_URL: location.hostname.toLowerCase().match(/ncwadmin.coway.co.kr/i) || location.hostname.toLowerCase().match(/localhost/i) || location.hostname.toLowerCase().match(/ncwu.coway.co.kr/i),
			URL_MEMBER: function () {
				return this.IS_DEV_URL ? 'https://tvoc.coway.co.kr' : 'https://help.coway.co.kr';
			},
			KEY_URL: function () {
			    if (this.IS_REAL_URL || this.IS_REAL_N_URL) {
					return 'http://c.coway.co.kr/Safekey/auth/index.aspx';
				} else {
					return 'http://c.coway.co.kr/Safekey/auth/index.aspx?mcode=GNBBOT_01';
				}
			},
			CUR_URL: function () {
				if (this.IS_REAL_URL) {
					return 'http://www.coway.co.kr';
				} else if (this.IS_REAL_N_URL) {
				    return 'http://www.coway.co.kr';
				} else if (this.IS_TEST_URL) {
					return 'http://ncwu.coway.co.kr';
				} else {
					return location.protocol + "//" + location.host;
				}
			},
			SSL_URL: function () {
				if (this.IS_REAL_URL) {
					return 'https://www.coway.co.kr';
				} else if (this.IS_REAL_N_URL) {
				    return 'https://www.coway.co.kr';
				} else if (this.IS_TEST_URL) {
				    return 'https://ncwu.coway.co.kr';
				} else if (this.IS_LOCAL_URL) {
				    return location.protocol + "//" + location.host;
				}
			},
			MALL_URL: function () {
				if (this.IS_REAL_URL) {
					return 'http://mall.coway.co.kr';
				} else if (this.IS_REAL_N_URL) {
				    return 'http://wwww.coway.co.kr';
				} else if (this.IS_TEST_URL) {
					return 'http://mall.coway.co.kr';
				} else {
					return 'http://localhost:8848';
				}
			},
			CODY_URL: function () {
				if (this.IS_REAL_URL || this.IS_REAL_N_URL) {
					return 'https://c.coway.co.kr:448';
				} else {
					return 'https://nc.coway.co.kr';
				}
			},
			pc: function() {
				return a(window).width() > 768;
			}
		},
		keyCode: {	/*	p.keyCode	*/
			ALT: 18,
			BACKSPACE: 8,
			CAPS_LOCK: 20,
			COMMA: 188,
			COMMAND: 91,
			COMMAND_LEFT: 91, /*	COMMAND	*/
			COMMAND_RIGHT: 93,
			CONTROL: 17,
			DELETE: 46,
			DOWN: 40,
			END: 35,
			ENTER: 13,
			ESCAPE: 27,
			HOME: 36,
			INSERT: 45,
			LEFT: 37,
			MENU: 93, /*	COMMAND_RIGHT	*/
			NUMPAD_ADD: 107,
			NUMPAD_DECIMAL: 110,
			NUMPAD_DIVIDE: 111,
			NUMPAD_ENTER: 108,
			NUMPAD_MULTIPLY: 106,
			NUMPAD_SUBTRACT: 109,
			PAGE_DOWN: 34,
			PAGE_UP: 33,
			PERIOD: 190,
			RIGHT: 39,
			SHIFT: 16,
			SPACE: 32,
			TAB: 9,
			UP: 38,
			WINDOWS: 91 /*	COMMAND	*/
		},	/*	End p.keyCode	*/
		escape: {	/*	p.escape	*/
			BACKSPACE: '\b',
			TAB: '\t',
			LINE_FEED: '\n',
			VERTICAL_TAB: '\v',
			FORM_FEED: '\f',
			CARRIAGE_RETURN: '\r',
			DOUBLE_QUOTATION: '\"',
			SINGLE_QUOTATION: '\'',
			BACKSLASH: '\\'
		},	/*	End p.escape	*/
		color: {	/*	p.color	*/
			white: '#ffffff',
			black: '#000000'
		},	/*	End p.color	*/
		html: {	/*	p.html	*/
			space: '&nbsp;',
			quotation: '&quot;',
			ampersand: '&amp;',
			less: '&lt;',
			greater: '&gt;',
			bullet: '&bull;'
		},	/*	End p.html	*/
		path: {	/*	p.path	*/
			test: 'test'
		},	/*	End p.path	*/
		regular: {	/*	p.regular	*/
			int: /[0-9 -()+]+$/,
			price: /^(\d*([,](?=\d{3}))?\d+)+((?!\2)[,]\d\d)?$/,/* /^(\d*([.,](?=\d{3}))?\d+)+((?!\2)[.,]\d\d)?$/,	*/
			ip: '\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b',
			num0to255: '^([01][0-9][0-9]|2[0-4][0-9]|25[0-5])$',
			percentFloat: /^\d{0,3}(?:\.\d{0,2}){0,1}$/,	/* '[-+]?([0-9]*\.[0-9]+|[0-9]+)', */
			number1to50Regex: /(^[1-9]{1}$|^[1-4]{1}[0-9]{1}$|^50$)/gm,
			email: '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$',
			creditCard: '^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$',
			/*username: '/^[a-z0-9_-]{3,16}$/',
			password: '/^[a-z0-9_-]{6,18}$/',
			passwordStrength: /((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15})/gm,*/
			phoneNumber: /[0-9-()+]{3,20}/,
			url: '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
			urlslug: '/^[a-z0-9-]+$/',
			whiteSpace: '^[ \t]+',
			whiteSpaceAndTabs: '^[ \t]+|[ \t]+$',
			whiteSpaceAndLinebreaks: '[ \t\r\n]',
			image: /([^\s]+(?=\.(jpg|gif|png))\.\2)/gm,
			excel: /([^\s]+(?=\.(csv|xls|xlsx))\.\2)/gm,
			csv: /([^\s]+(?=\.(csv))\.\2)/gm,
			htmlTag: '/^<([a-z]+)([^<]+)*(?:>(.*)<\/\1>|\s+\/>)$/',
			jsTag: /<script.+?src=\"(.+?\.js(?:\?v=\d)*).+?script>/ig,
			cssTag: /<link.+?href=\"(.+?\.css(?:\?v=\d)*).+?>/ig,
			date: /^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/
		},	/*	End p.regular	*/
		isNull: function (v) {	/*	p.isNull	*/
			return (v != undefined) ? !/[^\s]/g.test(v) : true;
		},	/*	End p.isNull	*/
		trim: function (v) {	/*	p.trim	*/
			return v.replace(/^\s*|\s*$/g, '');
		},	/*	End p.trim	*/
		trimAll: function (v) {	/*	p.trimAll	*/
			return v.replace(/ /gim, '');
		},	/*	End p.trimAll	*/
		replace: function (origin, target, replace) {	/*	p.replace	*/
			var s = new String();
			for (i = 0; i < origin.length; i++)
				if (origin.charAt(i) != target) s = s + origin.charAt(i);
				else
					s = s + replace;
			return s;
		},	/*	End p.replace	*/
		leftPad: function (v, len, s) {	/*	p.leftPad	*/
			var t = '0';
			if (s !== undefined) t = s;
			var r = v.toString();
			while (r.length < len) r = t + r;
			return r;
		},	/*	End p.leftPad	*/
		comma: function (v) {	/*	p.comma	*/
			v = String(v);
			var m = v.substring(0, 1);

			v = v.replace(/[^\d]+/g, '');
			v = v.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
			if (m == "-") v = "-" + v;

			return v;
		},	/*	End p.comma	*/
		unComma: function (v) {	/*	p.unComma	*/
			v = String(v);
			var m = v.substring(0, 1);

			v = v.replace(/[^\d]+/g, '');
			if (m == "-") v = "-" + v;

			return v;
		},	/*	End p.unComma	*/
		formatDate: function (v, seperator) {	/*	p.formatDate	*/
			var r = new String();
			if (b.isNull(v)) return '';

			var dateString = b.trim(b.replace(b.replace(b.replace(v, '-', ''), '.', ''), '/', ''));
			if (dateString.length < 4) r = v;
			if (dateString.length < 6) r = dateString.substring(0, 4) + seperator + dateString.substring(4);
			if (dateString.length < 8) r = dateString.substring(0, 4) + seperator + dateString.substring(4, 6) + seperator + dateString.substring(6);

			if (r == '') {
				dateString = dateString.substring(0, 4) + '/' + dateString.substring(4, 6) + '/' + dateString.substring(6, 8);
				var date = new Date(dateString);
				var currDate = date.getDate();
				var currMonth = date.getMonth();
				var currYear = date.getFullYear();

				currMonth++;

				if (currDate <= 9) currDate = '0' + date.getDate();
				if (currYear == '1900') r = v;
				else r = currYear + seperator + b.leftPad(currMonth, 2) + seperator + currDate;
			}

			return r;
		},	/*	End p.formatDate	*/
		formatDate2: function (v, seperator) {	/*	p.formatDate2	*/
		    var year = v.getFullYear();
		    var month = v.getMonth() + 1;
		    var day = v.getDate();

		    if (('' + month).length == 1) { month = '0' + month; }
		    if (('' + day).length == 1) { day = '0' + day; }

		    return (year + seperator + month + seperator + day);

		},	/*	End p.formatDate2	*/
		ajax: function (settings) {	/*	p.ajax	*/
			var win = a(window);
			var attr = {
				loading: false,
				loadingType: '',
				/*loadingBar: a('<div />').append(a('<img />').attr({ src: "/images/roading.gif" })).css({
					'position': 'absolute',
					'left': (win.width() - 160) / 2 + win.scrollLeft(),
					'top': (win.height() - 24) / 2 + win.scrollTop(),
					'z-index': 1000
				}),*/
				loadingTime: 0,
				type: 'POST',
				url: '',
				data: {},
				timeout: 0,
				context: document.body,
				async: true,
				cache: false,
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				beforeSend: a.noop,
				complete: a.noop,
				success: a.noop,
				error: a.noop,
				login: false
			};
			if (settings) a.extend(attr, settings);

			if (attr.login) {
				if (!this.isLogin({ go: true })) {
					return false;
				};
			};

			var config = {
				type: attr.type,
				url: attr.url,
				data: attr.data,
				timeout: attr.timeout,
				context: attr.context,
				async: attr.async,
				cache: attr.cache,
				contentType: attr.contentType,
				dataType: attr.dataType,
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
					/*if (attr.loading === true) {
						a('body').append(attr.loadingBar);
					};*/
					if ((attr.loading === true) && (attr.async === true)) {
						p.loadingBar.on({ type: attr.loadingType });
					};
					return attr.beforeSend.apply(this, arguments);
				},
				complete: function () {
					/*if (attr.loading === true) {
						if (attr.loadingTime) {
							setTimeout(function () {
								attr.loadingBar.remove();
							}, attr.loadingTime);
						} else {
							attr.loadingBar.remove();
						}
					};*/
					if ((attr.loading === true) && (attr.async === true)) {
						p.loadingBar.off({ type: attr.loadingType });
					};
					return attr.complete.apply(this, arguments);
				},
				success: attr.success,
				error: attr.error
			};

			if (a.type(config.data) !== "string") {
				config.data = JSON.stringify(config.data);
			};

			a.ajax(config);
		},	/*	End p.ajax	*/
		format: function (s, params) {	/*	p.format	*/
			var fm = a.validator.format(s);
			return fm(params);
		},	/*	End p.format	*/
		goLogOut: function (settings) {	/*	p.goLogOut	*/
			var config = {
				mcode: '',
				from: '',
				url: encodeURIComponent(location.href)
			}
			if (settings) a.extend(config, settings);

			var url = p.format('/Account/Logout?login_url={0}/member/coway_new/member/login.aspx?mcode={1}&from={2}&url={3}', [p.config.URL_MEMBER(), config.mcode, config.from, config.url]);
			if (this.isRealMobile() === true) {
				url = p.format('/Account/Logout?login_url={0}/member/mcoway_new/member/login.aspx?mcode={1}&from={2}&url={3}', [p.config.URL_MEMBER(), config.mcode, config.from, config.url]);
			};

			location.href = url;
		},	/*	End p.goLogOut	*/
		isLogin: function (settings) {	/*	p.isLogin	*/
			var result = false;
			var url = a.validator.format('/Account/Logout?login_url={0}/member/coway_new/member/login.aspx?mcode=GNBBOT_02&from=RT_A_01&url=');
			if (this.isRealMobile() === true) {
				url = a.validator.format('/Account/Logout?login_url={0}/member/mcoway_new/member/login.aspx?mcode=GNBBOT_02&from=RT_A_01&url=');
			};

			var config = {
				go: false,
				confirm: false,
				msg: '',
				data: {
				},
				url: url(this.config.URL_MEMBER()) + encodeURIComponent(location.href)
			}
			if (settings) a.extend(config, settings);

			b.ajax({
				url: '/Common/IsLogin',
				data: config.data,
				async: false,
				success: function (r) {
					result = r.IsLogin;
				}
			});

			if (!result) {
				if (config.go) {
					if (config.msg.length > 0) {
						if (config.confirm) {
							if (this.confirm(config.msg)) {
								if (config.url.length > 0) { location.href = config.url; }
							}
							else {
								return result;
							}
						} else {
							this.alert(config.msg);
						}
					}
					if (config.url.length > 0) { location.href = config.url; }
				}
			};
			return result;
		},	/*	End p.isLogin	*/
		isRealMobile: function () {	/*	p.isRealMobile	*/
			var result = false;
			b.ajax({
				url: '/Common/IsMobileDevice',
				async: false,
				success: function (r) {
					result = r.IsMobile;
				}
			});
			return result;
		},	/*	End p.isRealMobile	*/
		IsProxyLogin: function (settings) {	/*	p.IsProxyLogin	*/
			var config = {
				message: '대리 사용자 화면에서는 연결되지 않습니다!',
				isAlert: true
			}
			if (settings) a.extend(config, settings);

			var result = true;
			b.ajax({
				url: '/Common/IsProxyLogin',
				async: false,
				success: function (r) {
					result = r.IsProxy;
				}
			});

			if (result && config.isAlert) {
				p.alert(config.message);
			};

			return result;
		},	/*	End p.IsProxyLogin	*/
		GetUserType: function () {	/*	p.GetUserType	*/
		    var result = '';
		    b.ajax({
		        url: '/Common/GetUserType',
		        async: false,
		        success: function (r) {
		            result = r.UserType;
		        }
		    });
		    return result;
		},	/*	End p.GetUserType	*/
		stringBuilder: function (settings) {	/*	End p.stringBuilder	*/
			var config = {
				br: this.escape.LINE_FEED
			}
			if (settings) a.extend(config, settings);

			var s = [];
			var r = {
				clear: function () {
					s = [];
				},
				append: function (v) {
					if (v !== undefined) s.push(v);
				},
				appendLine: function (v) {
					if (v !== undefined) {
						s.push(v);
						s.push(config.br);
					}
				},
				toString: function () {
					return s.join('');
				}
			};
			return r;
		},	/*	End p.stringBuilder	*/
		dialog: function (settings, dialogOption) {	/*	End p.dialog	*/
			var config = {
				id: ''
			};
			if (settings) a.extend(config, settings);

			var attr = {
				modal: true,
				close: a.noop
			};
			if (dialogOption) a.extend(attr, dialogOption);

			var layer = config.id ? a('#' + config.id) : a('<div />').appendTo('body');
			var dialog = layer.dialog(attr);

			return {
				layer: layer,
				dialog: dialog
			};

			/*
				var config = {
					layer: '',
					mask: '',
					html: 'sdfsdfsdfsdf<br />가가ㅏ런ㅇㅎ',
					width: 0,
					height: 0,
					gnbHeight: 150,
					top: 0,
					left: 0,
					layerIndex: '9999',
					maskIndex: '9000',
					center: true
				};
				if (settings) a.extend(config, settings);
	
				var win = a(window);
				var w = win.width();
				var h = win.height();
	
				var layer = config.layer ? a(config.layer) : a('<div />').html(config.html).appendTo('body');
				if (config.center) {
					config.top = (h - config.height) / 2 + win.scrollTop();
					config.left = (w - config.width) / 2 + win.scrollLeft();
				};
	
				var mask = config.mask ? a(config.mask) : a('<div />').appendTo('body');
				mask.css({
					'position': 'absolute',
					'background-color': '#000',
					'opacity': '0.05',
					'-moz-opacity': '0.05',
					'width': w + 'px',
					'height': h + 'px',
					'left': '0px',
					'top': config.top + 'px',
					'display': 'block',
					'z-index': config.maskIndex
				});
	
				layer.css({
					'position': 'absolute',
					'background-color': '#fff',
					'width': config.width + 'px',
					'height': config.height + 'px',
					'left': config.left + 'px',
					'top': config.top + 'px',
					'display': 'block',
					'z-index': config.layerIndex
				});
				*/

		},	/*	End p.dialog	*/
		alert: function (message) {	/*	p.alert	*/
			return alert(message);
		},	/*	End p.alert	*/
		confirm: function (message) {	/*	p.confirm	*/
			return confirm(message);
		},	/*	End p.confirm	*/
		go: function (settings) {	/*	p.go	*/
			var config = {
				url: '',
				message: '목록으로 이동하실 경우 입력한\n내용은 저장되지 않습니다.\n목록으로 이동 하시겠습니까?'
			};
			if (settings) a.extend(config, settings);

			if (confirm(config.message)) {
				location.href = config.url;
			};
		},	/*	End p.confirm	*/
		print: function (settings) {	/*	p.print	*/
			var config = {
				id: '',
				content: ''
			};
			if (settings) a.extend(config, settings);

			var pContent = config.content;
			if (!pContent.length) {
				pContent = a('#' + config.id).html();
			};

			var strFrameName = "printThis-" + (new Date()).getTime();

			if (a('iframe[name="printIframe"]').length) {
				a('iframe[name="printIframe"]').remove();
			};

			a('<iframe />').attr({ id: strFrameName, name: 'printIframe', width: 0, height: 0 }).hide().appendTo('body');

			var iframe = $("#" + strFrameName);
			var doc = iframe.contents();

			doc.find("body").append(pContent);

			setTimeout(function () {
				iframe[0].contentWindow.focus();
				iframe[0].contentWindow.print();

				setTimeout(function () {
					iframe.remove();
				}, 1000);

			}, 333);
		},	/*	End p.print	*/
		adPlay: function (settings) {	/*	p.adPlay	*/
			var config = {
				url: ''
			};
			if (settings) a.extend(config, settings);

			if (a('iframe[name="adframe"]').length) {
				a('iframe[name="adframe"]').remove();
			};

			var iframe = a('<iframe />').attr({
				name: 'adframe',
				width: '1000px',
				height: '563px',
				src: config.url,
				frameborder: 0,
				webkitallowfullscreen: '',
				mozallowfullscreen: '',
				allowfullscreen: ''
			});

			a('html,body').css('overflow-y', 'hidden');
			a('.adpop_layer').fadeIn(100, function () {
				a('.adpop_cont_play').fadeIn(100).append(iframe);

				a(".adpop_cont_play").fadeIn(100, function () {
					var winH = a(window).height();
					var videoH = a(".adpop_cont_play").outerHeight(true);

					if (winH > videoH) {
						a(".adpop_cont").css({ "top": "50%", "margin-top": -(videoH / 2) });
					} else {
						a(this).find("iframe").css("height", winH);
						a(".adpop_cont").css({ "top": 0, "margin-top": 0 });
					}
				});
			});
			return false;
		},	/*	End p.adPlay	*/
		download: function (settings) {	/*	p.adPlay	*/
		    var config = {
		        file: '',
		        asFileNm: '',
		        no: 0,
		        type: 'full_url',
		        url: '/Common/FileDownLoad/?'
		    };
		    if (settings) a.extend(config, settings);

		    config.url += b.format('&file={0}', encodeURIComponent(escape(config.file)));
		    config.url += b.format('&asFilenm={0}', encodeURIComponent(escape(config.asFileNm)));
		    config.url += b.format('&no={0}', config.no);

		    if (!b.isNull(config.type)) {
		        config.url += b.format('&type={0}', encodeURIComponent(escape(config.type)));
		    }

		    if (a('iframe[name="downframe"]').length) {
		        a('iframe[name="downframe"]').remove();
		    };

		    a('<iframe />').attr({ src: config.url, name: 'downframe', width: 0, height: 0 }).hide().appendTo('body');

		},	/*	End p.download	*/
		tag: {	/*	p.tag	*/
			colgroup: function (settings) {	/*	p.tag.colgroup	*/
				var config = {
					col: []
				};
				if (settings) a.extend(config, settings);
				var colgroup = a('<colgroup />');
				a.each(config.col, function () {
					var col = a('<col />');
					var w = this.toString();

					if (w.length) col.attr({ 'width': w });
					colgroup.append(col);
				});

				return colgroup;
			},	/*	End p.tag.colgroup	*/
			table: function (settings) {	/*	p.tag.table	*/
				var config = {
					className: 'board',
					attr: {
						width: '100%',
						cellspacing: '0',
						cellpadding: '0'
					}
				};
				if (settings) a.extend(config, settings);

				var table = a('<table />').attr(config.attr).addClass(config.className);
				return table;
			},	/*	End p.tag.table	*/
			label: function (settings) {	/*	p.tag.label	*/
				var config = {
					name: '',
					id: '',
					forStr: '',
					'html': ''
				};
				if (settings) a.extend(config, settings);

				var label = a('<label />').attr({ 'for': config.forStr }).html(config.html);;
				if (config.name.length) label.attr({ 'name': config.name });
				if (config.id.length) label.attr({ 'id': config.id });

				return label;
			},	/*	End p.tag.label	*/
			hidden: function (settings) {	/*	p.tag.hidden	*/
				var config = {
					name: '',
					id: '',
					value: ''
				};
				if (settings) a.extend(config, settings);

				var input = a('<input />').attr({ type: 'hidden' }).val(config.value);
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });

				return input;
			},	/*	End p.tag.hidden	*/
			textbox: function (settings) {	/*	p.tag.textbox	*/
				var config = {
					name: '',
					id: '',
					value: '',
					css: {},
					className: 'basic',
					size: 0,
					maxlength: 0,
					readonly: false,
					title: ''
				};
				if (settings) a.extend(config, settings);

				var input = a('<input />').attr({ type: 'text' }).val(config.value);
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });
				if (config.className.length) input.addClass(config.className);
				input.css(config.css);
				if (config.size) input.attr({ 'size': config.size });
				if (config.maxlength) input.attr({ 'maxlength': config.maxlength });
				if (config.readonly) input.attr({ readonly: true });
				if (config.title.length) input.attr({ 'title': config.title });

				return input;
			},	/*	End p.tag.textbox	*/
			textarea: function (settings) {	/*	p.tag.textarea	*/
				var config = {
					name: '',
					id: '',
					value: '',
					css: {},
					className: 'basic',
					maxlength: 0,
					readonly: false,
					title: ''
				};
				if (settings) a.extend(config, settings);

				var input = a('<textarea />').val(config.value);
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });
				if (config.className.length) input.addClass(config.className);
				input.css(config.css);
				if (config.maxlength) input.attr({ 'maxlength': config.maxlength });
				if (config.readonly) input.attr({ readonly: true });
				if (config.title.length) input.attr({ 'title': config.title });

				return input;
			},	/*	End p.tag.textarea	*/
			checkbox: function (settings) {	/*	p.tag.checkbox	*/
				var config = {
					name: '',
					id: '',
					value: '',
					css: {},
					className: '',
					disabled: false
				};
				if (settings) a.extend(config, settings);

				var input = a('<input />').attr({ type: 'checkbox' }).val(config.value);
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });
				if (config.className.length) input.addClass(config.className);
				input.css(config.css);
				if (config.disabled) input.attr({ disabled: true });

				return input;
			},	/*	End p.tag.checkbox	*/
			radio: function (settings) {	/*	p.tag.radio	*/
				var config = {
					name: '',
					id: '',
					value: '',
					css: {},
					className: '',
					disabled: false
				};
				if (settings) a.extend(config, settings);

				var input = a('<input />').attr({ type: 'radio' }).val(config.value);
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });
				if (config.className.length) input.addClass(config.className);
				input.css(config.css);
				if (config.disabled) input.attr({ disabled: true });

				return input;
			},	/*	End p.tag.radio	*/
			file: function (settings) {	/*	p.tag.file	*/
				var config = {
					name: '',
					id: '',
					className: 'basic',
					css: {},
					title: ''
				};
				if (settings) a.extend(config, settings);

				var input = a('<input />').attr({ type: 'file' });
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });
				if (config.className.length) input.addClass(config.className);
				input.css(config.css);
				if (config.title.length) input.attr({ 'title': config.title });

				return input;
			},	/*	End p.tag.file	*/
			combo: function (settings) {	/*	p.tag.combo	*/
				var config = {
					name: '',
					id: '',
					className: 'basic',
					css: {},
					title: '',
					isDefault: false,
					defaultVal: '',
					defaultText: '전체',
					data: [],
					valKey: 'value',
					textKey: 'text',
					isOptionData: false
				};
				if (settings) a.extend(config, settings);

				var input = a('<select />');
				if (config.name.length) input.attr({ 'name': config.name });
				if (config.id.length) input.attr({ 'id': config.id });
				if (config.className.length) input.addClass(config.className);
				input.css(config.css);
				if (config.title.length) input.attr({ 'title': config.title });

				if (config.isDefault) {
					var op = a('<option />').val(config.defaultVal).text(config.defaultText);
					input.append(op);
				};

				a.each(config.data, function () {
					var op = a('<option />').val(this[config.valKey]).text(this[config.textKey]);
					input.append(op);
					if (config.isOptionData) {
						op.data(this);
					};
				});

				return input;
			}	/*	End p.tag.combo	*/
		},	/*	End p.tag	*/
		keyUrlOpen: function (name) {	/*	p.keyUrlOpen	*/
			var url = this.config.KEY_URL();
			return this.window.open(url, name, { width: 501, height: 560 }, true);
		},	/*	End p.keyUrlOpen	*/
		sns: function (settings) {	/*	p.sns	*/
			var config = {
				type: '',
				title: '',
				url: location.href
			};
			if (settings) a.extend(config, settings);

			var jTitle = encodeURIComponent(config.title); // SNS에 표시될 타이틀명
			var jUrl = encodeURIComponent(config.url); // SNS에 표시될 페이지 url주소

			var jHref = ""; // URL
			var jWinName = config.type; // 팝업명
			var jAttr = ""; // 속성

			switch (config.type) {
			    case "facebook":
			        if (get_mobile()) {
			            jHref = "http://m.facebook.com/sharer/sharer.php?u=" + jUrl + "&t=" + jTitle; 
			        } else {
			            jHref = "https://www.facebook.com/sharer/sharer.php?s=100&p[title]=" + jTitle + "&p[url]=" + jUrl;
			        }
					jAttr = "toolbar=0;status=0;width=1002;height=612";
					break;
			}

			var pop = window.open(jHref, jWinName, jAttr);
			pop.focus();
		},	/*	End p.sns	*/
		extension: function (filename) {	/*	p.extension	*/
			var r = '';
			if ((/[.]/.exec(filename))) {
				r = /[^.]+$/.exec(filename);
				r = r.toString().toLowerCase();
			};
			return r;
		},	/*	End p.extension	*/
		number: {	/*	p.number	*/
			comma: function (n) {	/*	p.number.comma	*/
				var pattern = /(-?[0-9]+)([0-9]{3})/;
				while (pattern.test(n)) {
					n = n.toString().replace(pattern, '$1,$2');
				}
				return n;
			},	/*	End p.number.comma	*/
			unComma: function (n) {	/*	p.number.unComma	*/
				if (n === undefined) return '';
				return n.toString().replace(/\,/g, '');
			}	/*	End p.number.unComma	*/
		},	/*	End p.number	*/
		checkimage: function (filename) {	/*	p.checkimage	*/
			var r = false;
			var ext = this.extension(filename);
			switch (ext) {
				case 'jpg':
				case 'jpeg':
				case 'gif':
				case 'png':
				case 'bmp':
					r = true;
					break;
			};
			return r;
		},	/*	End p.checkimage	*/
		checkzip: function (filename) {	/*	p.checkzip	*/
			var r = false;
			var ext = this.extension(filename);
			switch (ext) {
				case 'zip':
					r = true;
					break;
			};
			return r;
		},	/*	End p.checkzip	*/
		window: {	/*	p.window	*/
			open: function (url, name, featrues, center) {	/*	p.window.open	*/
				var config = {
					width: 100,
					height: 100,
					top: 0,
					left: 0,
					directories: 'no',
					menubar: 'no',
					resizable: 'no',
					scrollbars: 'auto',
					status: 'no',
					toolbar: 'no'
				}
				if (featrues) a.extend(config, featrues);
				if (center) {
					config.top = (screen.height / 2) - (config.height / 2); //($(window).height() - config.height) / 2;
					config.left = (screen.width / 2) - (config.width / 2); //($(window).width() - config.width) / 2;
				}

				var param = [];
				a.each(config, function (k, v) {
					var fm = a.validator.format('{0}={1}');
					param.push(fm(k, v));
				});

				var newwin = window.open(url, name, param.join(','));
				if (window.focus) {
					newwin.focus();
				} else {
					p.alert("새창 띄우기를 허용해 주십시요");
				}
				return newwin;
			}	/*	End p.window.open	*/
		},	/*	End p.window	*/
		resize: function (fnp, fnm) {	/*	p.resize	*/
		    var resizeThis = this;
		    var rpc = resizeThis.config.pc(), rmb = !rpc;

			a(window).resize(function () {
				var isPc = resizeThis.config.pc();/*a(window).width() > 480;*/

				if (isPc && rpc === false) {
				    rpc = true; rmb = false;

					if (a.isFunction(fnp)) fnp();
				} else if (!isPc && rmb === false) {
				    rmb = true; rpc = false;

					if (a.isFunction(fnm)) fnm();
				};
			});
		},	/*	End p.resize	*/
		openPccV2Window: function (settings) {	/*	p.openPccV2Window	*/
			var config = {
				iframe: null,
				iframeSrc: '/MyCoway/Person/ProductMng/HeartService/HeartUpdateAuthSeed',
				callBack: a.noop,
				callBackName: 'GetAuthResult',
				data: {}
			};
			if (settings) a.extend(config, settings);

			if (config.iframe) {
				window[config.callBackName] = config.callBack;

				if (this.config.IS_LOCAL_URL) {
					/*config.iframe.attr({ src: config.iframeSrc });*/
					a('<form />').attr({ method: 'post', action: config.iframeSrc, target: config.iframe.attr('name') }).appendTo('body').cwSubimt({
						data: config.data
					});
				} else {
					var retUrl = b.config.SSL_URL() + config.iframeSrc;	/*CUR_URL*/
					var actionUrl = 'https://tvoc.coway.co.kr/member/auth_seed.asp';
					var srvNo = '003008';
					if (this.config.IS_REAL_URL || this.config.IS_REAL_N_URL) {
						actionUrl = 'https://help.coway.co.kr/member/auth_seed.asp';
						srvNo = '004010';
					};

					/*	통합인증 본인인증 페이지로 아이프레임 이동	*/
					/*a('<form />').attr({ method: 'post', action: actionUrl, target: config.iframe.attr('name') })
						.append(this.tag.hidden({ name: 'srvNo', value: srvNo }))
						.append(this.tag.hidden({ name: 'retUrl', value: retUrl }))
					.appendTo('body').cwSubimt();*/
					a('<form />').attr({ method: 'post', action: actionUrl, target: config.iframe.attr('name') }).appendTo('body').cwSubimt({
						data: {
							srvNo: srvNo,
							retUrl: retUrl
						}
					});
				};
			};
		},	/*	End p.openPccV2Window	*/
		loadingBar: {	/*	p.loadingBar	*/
			on: function (settings) {	/*	p.loadingBar.on	*/
				var config = {
					type: ''
				};
				if (settings) a.extend(config, settings);

				if (!config.type.length) config.type = 'root';

				loading_on(config.type);
			},	/*	End p.loadingBar.on	*/
			off: function (settings) {	/*	p.loadingBar.off	*/
				var config = {
					type: ''
				};
				if (settings) a.extend(config, settings);

				if (!config.type.length) config.type = 'root';

				loading_off(config.type);
			},	/*	End p.loadingBar.on	*/
			error: function (settings) {	/*	p.loadingBar.error	*/
				var config = {
					type: ''
				};
				if (settings) a.extend(config, settings);

				if (!config.type.length) config.type = 'root';

				loading_error(config.type);
			}	/*	End p.loadingBar.error	*/
		},	/*	End p.loadingBar	*/
		script: {	/*	p.script	*/
			kr: {	/*	p.script.kr	*/
			},	/*	End p.script.kr	*/
			mall: {	/*	p.script.mall	*/
			},	/*	End p.script.mall	*/
			admin: {	/*	p.script.admin	*/
			}	/*	End p.script.admin	*/
		}	/*	End p.script	*/
	}; /*	End p	*/

	/*	prototype	*/
	var base = a.noop;
	base.prototype = p;
	b = new base();

	/*
	var child = function () {
		base.call(this);
	};
	child.prototype = Object.create(base.prototype);
	child.prototype.constructor = child;
	var c = new child();
	*/
	/*	End prototype	*/

	/*	Start ::: jQuery PlugIn	*/

	/*	jQuery.fn.cwPager	*/
	a.fn.cwPager = function (attribute) {
		var attr = {
			scrollTop: -1,
			totalCount: 0,
			pageSize: 10,
			blockSize: 10,
			pageNo: 1,
			pageClick: a.noop,
			curNumCls: 'on',
			hoverNumCls: 'on',
			$NumBlock: a('<span />').addClass('num'),
			$First: a('<a />').css({ 'cursor': 'pointer' }).text('처음'),
			$Prev: a('<a />').css({ 'cursor': 'pointer' }).addClass('btn prev').attr('title', '이전').text('이전으로'),
			$Number: a('<a />').css({ 'cursor': 'pointer' }),
			$Next: a('<a />').css({ 'cursor': 'pointer' }).addClass('btn next').attr('title', '다음').text('다음으로'),
			$End: a('<a />').css({ 'cursor': 'pointer' }).text('마지막')
		};
		if (attribute) a.extend(attr, attribute);

		var pageCount = Math.floor((attr.totalCount - 1) / attr.pageSize + 1);
		pageCount = (pageCount == 0 ? 1 : pageCount);
		var blockCount = Math.floor((pageCount - 1) / attr.blockSize + 1);
		var curBlock = Math.floor((attr.pageNo - 1) / attr.blockSize + 1);
		var pageStart;
		var pageEnd;
		var self = this;

		a(self).empty();
		function init() {
			if (curBlock < 1) {
				curBlock = 1;
			};

			if (curBlock > blockCount) {
				curBlock = blockCount;
			};

			var pageStart = Math.floor((curBlock - 1) * attr.blockSize + 1);
			var pageEnd = curBlock * attr.blockSize;

			if (blockCount <= curBlock) {
				pageEnd = pageCount;
			};

			if (curBlock > 1) //첫 블럭이 아닌경우
			{
				//attr.$First.on('click', { page: 1 }, attr.pageClick).appendTo(a(self));
				attr.$Prev.on('click', { page: (curBlock - 1) * attr.blockSize }, function (e) {
					e.preventDefault();
					if (attr.scrollTop > -1) {
						a(window).scrollTop(attr.scrollTop);
					};
					attr.pageClick.apply(this, arguments);
				}).appendTo(a(self));
			} else {
				//attr.$First.css('cursor', 'default').appendTo(a(self));
				//attr.$Prev.css('cursor', 'default').appendTo(a(self));
			};

			for (var i = pageStart; i <= pageEnd; i++) {
				var $number = attr.$Number.clone()
				if (i == attr.pageNo) {
					$number.attr('title', i + '페이지').html(i).on('click', { page: i }, function (e) {
						e.preventDefault();
						if (attr.scrollTop > -1) {
							a(window).scrollTop(attr.scrollTop);
						};
						attr.pageClick.apply(this, arguments);
					}).addClass('on').appendTo(attr.$NumBlock);

				} else {
					$number.attr('title', i + '페이지').html(i).on('click', { page: i }, function (e) {
						e.preventDefault();
						if (attr.scrollTop > -1) {
							a(window).scrollTop(attr.scrollTop);
						};
						attr.pageClick.apply(this, arguments);
					})
						.mouseenter(function (evt) {
							a(this).toggleClass(attr.hoverNumCls);
						})
						.mouseleave(function (evt) {
							a(this).toggleClass(attr.hoverNumCls);
						})
						.appendTo(attr.$NumBlock)
				}
			};

			attr.$NumBlock.appendTo(a(self))

			if ((curBlock) * attr.blockSize < pageCount) //마지막페이지가 아닌경우
			{
				attr.$Next.on('click', { page: (curBlock) * attr.blockSize + 1 }, function (e) {
					e.preventDefault();
					if (attr.scrollTop > -1) {
						a(window).scrollTop(attr.scrollTop);
					};
					attr.pageClick.apply(this, arguments);
				}).appendTo(a(self));
				//attr.$End.on('click', { page: pageCount }, attr.pageClick).appendTo(a(self));
			} else {
				//attr.$Next.css('cursor', 'default').appendTo(a(self));
				//attr.$End.css('cursor', 'default').appendTo(a(self));
			};
		};
		if(attr.totalCount > 0 ) init();
	};	/*	End jQuery.fn.cwPager	*/

	/*	jQuery.fn.cwAjaxForm	*/
	a.fn.cwAjaxForm = function (settings) {
		var config = {
			dataType: "json",
			data: {},
			target: null,
			beforeSubmit: a.noop,
			success: a.noop,
			error: a.noop
		};
		if (settings) a.extend(config, settings);

		a(this).ajaxForm(config);
	};
	/*	End jQuery.fn.cwAjaxForm	*/

	/*	jQuery.fn.cwSubimt	*/
	a.fn.cwSubimt = function (settings) {
		var win = a(window);
		var config = {
			iframe: false,
			iframePrint: false,
			settings: a.noop,
			loading: false,
			/*loadingBar: a('<div />').append(a('<img />').attr({ src: "/images/roading.gif" })).css({
				'position': 'absolute',
				'left': (win.width() - 160) / 2 + win.scrollLeft(),
				'top': (win.height() - 24) / 2 + win.scrollTop(),
				'z-index': 1000
			}),*/
			data: {},
			beforeSubmit: function () {
				return true;
			}
		};
		if (settings) a.extend(config, settings);
		var t = a(this);
		var preAction = t.attr('action');
		var preTarget = t.attr('target');
		var iframe = null;

		if (config.iframe === true) {
			if (a('iframe[name="cw_iframe_sumit"]').length) {
				a('iframe[name="cw_iframe_sumit"]').remove();
			};

			iframe = a('<iframe />').attr({ name: 'cw_iframe_sumit' }).hide().appendTo('body');
			t.attr({ target: 'cw_iframe_sumit' });
		}

		if (config.beforeSubmit.call(this)) {
			a.each(config.data, function (k, v) {
				if (a.isArray(v)) {
					a.each(v, function () {
						t.append(a('<input />').attr({ 'type': 'hidden', 'name': k }).val(this.toString()));
					});
				} else {
					var fm = a.validator.format('input[type="hidden"][name="{0}"]');
					var hdn = t.find(fm(k));
					if (hdn.length > 0) {
						hdn.val(v);
					} else {
						hdn = a('<input />').attr({ 'type': 'hidden', 'name': k });
						t.append(hdn.val(v));
					}
				}
			});

			/*if (config.loading === true) a('body').append(config.loadingBar);*/
			config.settings.apply(this, [/*{ loadingBar: config.loadingBar }*/]);

			t.submit();

			if (config.iframe === true) {
				if (config.iframePrint === true) {
					iframe.css({width: '100%', height: '0px'}).show();
					iframe.load(function () {
						/*	var c = a(this).contents();	*/
						iframe[0].contentWindow.focus();
						iframe[0].contentWindow.print();
						
						setTimeout(function () {
							iframe.remove();
						}, 1000);
					});
				};
			};

		};

		return false;
	};
	/*	End jQuery.fn.cwSubimt	*/

	/*	jQuery.fn.cwNumber	*/
	a.fn.cwNumber = function (settings) {
		var config = {
			isRealMobile: b.isRealMobile(),
			onlyNum: false,
			isAlert: false,
			isPercentFloat: false,
			regular: b.regular.percentFloat,
			max: 0,
			onKeyUp: a.noop,
			maxlength: 0
		};
		if (settings) a.extend(config, settings);
		var t = a(this);

		if (config.maxlength) t.attr({ 'maxlength': config.maxlength });
		if (config.isRealMobile === true) {
			t.off('keyup').on('keyup', function () {
				config.onKeyUp.apply(this, arguments);
			});	/*	End t.keydown(function (evt) {	*/
			return t;
		};

		t.keydown(function (evt) {
			/* Allow: backspace, delete, tab, escape, and enter	*/
			if (evt.keyCode == 144 || evt.keyCode == 46 || evt.keyCode == 8 || evt.keyCode == 9 || evt.keyCode == 27 || evt.keyCode == 13 ||
				/* Allow: Ctrl+A	*/
				(evt.keyCode == 65 && evt.ctrlKey === true) ||
				/* Allow: home, end, left, right	*/
				(evt.keyCode >= 35 && evt.keyCode <= 39)) {
				/* let it happen, don't do anything	*/
				return;
			} else {
				/* Ensure that it is a number and stop the keypress	*/
				var c = evt.shiftKey || (evt.keyCode < 48 || evt.keyCode > 57) && (evt.keyCode < 96 || evt.keyCode > 105);
				if (c && config.isPercentFloat && (evt.keyCode === 190 || evt.keyCode === 110)) c = false;

				if (c) {
					evt.preventDefault();
					if (config.isAlert) {
						if (config.isPercentFloat) {
							b.alert('정수부 2자리 소주부 3자리까지 입력 가능합니다.');
						} else {
							b.alert('숫자만 입력 가능합니다.');
						}
					}
				} else {

					t.off('keyup').on('keyup', function () {
						if (!config.onlyNum) t.val(b.number.comma(b.number.unComma(t.val())));

						var s = b.number.unComma(t.val());
						var c = false;
						if (config.max) {
							if (parseFloat(s) > parseFloat(config.max)) {
								t.val('');
								b.alert(b.format('최대 {0}까지 입력할 수 있습니다.', [config.max]));
							}
						};

						if (s && config.isPercentFloat) {
							if (!s.match(config.regular)) {
								if (config.isAlert) {
								} else {
									t.val('');
								}
							}
						};

						if (!c) config.onKeyUp.apply(this, arguments);

					});	/* End t.off('keyup').on('keyup', function () {	*/

				}	/*	End if (c) {	*/
			}
		});	/*	End t.keydown(function (evt) {	*/

		return t;
	};
	/*	jQuery.fn.cwNumber	*/


	////keyboard maping array   
	//var keyboardMap = ["", "", "", "CANCEL", "", "", "HELP", "", "BACK_SPACE", "TAB", "", "", "CLEAR", "ENTER", "RETURN", "", "SHIFT", "CONTROL", "ALT", "PAUSE", "CAPS_LOCK", "KANA", "EISU", "JUNJA", "FINAL", "HANJA", "", "ESCAPE", "CONVERT", "NONCONVERT", "ACCEPT", "MODECHANGE", "SPACE", "PAGE_UP", "PAGE_DOWN", "END", "HOME", "LEFT", "UP", "RIGHT", "DOWN", "SELECT", "PRINT", "EXECUTE", "PRINTSCREEN", "INSERT", "DELETE", "", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "COLON", "SEMICOLON", "LESS_THAN", "EQUALS", "GREATER_THAN", "QUESTION_MARK", "AT", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "WIN", "", "CONTEXT_MENU", "", "SLEEP", "NUMPAD0", "NUMPAD1", "NUMPAD2", "NUMPAD3", "NUMPAD4", "NUMPAD5", "NUMPAD6", "NUMPAD7", "NUMPAD8", "NUMPAD9", "MULTIPLY", "ADD", "SEPARATOR", "SUBTRACT", "DECIMAL", "DIVIDE", "F1", "F2", "F3", "F4", "F5", "F6", "F7", "F8", "F9", "F10", "F11", "F12", "F13", "F14", "F15", "F16", "F17", "F18", "F19", "F20", "F21", "F22", "F23", "F24", "", "", "", "", "", "", "", "", "NUM_LOCK", "SCROLL_LOCK", "WIN_OEM_FJ_JISHO", "WIN_OEM_FJ_MASSHOU", "WIN_OEM_FJ_TOUROKU", "WIN_OEM_FJ_LOYA", "WIN_OEM_FJ_ROYA", "", "", "", "", "", "", "", "", "", "CIRCUMFLEX", "EXCLAMATION", "DOUBLE_QUOTE", "HASH", "DOLLAR", "PERCENT", "AMPERSAND", "UNDERSCORE", "OPEN_PAREN", "CLOSE_PAREN", "ASTERISK", "PLUS", "PIPE", "HYPHEN_MINUS", "OPEN_CURLY_BRACKET", "CLOSE_CURLY_BRACKET", "TILDE", "", "", "", "", "VOLUME_MUTE", "VOLUME_DOWN", "VOLUME_UP", "", "", "", "", "COMMA", "", "PERIOD", "SLASH", "BACK_QUOTE", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "OPEN_BRACKET", "BACK_SLASH", "CLOSE_BRACKET", "QUOTE", "", "META", "ALTGR", "", "WIN_ICO_HELP", "WIN_ICO_00", "", "WIN_ICO_CLEAR", "", "", "WIN_OEM_RESET", "WIN_OEM_JUMP", "WIN_OEM_PA1", "WIN_OEM_PA2", "WIN_OEM_PA3", "WIN_OEM_WSCTRL", "WIN_OEM_CUSEL", "WIN_OEM_ATTN", "WIN_OEM_FINISH", "WIN_OEM_COPY", "WIN_OEM_AUTO", "WIN_OEM_ENLW", "WIN_OEM_BACKTAB", "ATTN", "CRSEL", "EXSEL", "EREOF", "PLAY", "ZOOM", "", "PA1", "WIN_OEM_CLEAR", ""];
	////Allowed keys as per your requirement          
	//var allowedKey = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "NUMPAD0", "NUMPAD1", "NUMPAD2", "NUMPAD3", "NUMPAD4", "NUMPAD5", "NUMPAD6", "NUMPAD7", "NUMPAD8", "NUMPAD9", "DELETE", "BACK_SPACE", "DECIMAL", "LEFT", "RIGHT", "TAB", "SUBTRACT", "PERIOD"];

	////bind keydown to your element
	//element.on("keydown", function (e) {
	//	var key = e.charCode || e.keyCode || 0;
	//	var keyValue = keyboardMap[key];
	//	if ($.inArray(keyValue, allowedKey) !== -1){
	//		return true;
	//	}
	//	else{
	//		return  false;
	//	}
	//}


	/*	jQuery.fn.serializeObject	*/
	a.fn.serializeObject = function () {
		var o = {};
		var arr = this.serializeArray();
		a.each(arr, function () {
			if (o[this.name]) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
	};
	/*	End jQuery.fn.serializeObject	*/

	return b;
})(jQuery, window.jCoway || {});