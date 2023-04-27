/**
 * @description this code adds a click_outside event to a node
 * it's used in the menu component to close the menu when it's open and a click happens outside the menu

* @param {node} node
 * @returns {object} destroy
 */
export function clickOutside(node) {
	const handleClick = (event) => {
		if (node && !node.contains(event.target) && !event.defaultPrevented) {
			// console.log('click outside', event.target);
			node.dispatchEvent(new CustomEvent('click_outside', node));
		}
	};
	document.addEventListener('click', handleClick, true);
	return {
		destroy() {
			document.removeEventListener('click', handleClick, true);
		}
	};
}

/**
 * @description get current date and time
 *
 * @returns {string} yyyy-mm-dd hh:mm:ss
 */
export function getDatetimeNow() {
	const date = new Date();
	const year = date.getFullYear();
	const month = date.getMonth() + 1;
	const day = date.getDate();
	const hours = ('0' + date.getHours()).substr(-2);
	const minutes = ('0' + date.getMinutes()).substr(-2);
	const seconds = ('0' + date.getSeconds()).substr(-2);
	// console.log('getDate', `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`);
	return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

// export function formatDatetime_old(datetime) {
// 	const date = new Date(datetime);
// 	const year = date.getFullYear();
// 	const month = date.getMonth() + 1;
// 	const day = date.getDate();
// 	const hours = date.getHours();
// 	const minutes = date.getMinutes();
// 	const seconds = date.getSeconds();
// 	// console.log('formatDate', `${day}.${month}.${year} ${hours}:${minutes}`);
// 	return `${day}.${month}.${year} ${hours}:${minutes}`;
// }

/**
 * @description format datetime to dd.mm.yyyy hh:mm
 *
 * @param {string} datetime
 * @returns {string} dd.mm.yyyy hh:mm
 */
export function formatDatetime(datetime) {
	const d = new Date(datetime);
	const year = d.getFullYear();
	const month = ('0' + (d.getMonth() + 1)).slice(-2);
	const day = ('0' + d.getDate()).slice(-2);
	const hour = ('0' + d.getHours()).slice(-2);
	const minute = ('0' + d.getMinutes()).slice(-2);
	return `${day}.${month}.${year} ${hour}:${minute}`;
}

/**
 * @description colorfull console.log
 * This code sets up a global variable called "deb" that contains logging functions.
 * When in debug mode, the functions will log to the console, and when in production mode, the functions will do nothing.
 */
export function setDebug(isDebug) {
	if (isDebug) {
		window.deb = {
			r: window.console.log.bind(window.console, '%c%s', 'color: #e06c75; font-weight: bold;'),
			b: window.console.log.bind(window.console, '%c%s', 'color: #61afef; font-weight: bold;'),
			y: window.console.log.bind(window.console, '%c%s', 'color: #d19a66; font-weight: bold;'),
			g: window.console.log.bind(window.console, '%c%s', 'color: #98c379; font-weight: bold;'),
			w: window.console.log.bind(window.console, '%c%s', 'color: #abb2bf; font-weight: bold;'),
			p: window.console.log.bind(window.console, '%c%s', 'color: #c678dd; font-weight: bold;'),
			log: window.console.log.bind(window.console, 'log: %s'),
			error: window.console.error.bind(window.console, 'error: %s'),
			info: window.console.info.bind(window.console, 'info: %s'),
			warn: window.console.warn.bind(window.console, 'warn: %s')
		};
	} else {
		var __no_op = function () {};

		window.deb = {
			r: __no_op,
			b: __no_op,
			y: __no_op,
			g: __no_op,
			w: __no_op,
			p: __no_op,
			error: __no_op,
			warn: __no_op,
			info: __no_op
		};
	}
}
// setDebug(1);let test = 'test';deb.r('wat', test);deb.b('wat', test);deb.y('wat', test);deb.g('wat', test);deb.w('wat', test);deb.p('wat', test);

/**
 * @description Convert markdown to html
 * https://gist.github.com/plugnburn/f0d12e38b6416a77c098
 *
 * @param {string} text
 * @returns {string}
 */
export function md(text) {
	var esc = function (s) {
			s = s.replace(/\&/g, '&amp;');
			var escChars = '\'#<>`*-~_=:"![]()nt',
				c,
				l = escChars.length,
				i;
			for (i = 0; i < l; i++)
				s = s.replace(RegExp('\\' + escChars[i], 'g'), function (m) {
					return '&#' + m.charCodeAt(0) + ';';
				});
			return s;
		},
		rules = [
			{ p: /\r\n/g, r: '\n' },
			{ p: /######\s(?!#)(.*)/g, r: '\n<h6>$1</h6>\n' },
			{ p: /#####\s(?!#)(.*)/g, r: '\n<h5>$1</h5>\n' },
			{ p: /####\s(?!#)(.*)/g, r: '\n<h4>$1</h4>\n' },
			{ p: /###\s(?!#)(.*)/g, r: '\n<h3>$1</h3>\n' },
			{ p: /##\s(?!#)(.*)/g, r: '\n<h2>$1</h2>\n' },
			{ p: /#\s(?!#)(.*)/g, r: '\n<h1>$1</h1>\n' },
			{ p: /___(.*?)___/g, r: '<u>$1</u>' },
			{ p: /(\*\*|__)(.*?)\1/g, r: '<strong>$2</strong>' },
			{ p: /(\*|_)(.*?)\1/g, r: '<em>$2</em>' },
			{ p: /~~(.*?)~~/g, r: '<del>$1</del>' },
			{ p: /:"(.*?)":/g, r: '<q>$1</q>' },
			{ p: /\!\[([^\[]+?)\]\s*\(([^\)]+?)\)/g, r: '<img src="$2" alt="$1">' },
			{ p: /\[([^\[]+?)\]\s*\(([^\)]+?)\)/g, r: '<a href="$2">$1</a>' },
			{ p: /\n\s*(\*|\-)\s*([^\n]*)/g, r: '\n<ul><li>$2</li></ul>' },
			{ p: /\n\s*\d+\.\s*([^\n]*)/g, r: '\n<ol><li>$1</li></ol>' },
			{ p: /\n\s*(\>|&gt;)\s*([^\n]*)/g, r: '\n<blockquote>$2</blockquote>' },
			{ p: /<\/(ul|ol|blockquote)>\s*<\1>/g, r: ' ' },
			{ p: /\n\s*\*{5,}\s*\n/g, r: '\n<hr>' },
			{ p: /\n{3,}/g, r: '\n\n' },
			{ p: />\s+</g, r: '><' }
		];
	if ((text = text || '')) {
		text = '\n' + text.trim() + '\n';
		for (let i = 0; i < rules.length; i++) text = text.replace(rules[i].p, rules[i].r);
	}
	return text;
}
