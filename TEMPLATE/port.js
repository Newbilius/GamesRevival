$(document).on('click', '[data-toggle="lightbox"]', function(event) {
			event.preventDefault();
			$(this).ekkoLightbox();
		});

var newURL = window.location.protocol + "//" + window.location.host + window.location.pathname;

var disqus_config = function () {
this.page.url = newURL;
this.page.identifier = newURL;
};

(function() {
var d = document, s = d.createElement('script');
s.src = 'https://gamesrevival-ru.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();