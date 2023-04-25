/** Dispatch event on click outside of node */
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

export function formatDatetime(datetime) {
	const date = new Date(datetime);
	const year = date.getFullYear();
	const month = date.getMonth() + 1;
	const day = date.getDate();
	const hours = date.getHours();
	const minutes = date.getMinutes();
	const seconds = date.getSeconds();
	// console.log('formatDate', `${day}.${month}.${year} ${hours}:${minutes}`);
	return `${day}.${month}.${year} ${hours}:${minutes}`;
}

// https://gist.github.com/plugnburn/f0d12e38b6416a77c098
export function md(text) {
	// return 'text';
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
			// {
			// 	p: /\n\s*```\n([^]*?)\n\s*```\s*\n/g,
			// 	r: function (m, grp) {
			// 		return '<pre>' + esc(grp) + '</pre>';
			// 	}
			// },
			// {
			// 	p: /`(.*?)`/g,
			// 	r: function (m, grp) {
			// 		return '<code>' + esc(grp) + '</code>';
			// 	}
			// },
			// {
			// 	p: /\n\s*(#+)(.*?)/g,
			// 	r: function (m, hset, hval) {
			// 		m = hset.length;
			// 		console.log('hval', hval);
			// 		return '<h' + m + '>' + hval.trim() + '</h' + m + '>';
			// 	}
			// },
			// {
			// 	p: /^([A-Za-z \t]*)```([A-Za-z]*)?\n([\s\S]*?)```([A-Za-z \t]*)*$/gm,
			// 	r: '\n<pre>$1</pre>\n'
			// },
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
			// {
			// 	p: /\n([^\n]+)\n/g,
			// 	r: function (m, grp) {
			// 		grp = grp.trim();
			// 		return /^\<\/?(ul|ol|bl|h\d|p).*/.test(grp.slice(0, 9)) ? grp : '<p>' + grp + '</p>';
			// 	}
			// },
			{ p: />\s+</g, r: '><' }
		];

	// let md = `
	// # Header 12
	// ## Header 12
	// 	- test
	// 	- test
	// 	___u___
	// 	`;
	// text = md;
	// let j = 4;
	// md = md.replace(rules[j].p, rules[j].r);
	// console.log('md', md);

	if ((text = text || '')) {
		text = '\n' + text.trim() + '\n';
		for (var i = 0; i < rules.length; i++) text = text.replace(rules[i].p, rules[i].r);
	}
	return text;
}
