/* KRAAK Associates — interactions (sans dépendance). */
(function () {
	'use strict';

	var $ = function (s, c) { return (c || document).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---------- Menu mobile ---------- */
	var overlay = $('#menu-overlay');
	var toggle = $('.menu-toggle');
	function openMenu() {
		overlay.hidden = false;
		requestAnimationFrame(function () { overlay.classList.add('is-open'); });
		document.body.classList.add('menu-open');
		toggle.setAttribute('aria-expanded', 'true');
		var close = $('.menu-close', overlay); if (close) close.focus();
	}
	function closeMenu() {
		overlay.classList.remove('is-open');
		document.body.classList.remove('menu-open');
		toggle.setAttribute('aria-expanded', 'false');
		setTimeout(function () { overlay.hidden = true; }, 300);
		toggle.focus();
	}
	if (overlay && toggle) {
		toggle.addEventListener('click', openMenu);
		$('.menu-close', overlay).addEventListener('click', closeMenu);
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !overlay.hidden) closeMenu(); });
	}

	/* ---------- En-tête : fond au défilement (accueil) ---------- */
	var header = $('#site-header');
	if (header && document.body.classList.contains('header-overlay')) {
		var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 40); };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/* ---------- Diaporama de l'accueil ---------- */
	var hero = $('[data-hero]');
	if (hero) {
		var slides = $$('.hero__slide', hero);
		var dots = $$('[data-goto]', hero);
		var count = $('[data-hero-count]', hero);
		var delay = 6500, current = 0, timer = null;
		hero.style.setProperty('--hero-delay', delay + 'ms');

		var show = function (i) {
			if (!slides.length) return;
			current = (i + slides.length) % slides.length;
			slides.forEach(function (s, k) {
				var on = k === current;
				s.classList.toggle('is-active', on);
				s.setAttribute('aria-hidden', on ? 'false' : 'true');
				var a = $('a', s); if (a) a.tabIndex = on ? 0 : -1;
			});
			dots.forEach(function (d, k) {
				d.classList.remove('is-active');
				if (k === current) { void d.offsetWidth; d.classList.add('is-active'); }
			});
			if (count) count.textContent = 'P–' + ('0' + (current + 1)).slice(-2) + ' / ' + ('0' + slides.length).slice(-2);
		};
		var play = function () {
			clearInterval(timer);
			if (reduced || slides.length < 2) return;
			timer = setInterval(function () { show(current + 1); }, delay);
		};
		dots.forEach(function (d) {
			d.addEventListener('click', function () { show(+d.getAttribute('data-goto')); play(); });
		});
		hero.addEventListener('mouseenter', function () { hero.classList.add('is-paused'); clearInterval(timer); });
		hero.addEventListener('mouseleave', function () { hero.classList.remove('is-paused'); show(current); play(); });

		// Balayage tactile.
		var x0 = null;
		hero.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
		hero.addEventListener('touchend', function (e) {
			if (x0 === null) return;
			var dx = e.changedTouches[0].clientX - x0;
			if (Math.abs(dx) > 50) { show(current + (dx < 0 ? 1 : -1)); play(); }
			x0 = null;
		});
		document.addEventListener('visibilitychange', function () { if (document.hidden) clearInterval(timer); else play(); });
		play();
	}

	/* ---------- Projets : grille / liste ---------- */
	var grid = $('[data-grid]');
	var viewBtns = $$('[data-view]');
	if (grid && viewBtns.length) {
		var setView = function (v) {
			grid.classList.toggle('is-list', v === 'list');
			viewBtns.forEach(function (b) {
				var on = b.getAttribute('data-view') === v;
				b.classList.toggle('is-active', on);
				b.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			try { localStorage.setItem('kraak-view', v); } catch (e) {}
		};
		viewBtns.forEach(function (b) { b.addEventListener('click', function () { setView(b.getAttribute('data-view')); }); });
		try { if (localStorage.getItem('kraak-view') === 'list') setView('list'); } catch (e) {}
	}

	/* ---------- Projets : charger plus ---------- */
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('[data-load-more]');
		if (!btn || !grid || !window.fetch) return;
		e.preventDefault();
		var label = btn.textContent;
		btn.textContent = (window.KRAAK && KRAAK.i18n.loading) || '…';
		btn.setAttribute('aria-busy', 'true');
		fetch(btn.href, { credentials: 'same-origin' })
			.then(function (r) { return r.text(); })
			.then(function (html) {
				var doc = new DOMParser().parseFromString(html, 'text/html');
				$$('[data-grid] > .card', doc).forEach(function (card) { grid.appendChild(document.importNode(card, true)); });
				var next = $('[data-load-more]', doc);
				if (next) { btn.href = next.href; btn.textContent = label; btn.removeAttribute('aria-busy'); }
				else { btn.parentNode.remove(); }
			})
			.catch(function () { window.location = btn.href; });
	});

	/* ---------- Fiche projet : plans, coupes, schémas ---------- */
	var drawings = $('[data-drawings]');
	if (drawings) {
		var frames = $$('.drawings__frame', drawings);
		var tabs = $$('[data-tab]', drawings);
		var thumbs = $$('[data-show]', drawings);
		var showFrame = function (idx) {
			var frame = frames[idx]; if (!frame) return;
			frames.forEach(function (f) { f.classList.toggle('is-active', f === frame); });
			var cat = frame.getAttribute('data-cat');
			tabs.forEach(function (t) {
				var on = t.getAttribute('data-tab') === cat;
				t.classList.toggle('is-active', on);
				t.setAttribute('aria-selected', on ? 'true' : 'false');
			});
			thumbs.forEach(function (t) { t.classList.toggle('is-hidden', +t.getAttribute('data-show') === idx); });
		};
		tabs.forEach(function (t) {
			t.addEventListener('click', function () {
				var first = frames.filter(function (f) { return f.getAttribute('data-cat') === t.getAttribute('data-tab'); })[0];
				if (first) showFrame(+first.getAttribute('data-idx'));
			});
		});
		thumbs.forEach(function (t) { t.addEventListener('click', function () { showFrame(+t.getAttribute('data-show')); }); });

		var box = $('[data-lightbox]');
		if (box && box.showModal) {
			var img = $('img', box);
			$$('[data-zoom]', drawings).forEach(function (z) {
				z.addEventListener('click', function () {
					var src = $('img', z);
					img.src = z.getAttribute('data-zoom');
					img.alt = src ? src.alt : '';
					box.showModal();
				});
			});
			$('[data-close]', box).addEventListener('click', function () { box.close(); });
			box.addEventListener('click', function (e) { if (e.target === box) box.close(); });
		}
	}
})();
