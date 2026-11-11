"""
Capture promo screenshots of the running app (http://localhost:8080) with Playwright.

- Renames the app to the promo brand in the header + tab title
- Pseudonymises team labels that contain people's names, blurs people's names,
  staff IDs and IRIS codes, hides toasts
- Desktop 1440x900 and mobile 412x915 (both @2x for crisp output)
- Also writes full-page captures used by the motion video into promo/source/assets/

Usage:  python -I promo/source/capture.py
"""
import re
from pathlib import Path
from playwright.sync_api import sync_playwright

BASE = "http://localhost:8080"
BRAND = "Trackwyn"
LOGIN = ("1234abn", "adjn21")
ROOT = Path(__file__).resolve().parents[1]
OUT = {"desktop": ROOT / "screenshots" / "desktop", "mobile": ROOT / "screenshots" / "mobile"}
ASSETS = ROOT / "source" / "assets"

HTML_REWRITES = [
    ("BE Tracker", BRAND),
    ("NRO-Megat", "NRO-East"),
    ("NRO-Ain", "NRO-West"),
    ("NROAin", "NRO-West"),
]

MASK_SCRIPT = r"""
(() => {
  const SWAP = [['BE Tracker', 'TRACKWYN_BRAND'], ['NRO-Megat', 'NRO-East'], ['NRO-Ain', 'NRO-West'], ['NROAin', 'NRO-West']];
  const NAMES = ['hashim akmal kamarul', 'widad rusli', 'megat', 'abu', 'usop', 'cong',
                 '123acva', 'ahs1234', '1234abn', 'abcjk12', 'bhkjd12', 'abcj1243'];
  const PII = String.raw`\b(` + NAMES.map(n => n.replace(/\s+/g, String.raw`\s+`)).join('|') + String.raw`|C3\d{4}|65332|adssad|eqweqwe)\b`;
  const PII_TEST = new RegExp(PII, 'i');
  const swap = t => SWAP.reduce((acc, [a, b]) => acc.split(a).join(b), t);
  const css = `.pii{filter:blur(9px);user-select:none}
    .toast-container{display:none!important}
    .bulk-bar:not(.show){display:none!important}
    .avatar{filter:blur(4px)}
    input.pii-input{filter:blur(7px)}`;

  // Rewrite JSON data islands (chart + table data) before the page scripts read them.
  new MutationObserver(muts => {
    for (const m of muts) for (const n of m.addedNodes) {
      if (n.nodeName === 'SCRIPT' && n.type === 'application/json') n.textContent = swap(n.textContent);
    }
  }).observe(document, { childList: true, subtree: true });

  function process(root) {
    if (document.title.includes('BE Tracker')) document.title = swap(document.title);
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
    const nodes = [];
    while (walker.nextNode()) {
      const n = walker.currentNode, el = n.parentElement;
      if (!el || el.closest('script,style,textarea,title')) continue;
      nodes.push(n);
    }
    nodes.forEach(n => {
      const swapped = swap(n.nodeValue);
      if (swapped !== n.nodeValue) n.nodeValue = swapped;
      if (n.parentElement.closest('.pii') || !PII_TEST.test(n.nodeValue)) return;
      const html = n.nodeValue.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]))
        .replace(new RegExp(PII, 'gi'), '<span class="pii">$1</span>');
      const span = document.createElement('span');
      span.innerHTML = html;
      n.replaceWith(...span.childNodes);
    });
    document.querySelectorAll('input').forEach(i => {
      if (i.type === 'hidden') return;
      const v = swap(i.value); if (v !== i.value) i.value = v;
      if (i.id === 'iris' && i.value) i.classList.add('pii-input');
    });
  }
  document.addEventListener('DOMContentLoaded', () => {
    const st = document.createElement('style'); st.textContent = css; document.head.appendChild(st);
    process(document.body);
    let queued = false;
    new MutationObserver(() => {
      if (queued) return; queued = true;
      setTimeout(() => { process(document.body); queued = false; }, 0);
    }).observe(document.body, { childList: true, subtree: true, characterData: true });
  });
})();
""".replace("TRACKWYN_BRAND", BRAND)


def settle(page, ms=500):
    page.wait_for_load_state("networkidle")
    page.wait_for_timeout(ms)


def shot(page, device, name, full=False):
    settle(page, 350)
    path = OUT[device] / f"{name}.png"
    page.screenshot(path=str(path), full_page=full)
    print("saved", path.relative_to(ROOT))


BOXES = {}
_bx = Path(__file__).resolve().parents[1] / "source" / "assets" / "boxes.json"
if _bx.exists():
    BOXES.update(__import__("json").loads(_bx.read_text()))


def asset(page, name, full=True, sel=()):
    """Save a video source image plus CSS-pixel boxes of selectors (document coords if full)."""
    settle(page, 350)
    page.screenshot(path=str(ASSETS / f"{name}.png"), full_page=full)
    info = page.evaluate(
        """([sels, full]) => {
          const out = {}, sy = full ? scrollY : 0;
          for (const s of sels) { const el = document.querySelector(s); if (!el) continue;
            const r = el.getBoundingClientRect();
            out[s] = {x: r.left, y: r.top + sy, w: r.width, h: r.height}; }
          return {vw: innerWidth, vh: innerHeight, docH: document.documentElement.scrollHeight, boxes: out};
        }""",
        [list(sel), full],
    )
    BOXES[name] = info
    (ASSETS / "boxes.json").write_text(__import__("json").dumps(BOXES, indent=1))
    print("asset", name)


def scroll_to(page, selector, offset=24):
    page.evaluate(
        "([s,o]) => { const el = document.querySelector(s); window.scrollTo(0, el.getBoundingClientRect().top + scrollY - o); }",
        [selector, offset],
    )
    page.wait_for_timeout(300)


def fill_new_job(page):
    page.fill("#project_system", "Partner Onboarding")
    page.fill("#brief_description", "Self-service onboarding flow for new business partners")
    page.fill("#workstream", "Enterprise")
    page.fill("#ws_code", "8c")
    page.select_option("#svp_others", "SVP10 - NDDigital")
    page.fill("#iris", "C36288")
    page.fill("#mot", "MOT15")
    page.click("label[for=dev0]")
    page.click("label[for=dev1]")
    page.click("label[for=st3]")  # Build
    page.fill("#be_submission", "2026-11-03")
    page.fill("#assessment_completed", "2026-11-10")
    page.fill("#target_systemrfs", "2027-01-29")
    page.fill("#git_internalmd", "24")
    page.fill("#gnt_internalmd", "12")
    page.fill("#gnt_externalmd", "6")
    page.fill("#rm_gnt_external", "5088")
    page.fill("#remark", "Kick-off done, design approved")
    page.evaluate("document.activeElement && document.activeElement.blur()")


def run(pw, device):
    browser = pw.chromium.launch()
    opts = dict(device_scale_factor=2, reduced_motion="reduce", color_scheme="light", locale="en-GB",
                timezone_id="Asia/Kuala_Lumpur")
    if device == "desktop":
        opts.update(viewport={"width": 1440, "height": 900})
    else:
        opts.update(viewport={"width": 412, "height": 915}, is_mobile=True, has_touch=True)
    ctx = browser.new_context(**opts)
    ctx.add_init_script("try{localStorage.setItem('theme','light')}catch(e){}")
    ctx.add_init_script(MASK_SCRIPT)
    page = ctx.new_page()
    mobile = device == "mobile"

    # --- Sign in ---
    page.goto(f"{BASE}/index.php")
    shot(page, device, "01-sign-in")
    if not mobile:
        asset(page, "login", full=False)
    page.fill("#staff_id", "demo.user")
    page.fill("#password", "wrong-password")
    page.click("button[type=submit]")
    shot(page, device, "02-sign-in-error")

    page.fill("#staff_id", LOGIN[0])
    page.fill("#password", LOGIN[1])
    page.click("button[type=submit]")
    page.wait_for_url("**/dashboard.php")
    settle(page, 1200)

    # --- Dashboard ---
    shot(page, device, "03-dashboard")
    asset(page, f"{device}-dashboard-full", sel=[".kpi-hero", "#statusChart", "#devChart", "#jobsTable", ".filter-chip[data-status='Completed']", ".page-head .btn-primary", ".card-x.kpi"])

    if mobile:
        page.click(".topbar [data-bs-toggle=offcanvas]")
        page.wait_for_timeout(600)
        shot(page, device, "04-navigation-menu")
        page.click("#sidebar .btn-close")
        page.wait_for_timeout(500)

    page.evaluate("document.querySelector('[data-theme-toggle]').click()")
    page.wait_for_timeout(900)
    shot(page, device, "05-dashboard-dark-mode")
    asset(page, f"{device}-dashboard-dark", full=False)
    page.evaluate("document.querySelector('[data-theme-toggle]').click()")
    page.wait_for_timeout(900)

    scroll_to(page, "#jobsTable", 200 if not mobile else 260)
    shot(page, device, "06-jobs-table")

    page.locator(".row-toggle").first.click()
    page.wait_for_timeout(400)
    scroll_to(page, "#jobsTable tbody tr", 90)
    shot(page, device, "07-job-details-expanded")
    if not mobile:
        asset(page, "desktop-details", full=False, sel=[".detail-panel", ".row-toggle"])
    page.locator(".row-toggle").first.click()

    page.click(".filter-chip[data-status='Completed']")
    scroll_to(page, "#statusFilter", 120)
    shot(page, device, "08-filter-by-status")
    if not mobile:
        asset(page, "desktop-filter", full=False, sel=[".row-toggle", "#statusFilter"])
    page.click(".filter-chip[data-status='']")

    scroll_to(page, "#jobsTable", 200 if not mobile else 260)
    checks = page.locator("tbody .row-check")
    checks.nth(0).check()
    checks.nth(1).check()
    page.wait_for_timeout(500)
    shot(page, device, "09-bulk-select")
    if not mobile:
        asset(page, "desktop-bulk", full=False, sel=["#bulkBar", "#bulkDelete", "tbody tr:nth-child(2) .row-check"])

    page.click("#bulkDelete")
    page.wait_for_timeout(600)
    shot(page, device, "10-delete-confirmation")
    if not mobile:
        asset(page, "desktop-modal", full=False, sel=["#confirmModal .modal-content"])
    page.click("#confirmModal [data-bs-dismiss=modal]")  # cancel - nothing is deleted
    page.wait_for_timeout(500)

    # --- New job ---
    page.goto(f"{BASE}/job_new.php")
    shot(page, device, "11-new-job-form")
    page.click("button[type=submit]")  # client-side validation only, nothing is saved
    page.wait_for_timeout(700)
    page.evaluate("window.scrollTo(0,0)")
    shot(page, device, "12-new-job-validation")

    page.goto(f"{BASE}/job_new.php")
    fill_new_job(page)
    page.evaluate("window.scrollTo(0,0)")
    shot(page, device, "13-new-job-filled")
    asset(page, f"{device}-newjob-full", sel=["#statusStepper", "label[for=st3]", "label[for=dev1]", ".cost-grid", ".summary-card", "#git_internalmd", "button[type=submit]", "#project_system"])
    scroll_to(page, "#statusStepper", 140)
    shot(page, device, "14-status-and-timeline")
    scroll_to(page, ".cost-grid", 160)
    shot(page, device, "15-cost-calculator")

    # --- Edit job (viewed only, not saved) ---
    page.goto(f"{BASE}/job_edit.php?id=13")
    shot(page, device, "16-edit-job")
    asset(page, f"{device}-edit-full", sel=[".summary-card", "#statusStepper"])
    scroll_to(page, ".cost-grid", 160)
    shot(page, device, "17-edit-job-costs")

    browser.close()


if __name__ == "__main__":
    for d in OUT.values():
        d.mkdir(parents=True, exist_ok=True)
    ASSETS.mkdir(parents=True, exist_ok=True)
    with sync_playwright() as pw:
        import sys
        for dev in (sys.argv[1:] or ["desktop", "mobile"]):
            run(pw, dev)
