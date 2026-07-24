{{-- Stamp <html data-theme> before first paint (no FOUC). Include in <head>
     BEFORE the stylesheet. Reads the shared `nexo-theme` cookie (scoped to the
     parent domain by the toggle in js/nexo-ui.js, so "dark in one tool = dark in
     all"), else the OS preference.

     nexolinks ships script-src with 'unsafe-inline', so this inline snippet needs
     no CSP hash. Kept a single line to match the rest of the ecosystem. --}}
<script>(function(){try{var m=document.cookie.match(/(?:^|; )nexo-theme=([^;]+)/);var mode=(m&&m[1])||(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');document.documentElement.setAttribute('data-theme',mode);}catch(e){}})();</script>
