/* Antarmuka perpustakaan — semua data lewat REST API (HTTP/JSON). */
const API = document.body.dataset.api;
const BASE = document.body.dataset.base;
const PAGE = document.body.dataset.page;
const $ = (s, r = document) => r.querySelector(s);
const esc = (v) => String(v ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

const Auth = {
  get token() { try { return localStorage.getItem('lib_token'); } catch { return null; } },
  get user() { try { return JSON.parse(localStorage.getItem('lib_user') || 'null'); } catch { return null; } },
  save(t, u) { localStorage.setItem('lib_token', t); localStorage.setItem('lib_user', JSON.stringify(u)); },
  clear() { localStorage.removeItem('lib_token'); localStorage.removeItem('lib_user'); },
};

async function api(path, { method = 'GET', body } = {}) {
  const headers = { Accept: 'application/json' };
  if (body) headers['Content-Type'] = 'application/json';
  if (Auth.token) headers.Authorization = 'Bearer ' + Auth.token;
  const res = await fetch(API + path, { method, headers, body: body ? JSON.stringify(body) : undefined });
  const json = await res.json().catch(() => ({}));
  if (res.status === 401 && Auth.token) { Auth.clear(); }
  if (!res.ok) { const e = new Error(json.message || 'Terjadi kesalahan.'); e.status = res.status; throw e; }
  return json;
}

function toast(msg, bad = false) {
  const t = $('#toast'); t.textContent = msg; t.className = 'toast show' + (bad ? ' bad' : '');
  clearTimeout(toast.t); toast.t = setTimeout(() => (t.className = 'toast'), 3200);
}

const fmtDate = (d) => d ? new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '–';
const PALETTE = ['#4a6b55', '#8a5a44', '#3b5b7a', '#7a4a6b', '#9a7b3a', '#5a6b3a'];
const hue = (s) => PALETTE[[...s].reduce((a, c) => a + c.charCodeAt(0), 0) % PALETTE.length];

function coverHtml(b) {
  if (b.cover_url) return `<div class="cover"><img src="${esc(b.cover_url)}" alt="Sampul ${esc(b.judul)}"></div>`;
  return `<div class="cover"><div class="cover-gen" style="background:${hue(b.judul)}"><span>${esc(b.judul)}</span><small>${esc(b.penulis)}</small></div></div>`;
}
const badge = (b) => `<span class="badge ${b.tersedia ? 'ok' : 'no'}">${esc(b.status)}</span>`;

function bookCard(b) {
  return `<article class="book">${coverHtml(b)}<h4>${esc(b.judul)}</h4><span class="by">${esc(b.penulis)}</span>
    <span class="cat-tag">${esc(b.kategori)}</span>${badge(b)}
    <a class="btn" href="${BASE}/buku/${b.id}">Lihat Detail →</a></article>`;
}

function renderNav() {
  const box = $('#nav-auth');
  if (Auth.user && box) {
    box.innerHTML = `<span>${esc(Auth.user.name)}</span><button class="btn btn-dark btn-sm" id="btn-logout">Keluar</button>`;
    $('#btn-logout').onclick = async () => {
      try { await api('/auth/logout', { method: 'POST' }); } catch {}
      Auth.clear(); location.href = BASE + '/';
    };
  }
}

const pages = {
  async home() {
    const [s, c, l] = await Promise.all([api('/stats'), api('/categories'), api('/books?per_page=4&sort=terbaru')]);
    $('#st-buku').textContent = s.data.jumlah_buku; $('#st-kategori').textContent = s.data.jumlah_kategori; $('#st-anggota').textContent = s.data.jumlah_anggota;
    const icons = ['📖', '🎓', '🔬', '🏺', '🌱', '🧒', '💻', '🌐'];
    $('#cat-grid').innerHTML = c.data.map((k, i) => `<a class="cat" href="${BASE}/katalog?kategori=${encodeURIComponent(k.kategori)}">
      <div class="cat-top tone-${i % 5}">${icons[i % icons.length]}</div>
      <div class="cat-body"><strong>${esc(k.kategori)}</strong><small>${k.jumlah} Buku</small></div></a>`).join('');
    $('#latest-grid').innerHTML = l.data.map(bookCard).join('');
  },

  async catalog() {
    const params = new URLSearchParams(location.search);
    const st = { q: params.get('q') || '', kategori: params.get('kategori') || '', status: params.get('status') || '', sort: 'terbaru', page: 1 };
    const cats = await api('/categories');
    $('#f-kategori').innerHTML += cats.data.map((k) => `<option ${k.kategori === st.kategori ? 'selected' : ''}>${esc(k.kategori)}</option>`).join('');
    $('#f-q').value = st.q; $('#f-status').value = st.status;
    async function load() {
      const qs = new URLSearchParams({ per_page: 8, page: st.page, sort: st.sort });
      ['q', 'kategori', 'status'].forEach((k) => st[k] && qs.set(k, st[k]));
      const r = await api('/books?' + qs);
      $('#book-grid').innerHTML = r.data.map(bookCard).join('');
      $('#empty').hidden = r.data.length > 0;
      $('#pager').innerHTML = Array.from({ length: r.meta.last_page }, (_, i) => `<button class="${i + 1 === r.meta.current_page ? 'on' : ''}" data-p="${i + 1}">${i + 1}</button>`).join('');
    }
    $('#pager').onclick = (e) => { if (e.target.dataset.p) { st.page = +e.target.dataset.p; load(); } };
    let timer; $('#f-q').oninput = (e) => { clearTimeout(timer); timer = setTimeout(() => { st.q = e.target.value; st.page = 1; load(); }, 300); };
    $('#f-kategori').onchange = (e) => { st.kategori = e.target.value; st.page = 1; load(); };
    $('#f-status').onchange = (e) => { st.status = e.target.value; st.page = 1; load(); };
    $('#f-sort').onchange = (e) => { st.sort = e.target.value; st.page = 1; load(); };
    load();
  },

  async detail() {
    const id = document.body.dataset.bookId;
    const box = $('#detail');
    async function draw() {
      try {
        const { data: b } = await api('/books/' + id);
        box.innerHTML = `<div class="detail-side">${coverHtml(b)}${badge(b)}
          <button class="btn btn-dark" id="btn-pinjam" ${b.tersedia ? '' : 'disabled'}>${b.tersedia ? 'Pinjam Buku' : 'Tidak Tersedia'}</button></div>
          <div><h2>${esc(b.judul)}</h2><p class="muted">${esc(b.penulis)}</p><span class="badge ok" style="background:#eee8d6;color:#555">${esc(b.kategori)}</span>
          <dl><dt>Penulis</dt><dd>${esc(b.penulis)}</dd><dt>Penerbit</dt><dd>${esc(b.penerbit || '–')}</dd><dt>Tahun Terbit</dt><dd>${esc(b.tahun_terbit || '–')}</dd>
          <dt>Jumlah Halaman</dt><dd>${esc(b.jumlah_halaman || '–')}</dd><dt>ISBN</dt><dd>${esc(b.isbn || '–')}</dd></dl>
          <h3>Deskripsi</h3><p>${esc(b.deskripsi)}</p></div>`;
        $('#btn-pinjam').onclick = async () => {
          if (!Auth.token) { toast('Silakan login terlebih dahulu.', true); return setTimeout(() => (location.href = BASE + '/masuk'), 900); }
          try { const r = await api('/borrowings', { method: 'POST', body: { book_id: b.id } }); toast(`${r.message} Jatuh tempo ${fmtDate(r.data.jatuh_tempo)}.`); draw(); }
          catch (e) { toast(e.message, true); }
        };
      } catch (e) { box.innerHTML = `<p class="error">${esc(e.message)}</p>`; }
    }
    draw();
  },

  async loans() {
    if (!Auth.token) { location.href = BASE + '/masuk'; return; }
    let status = 'dipinjam';
    async function load() {
      try {
        const r = await api('/borrowings?status=' + status);
        $('#loan-rows').innerHTML = r.data.map((x, i) => `<tr><td>${i + 1}</td><td>${esc(x.book?.judul)}</td><td>${fmtDate(x.tanggal_pinjam)}</td>
          <td>${fmtDate(x.jatuh_tempo)}</td><td><span class="badge ${x.terlambat ? 'late' : 'ok'}">${x.status === 'dikembalikan' ? 'Dikembalikan' : x.terlambat ? 'Terlambat' : 'Dipinjam'}</span></td>
          <td>${x.status === 'dipinjam' ? `<button class="btn btn-dark btn-sm" data-id="${x.id}">Kembalikan</button>` : fmtDate(x.tanggal_kembali)}</td></tr>`).join('');
        $('#loan-info').textContent = r.data.length ? (status === 'dipinjam' ? `${r.data.length} dari maksimal 3 buku aktif.` : '') : 'Belum ada data.';
      } catch (e) { if (e.status === 401) location.href = BASE + '/masuk'; else toast(e.message, true); }
    }
    document.querySelectorAll('.tab').forEach((t) => (t.onclick = () => { document.querySelectorAll('.tab').forEach((x) => x.classList.remove('active')); t.classList.add('active'); status = t.dataset.status; load(); }));
    $('#loan-rows').onclick = async (e) => {
      const id = e.target.dataset.id; if (!id) return;
      try { const r = await api(`/borrowings/${id}/return`, { method: 'PATCH' }); toast(r.message); load(); } catch (er) { toast(er.message, true); }
    };
    load();
  },

  login() {
    $('#login-form').onsubmit = async (e) => {
      e.preventDefault(); const f = new FormData(e.target); const err = $('#login-error'); err.hidden = true;
      try { const r = await api('/auth/login', { method: 'POST', body: { nim: f.get('nim'), nama: f.get('nama') } }); Auth.save(r.token, r.user); location.href = BASE + '/katalog'; }
      catch (er) { err.textContent = er.message; err.hidden = false; }
    };
  },
};

renderNav();
if (pages[PAGE]) pages[PAGE]().catch((e) => toast(e.message, true));
