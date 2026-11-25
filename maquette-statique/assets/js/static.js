/* KRAAK — maquette statique : filtres et formulaire sans serveur. */
(function () {
	'use strict';

	/* Filtres des pages Projets et Laboratoire. */
	var chips = Array.prototype.slice.call(document.querySelectorAll('[data-filter]'));
	if (chips.length) {
		var items = Array.prototype.slice.call(document.querySelectorAll('.project-grid .card, .research-list li'));
		var empty = document.createElement('p');
		empty.className = 'empty';
		empty.textContent = 'Aucun élément dans cette catégorie pour le moment.';
		empty.hidden = true;
		var list = document.querySelector('.project-grid, .research-list');
		if (list) list.parentNode.insertBefore(empty, list.nextSibling);

		var apply = function (slug) {
			var shown = 0;
			items.forEach(function (it) {
				var ok = !slug || it.getAttribute('data-type') === slug;
				it.hidden = !ok;
				if (ok) shown++;
			});
			empty.hidden = shown > 0;
			chips.forEach(function (c) { c.classList.toggle('is-active', c.getAttribute('data-filter') === slug); });
		};
		chips.forEach(function (c) {
			c.addEventListener('click', function (e) {
				e.preventDefault();
				var slug = c.getAttribute('data-filter');
				apply(slug);
				history.replaceState(null, '', slug ? '#' + slug : location.pathname);
			});
		});
		var start = location.hash.replace('#', '');
		if (start && chips.some(function (c) { return c.getAttribute('data-filter') === start; })) apply(start);
	}

	/* Formulaire de contact : ouvre la messagerie avec le message pré-rempli. */
	var form = document.querySelector('[data-static-form]');
	if (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			if (!form.reportValidity()) return;
			var v = function (n) { var el = form.elements[n]; return el ? el.value : ''; };
			var mail = (document.querySelector('.contact-mail') || {}).textContent || '';
			var body = 'Nom : ' + v('nom') + '\nOrganisation : ' + (v('organisation') || '—') + '\nE-mail : ' + v('email') + '\n\n' + v('message');
			window.location.href = 'mailto:' + mail.trim() + '?subject=' + encodeURIComponent('[KRAAK] ' + v('objet')) + '&body=' + encodeURIComponent(body);
			var note = form.parentNode.querySelector('.notice') || document.createElement('p');
			note.className = 'notice notice--ok';
			note.setAttribute('role', 'status');
			note.textContent = 'Maquette : votre messagerie s’ouvre avec le message pré-rempli. Si rien ne s’ouvre, écrivez directement à ' + mail.trim() + '.';
			form.parentNode.insertBefore(note, form);
		});
	}
})();
