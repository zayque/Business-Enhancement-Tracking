(function () {
  'use strict';

  var form = document.getElementById('jobForm');
  if (!form) return;

  var rate = parseFloat(form.dataset.mdRate) || 800;
  var $ = function (id) { return document.getElementById(id); };

  function num(el) {
    var n = parseFloat(String(el.value).replace(/,/g, ''));
    return isNaN(n) ? 0 : n;
  }
  function money(n) {
    return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  /* ---------- Live cost calculation ---------- */
  function recalc() {
    var gitInt = num($('git_internalmd')) * rate;
    var gntInt = num($('gnt_internalmd')) * rate;
    var gitExt = num($('rm_git_external'));
    var gntExt = num($('rm_gnt_external'));
    $('rm_git_internal').value = money(gitInt);
    $('rm_gnt_internal').value = money(gntInt);
    $('sumGitInt').textContent = money(gitInt);
    $('sumGntInt').textContent = money(gntInt);
    $('sumGitExt').textContent = money(gitExt);
    $('sumGntExt').textContent = money(gntExt);
    $('sumTotal').textContent = money(gitInt + gntInt + gitExt + gntExt);
  }
  form.addEventListener('input', function (ev) {
    if (ev.target.matches('[data-cost-md], [data-cost-ext]')) recalc();
    if (ev.target.dataset && ev.target.id && document.querySelector('[data-count-for="' + ev.target.id + '"]')) counter(ev.target);
  });
  recalc();

  /* ---------- Character counters ---------- */
  function counter(el) {
    var out = document.querySelector('[data-count-for="' + el.id + '"]');
    if (out) out.textContent = el.value.length;
  }
  document.querySelectorAll('[data-count-for]').forEach(function (out) { counter($(out.dataset.countFor)); });

  /* ---------- Status-dependent requirements ---------- */
  var conditional = {
    Deployed: ['actual_systemrfs'],
    Completed: ['rollout', 'git_internalmd', 'gnt_internalmd', 'gnt_externalmd']
  };
  var allConditional = ['actual_systemrfs', 'rollout', 'git_internalmd', 'gnt_internalmd', 'gnt_externalmd'];

  function currentStatus() {
    var checked = form.querySelector('input[name="status"]:checked');
    return checked ? checked.value : '';
  }

  function applyStatus() {
    var status = currentStatus();
    var needed = conditional[status] || [];
    allConditional.forEach(function (id) {
      var el = $(id);
      var req = needed.indexOf(id) !== -1;
      el.required = req;
      el.classList.toggle('is-conditional', req && !el.value);
    });

    // Stepper progress + summary badge
    var labels = form.querySelectorAll('#statusStepper label');
    var inputs = form.querySelectorAll('#statusStepper input');
    var idx = -1;
    inputs.forEach(function (inp, i) { if (inp.checked) idx = i; });
    labels.forEach(function (lab, i) { lab.classList.toggle('done', idx > -1 && i < idx); });

    if (idx > -1) {
      var lab = labels[idx];
      var icon = lab.querySelector('i').className;
      var color = lab.style.getPropertyValue('--status');
      $('sumStatus').innerHTML = '<span class="status-badge" style="--status:' + color + '"><i class="' + icon + '"></i>' + status + '</span>';
    }
  }
  form.addEventListener('change', function (ev) {
    if (ev.target.name === 'status') applyStatus();
    if (allConditional.indexOf(ev.target.id) !== -1) applyStatus();
    if (ev.target.name === 'developer[]') $('developerGroup').classList.remove('is-invalid');
  });
  applyStatus();

  /* ---------- Client-side validation (server validates again) ---------- */
  form.addEventListener('submit', function (ev) {
    var firstInvalid = null;
    form.querySelectorAll('input, select, textarea').forEach(function (el) {
      if (el.type === 'radio' || el.type === 'checkbox' || el.type === 'hidden') return;
      var bad = !el.checkValidity();
      el.classList.toggle('is-invalid', bad);
      if (bad && !firstInvalid) firstInvalid = el;
    });

    var devOk = form.querySelectorAll('input[name="developer[]"]:checked').length > 0;
    $('developerGroup').classList.toggle('is-invalid', !devOk);
    if (!devOk && !firstInvalid) firstInvalid = $('developerGroup');

    var statusOk = currentStatus() !== '';
    $('statusStepper').classList.toggle('is-invalid', !statusOk);
    if (!statusOk && !firstInvalid) firstInvalid = $('statusStepper');

    if (firstInvalid) {
      ev.preventDefault();
      firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      if (firstInvalid.focus) firstInvalid.focus({ preventScroll: true });
      return;
    }
    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving…';
  });

  form.addEventListener('input', function (ev) {
    if (ev.target.classList.contains('is-invalid') && ev.target.checkValidity()) ev.target.classList.remove('is-invalid');
  });
})();
