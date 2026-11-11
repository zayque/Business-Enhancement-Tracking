(function () {
  'use strict';

  var jobs = JSON.parse(document.getElementById('jobsData').textContent);
  var charts = JSON.parse(document.getElementById('chartData').textContent);
  var csrf = document.querySelector('meta[name="csrf-token"]').content;

  function esc(v) {
    return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  function money(v) {
    var n = parseFloat(String(v).replace(/,/g, ''));
    return (isNaN(n) ? 0 : n).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  function fmtDate(v) {
    if (!v || v === '0000-00-00') return '<span class="text-muted">—</span>';
    var d = new Date(String(v).replace(' ', 'T'));
    if (isNaN(d)) return esc(v);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  }
  function statusBadge(r) {
    return '<span class="status-badge" style="--status:' + esc(r.color) + '"><i class="bi ' + esc(r.icon) + '"></i>' + esc(r.status || 'Unknown') + '</span>';
  }
  function cssVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
  }

  /* ---------- Charts ---------- */
  var statusChart, devChart;
  function buildCharts() {
    if (!window.Chart) return;
    Chart.defaults.font.family = '"Plus Jakarta Sans", system-ui, sans-serif';
    Chart.defaults.color = cssVar('--app-muted');
    var border = cssVar('--app-border');
    var surface = cssVar('--app-surface');

    if (statusChart) statusChart.destroy();
    if (devChart) devChart.destroy();

    var hasData = charts.status.values.some(function (v) { return v > 0; });
    statusChart = new Chart(document.getElementById('statusChart'), {
      type: 'doughnut',
      data: {
        labels: charts.status.labels,
        datasets: [{
          data: hasData ? charts.status.values : [1],
          backgroundColor: hasData ? charts.status.colors : [border],
          borderColor: surface, borderWidth: 3, hoverOffset: 6, borderRadius: 6
        }]
      },
      options: {
        cutout: '72%', maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: hasData } }
      }
    });

    var ctx = document.getElementById('devChart').getContext('2d');
    var grad = ctx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, '#a3294f');
    grad.addColorStop(1, 'rgba(139, 30, 63, .25)');
    devChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: charts.developers.labels,
        datasets: [{ label: 'Jobs', data: charts.developers.values, backgroundColor: grad, borderRadius: 10, maxBarThickness: 56 }]
      },
      options: {
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, border: { display: false } },
          y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: border }, border: { display: false } }
        }
      }
    });
  }
  buildCharts();
  document.addEventListener('themechange', buildCharts);

  /* ---------- Table ---------- */
  var tableEl = document.getElementById('jobsTable');
  if (!tableEl || !window.DataTable) return;

  var selected = new Set();
  // Declared before the table is built: DataTables calls drawCallback during construction.
  var bulkBar = document.getElementById('bulkBar');
  var checkAll = document.getElementById('checkAll');

  var table = new DataTable(tableEl, {
    data: jobs,
    order: [[2, 'desc']],
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    language: { search: '', searchPlaceholder: 'Search jobs…', lengthMenu: '_MENU_ per page', emptyTable: 'No jobs found' },
    layout: {
      topStart: 'search', topEnd: 'pageLength',
      bottomStart: 'info', bottomEnd: 'paging'
    },
    columns: [
      { data: 'sysid', orderable: false, searchable: false,
        render: function (id) { return '<input class="form-check-input row-check" type="checkbox" value="' + esc(id) + '" aria-label="Select row">'; } },
      { data: null, orderable: false, searchable: false, defaultContent: '<button type="button" class="row-toggle" aria-label="Show details"><i class="bi bi-chevron-right"></i></button>' },
      { data: 'no', className: 'text-muted fw-semibold' },
      { data: 'project_system', render: function (v, type, r) {
          if (type !== 'display') return v + ' ' + r.brief_description;
          return '<div class="proj-title">' + esc(v || '—') + '</div><div class="proj-sub" title="' + esc(r.brief_description) + '">' + esc(r.brief_description) + '</div>';
        } },
      { data: 'workstream', render: function (v, type, r) {
          if (type !== 'display') return v + ' ' + r.ws_code;
          return '<div class="fw-semibold">' + esc(v || '—') + '</div><div class="proj-sub">' + esc(r.ws_code) + '</div>';
        } },
      { data: 'devs', render: function (v, type) {
          if (type !== 'display') return v.join(' ');
          return v.map(function (d) { return '<span class="dev-tag">' + esc(d) + '</span>'; }).join('') || '—';
        } },
      { data: 'status', render: function (v, type, r) { return type === 'display' ? statusBadge(r) : v; } },
      { data: 'target_systemrfs', render: function (v, type) { return type === 'display' ? fmtDate(v) : v; } },
      { data: 'total_cost', className: 'text-end fw-bold', render: function (v, type) { return type === 'display' ? money(v) : v; } },
      { data: 'last_update_timestamp', render: function (v, type, r) {
          var when = r.last_update_by || v;
          if (type !== 'display') return when;
          return '<div>' + fmtDate(when) + '</div><div class="proj-sub">' + esc(r.last_update_by_user || r.registered_by) + '</div>';
        } },
      { data: 'sysid', orderable: false, searchable: false, className: 'text-end',
        render: function (id) {
          return '<div class="row-actions">'
            + '<a class="btn btn-icon" href="job_edit.php?id=' + encodeURIComponent(id) + '" title="Edit" aria-label="Edit"><i class="bi bi-pencil-square"></i></a>'
            + '<button type="button" class="btn btn-icon danger row-delete" data-id="' + esc(id) + '" title="Delete" aria-label="Delete"><i class="bi bi-trash3"></i></button>'
            + '</div>';
        } }
    ],
    createdRow: function (row, data) {
      if (selected.has(String(data.sysid))) row.classList.add('selected');
    },
    drawCallback: function () {
      tableEl.querySelectorAll('.row-check').forEach(function (cb) { cb.checked = selected.has(cb.value); });
      syncCheckAll();
    }
  });

  function detail(r) {
    function item(label, value) { return '<div><dt>' + esc(label) + '</dt><dd>' + (value === undefined || value === '' ? '<span class="text-muted">—</span>' : value) + '</dd></div>'; }
    function txt(v) { return v ? esc(v) : ''; }
    return '<div class="detail-panel">'
      + '<div class="row g-4">'
      + '<div class="col-lg-5"><h4 class="detail-title">Overview</h4><dl class="detail-grid">'
      + item('Registered by', txt(r.registered_by))
      + item('SVP / Others', txt(r.svp_others))
      + item('IRIS', txt(r.iris))
      + item('MOT', txt(r.mot))
      + '</dl><dl class="detail-grid mt-3" style="grid-template-columns:1fr">'
      + item('Brief description', txt(r.brief_description))
      + item('Remark', txt(r.remark))
      + '</dl></div>'
      + '<div class="col-lg-3"><h4 class="detail-title">Timeline</h4><dl class="detail-grid" style="grid-template-columns:1fr 1fr">'
      + item('BE submission', fmtDate(r.be_submission))
      + item('Assessment done', fmtDate(r.assessment_completed))
      + item('Target RFS', fmtDate(r.target_systemrfs))
      + item('Revised RFS', fmtDate(r.revise_target_systemrfs))
      + item('Actual RFSI', fmtDate(r.actual_systemrfs))
      + item('Rollout', fmtDate(r.rollout))
      + '</dl></div>'
      + '<div class="col-lg-4"><h4 class="detail-title">BE cost</h4><dl class="detail-grid" style="grid-template-columns:1fr 1fr">'
      + item('GIT internal MD', txt(r.git_internalmd))
      + item('RM GIT internal', 'RM ' + money(r.rm_git_internal))
      + item('GIT external MD', txt(r.git_externalmd))
      + item('RM GIT external +6%', 'RM ' + money(r.rm_git_external))
      + item('GNT internal MD', txt(r.gnt_internalmd))
      + item('RM GNT internal', 'RM ' + money(r.rm_gnt_internal))
      + item('GNT external MD', txt(r.gnt_externalmd))
      + item('RM GNT external +6%', 'RM ' + money(r.rm_gnt_external))
      + '</dl></div>'
      + '</div></div>';
  }

  tableEl.addEventListener('click', function (ev) {
    var toggle = ev.target.closest('.row-toggle');
    if (toggle) {
      var row = table.row(toggle.closest('tr'));
      if (row.child.isShown()) { row.child.hide(); }
      else { row.child(detail(row.data())).show(); }
      return;
    }
    var del = ev.target.closest('.row-delete');
    if (del) { askDelete([del.dataset.id]); }
  });

  /* ---------- Status filter chips ---------- */
  var statusFilter = '';
  DataTable.ext.search.push(function (settings, searchData, index, rowData) {
    return settings.nTable !== tableEl || !statusFilter || rowData.status === statusFilter;
  });
  document.getElementById('statusFilter').addEventListener('click', function (ev) {
    var chip = ev.target.closest('.filter-chip');
    if (!chip) return;
    this.querySelectorAll('.filter-chip').forEach(function (c) { c.classList.toggle('active', c === chip); });
    statusFilter = chip.dataset.status;
    table.draw();
  });

  /* ---------- Selection & bulk delete ---------- */
  function syncBulk() {
    document.getElementById('bulkCount').textContent = selected.size;
    bulkBar.classList.toggle('show', selected.size > 0);
  }
  function syncCheckAll() {
    var boxes = tableEl.querySelectorAll('tbody .row-check');
    var checked = tableEl.querySelectorAll('tbody .row-check:checked');
    checkAll.checked = boxes.length > 0 && boxes.length === checked.length;
    checkAll.indeterminate = checked.length > 0 && checked.length < boxes.length;
  }

  tableEl.addEventListener('change', function (ev) {
    if (!ev.target.classList.contains('row-check')) return;
    var tr = ev.target.closest('tr');
    if (ev.target.checked) { selected.add(ev.target.value); tr.classList.add('selected'); }
    else { selected.delete(ev.target.value); tr.classList.remove('selected'); }
    syncCheckAll();
    syncBulk();
  });

  checkAll.addEventListener('change', function () {
    var on = checkAll.checked;
    tableEl.querySelectorAll('tbody .row-check').forEach(function (cb) {
      cb.checked = on;
      cb.closest('tr').classList.toggle('selected', on);
      if (on) selected.add(cb.value); else selected.delete(cb.value);
    });
    syncBulk();
  });

  document.getElementById('bulkClear').addEventListener('click', function () {
    selected.clear();
    tableEl.querySelectorAll('tbody tr.selected').forEach(function (tr) { tr.classList.remove('selected'); });
    table.draw(false);
    syncBulk();
  });
  document.getElementById('bulkDelete').addEventListener('click', function () { askDelete(Array.from(selected)); });

  var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
  var pendingIds = [];
  function askDelete(ids) {
    pendingIds = ids;
    document.getElementById('confirmCount').textContent = ids.length;
    modal.show();
  }

  document.getElementById('confirmDelete').addEventListener('click', function () {
    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Deleting…';
    var body = new URLSearchParams();
    body.append('_token', csrf);
    pendingIds.forEach(function (id) { body.append('ids[]', id); });

    fetch('job_delete.php', { method: 'POST', body: body, headers: { 'Accept': 'application/json' } })
      .then(function (res) { if (!res.ok) throw new Error(res.status); return res.json(); })
      .then(function () { window.location.reload(); })
      .catch(function () {
        btn.disabled = false;
        btn.textContent = 'Yes, delete';
        alert('Delete failed. Please refresh the page and try again.');
      });
  });
})();
