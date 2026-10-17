{{-- Ilustrasi pemandangan (SVG buatan sendiri). Param: $p = prefix id unik --}}
<svg class="scenery" viewBox="0 0 800 420" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
  <defs>
    <linearGradient id="{{ $p }}sky" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#cfe3c0"/><stop offset=".55" stop-color="#f4efc9"/><stop offset="1" stop-color="#f9e6b6"/></linearGradient>
    <radialGradient id="{{ $p }}sun" cx=".5" cy=".5" r=".5"><stop offset="0" stop-color="#fffbe0" stop-opacity="1"/><stop offset="1" stop-color="#fff3b8" stop-opacity="0"/></radialGradient>
    <linearGradient id="{{ $p }}h1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#b7d19e"/><stop offset="1" stop-color="#9dbf86"/></linearGradient>
    <linearGradient id="{{ $p }}h2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8fb279"/><stop offset="1" stop-color="#6f9360"/></linearGradient>
    <linearGradient id="{{ $p }}h3" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#5f8a52"/><stop offset="1" stop-color="#3f6a3a"/></linearGradient>
  </defs>
  <rect width="800" height="420" fill="url(#{{ $p }}sky)"/>
  <circle cx="610" cy="90" r="150" fill="url(#{{ $p }}sun)"/>
  <circle cx="610" cy="90" r="34" fill="#fff6c9" opacity=".9"/>
  <path d="M0 250 C120 190 220 230 330 205 C450 178 560 235 800 190 L800 420 L0 420Z" fill="url(#{{ $p }}h1)"/>
  <g fill="#7fa36c" opacity=".75"><circle cx="90" cy="228" r="34"/><circle cx="130" cy="236" r="26"/><circle cx="690" cy="205" r="38"/><circle cx="735" cy="214" r="28"/></g>
  <path d="M0 300 C140 250 260 300 400 270 C540 240 650 290 800 255 L800 420 L0 420Z" fill="url(#{{ $p }}h2)"/>
  {{-- rumah kecil --}}
  <g transform="translate(470 215)"><rect x="0" y="22" width="86" height="48" rx="4" fill="#f6ecd2"/><polygon points="-8,26 43,-8 94,26" fill="#c8826a"/><rect x="14" y="38" width="16" height="16" rx="3" fill="#f3c97a"/><rect x="56" y="38" width="16" height="16" rx="3" fill="#f3c97a"/><rect x="38" y="46" width="14" height="24" rx="3" fill="#8a5b49"/></g>
  <g fill="#4f7d47"><circle cx="420" cy="262" r="26"/><circle cx="445" cy="272" r="20"/><circle cx="590" cy="258" r="24"/><circle cx="612" cy="268" r="18"/></g>
  <path d="M0 350 C160 310 300 355 450 330 C600 305 700 345 800 320 L800 420 L0 420Z" fill="url(#{{ $p }}h3)"/>
  <g fill="#8fb676" opacity=".9"><circle cx="70" cy="330" r="40"/><circle cx="118" cy="344" r="30"/><circle cx="740" cy="322" r="42"/><circle cx="690" cy="338" r="30"/></g>
  <path d="M300 420 C330 380 360 360 400 345" stroke="#f1e6bf" stroke-width="14" stroke-linecap="round" fill="none" opacity=".7"/>
</svg>
