<!-- partials/base-styles.php — include di <head> setiap halaman -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --ink:    #122A1C; --paper: #FFFFFF; --cream: #E4E9E5;
  --accent: #C8FF4D; --accent2:#2B6CC8;
  --muted:  #5C6862; --white:  #FFFFFF;
  --gap:    clamp(1rem,4vw,2.5rem);
  --fh:     'Archivo Black', sans-serif;
  --fb:     'Inter', sans-serif;
  --fm:     'JetBrains Mono', monospace;
  --nav-h:  68px;
  --bw:     3px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--fb);background:var(--paper);color:var(--ink);overflow-x:hidden;line-height:1.6}
a{text-decoration:none;color:inherit}
img{max-width:100%;display:block}

/* MARQUEE TICKER */
.marquee{background:var(--ink);color:var(--white);border-bottom:var(--bw) solid var(--ink);overflow:hidden;white-space:nowrap;padding:.55rem 0}
.marquee-track{display:inline-block;animation:marquee 24s linear infinite}
.marquee-track span{font-family:var(--fm);font-size:.75rem;letter-spacing:.04em;text-transform:uppercase;padding:0 1.4rem;display:inline-flex;align-items:center;gap:1.4rem}
.marquee-track span::after{content:'●';color:var(--accent)}
@keyframes marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}
@media(prefers-reduced-motion:reduce){.marquee-track{animation:none}}

/* NAV */
.nav{position:sticky;top:0;left:0;right:0;z-index:200;height:var(--nav-h);display:flex;align-items:center;padding:0 clamp(1rem,4vw,3rem);justify-content:space-between;background:var(--white);border-bottom:var(--bw) solid var(--ink)}
.nav.dark-nav{background:var(--ink)}
.nav-logo{font-family:var(--fh);font-size:1.1rem;letter-spacing:-.01em;text-transform:uppercase}
.nav-logo span{color:var(--accent);-webkit-text-stroke:1px var(--ink)}
.dark-nav .nav-logo{color:#fff}
.nav-links{display:flex;gap:2rem;list-style:none}
.nav-links a{font-family:var(--fm);font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.02em;color:var(--ink);position:relative}
.nav-links a::after{content:'';position:absolute;left:0;bottom:-4px;width:0;height:2px;background:var(--ink);transition:width .18s}
.nav-links a:hover::after,.nav-links a.active::after{width:100%}
.dark-nav .nav-links a{color:#fff}
.nav-right{display:flex;gap:.75rem;align-items:center}
.btn{display:inline-block;padding:.65rem 1.4rem;border-radius:0;font-family:var(--fm);font-weight:700;font-size:.78rem;text-transform:uppercase;letter-spacing:.03em;cursor:pointer;border:2px solid var(--ink);transition:background .15s,color .15s,transform .15s,box-shadow .15s}
.btn-primary{background:var(--ink);color:#fff}
.btn-primary:hover{background:var(--accent);color:var(--ink);transform:translate(-2px,-2px);box-shadow:2px 2px 0 var(--ink)}
.btn-outline{border-color:var(--ink);color:var(--ink);background:transparent}
.btn-outline:hover{background:var(--ink);color:#fff;transform:translate(-2px,-2px);box-shadow:2px 2px 0 var(--accent)}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px}
.nav-hamburger span{width:22px;height:2px;background:var(--ink);display:block;transition:all .3s}
.dark-nav .nav-hamburger span{background:#fff}

/* SHARED SECTION */
.section{padding:6rem clamp(1rem,4vw,3rem)}
.s-inner{max-width:1200px;margin:0 auto}
.eyebrow{display:inline-flex;align-items:center;gap:.5rem;font-family:var(--fm);font-size:.75rem;font-weight:500;letter-spacing:.03em;text-transform:uppercase;color:var(--muted);margin-bottom:1rem}
.eyebrow::before{content:'//'}
.eyebrow-line{display:none}
h2.stitle{font-family:var(--fh);font-size:clamp(1.9rem,3.8vw,3rem);letter-spacing:-.01em;line-height:1.04;text-transform:uppercase;margin-bottom:.75rem}
.ssub{color:var(--muted);font-size:.95rem;max-width:52ch;line-height:1.8}

/* PAGE HERO (inner pages) */
.page-hero{background:var(--ink);padding:calc(var(--nav-h) + 4rem) clamp(1rem,4vw,3rem) 4rem;position:relative;overflow:hidden;border-bottom:var(--bw) solid var(--ink)}
.page-hero::before{content:'';position:absolute;inset:0;background:none;pointer-events:none}
.page-hero .s-inner{position:relative;z-index:1}
.breadcrumb{display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;font-family:var(--fm)}
.breadcrumb a{font-size:.78rem;color:#8FA697;transition:color .15s}
.breadcrumb a:hover{color:var(--accent)}
.breadcrumb-sep{color:#3E5747;font-size:.78rem}
.breadcrumb span{font-size:.78rem;color:#C7D4CB}
.page-hero h1{font-family:var(--fh);font-size:clamp(2.1rem,5vw,3.6rem);line-height:1.02;letter-spacing:-.01em;text-transform:uppercase;color:#fff;margin-bottom:1rem}
.page-hero h1 em{font-style:normal;background:var(--accent);color:var(--ink);box-decoration-break:clone;-webkit-box-decoration-break:clone;padding:0 .15em}
.page-hero p{color:#C7D4CB;font-size:.95rem;line-height:1.8;max-width:52ch}

/* FOOTER */
.footer{background:var(--ink);padding:4rem clamp(1rem,4vw,3rem) 2rem;border-top:var(--bw) solid var(--ink)}
.footer-top{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid #26402F;margin-bottom:2rem}
.f-logo{font-family:var(--fh);font-size:1.3rem;letter-spacing:-.01em;text-transform:uppercase;color:#fff;margin-bottom:.85rem}
.f-logo span{color:var(--accent);-webkit-text-stroke:1px #fff}
.f-tagline{font-size:.82rem;color:#8FA697;line-height:1.75;max-width:26ch}
.f-col h4{font-family:var(--fm);font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#5C7A67;margin-bottom:1.1rem}
.f-col ul{list-style:none}
.f-col li{margin-bottom:.6rem}
.f-col a{font-size:.82rem;color:#B7C7BC;transition:color .15s}
.f-col a:hover{color:var(--accent)}
.footer-bottom{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.f-copy{font-size:.75rem;color:#4A6656;font-family:var(--fm)}
.f-social{display:flex;gap:.6rem}
.f-social a{width:32px;height:32px;border:2px solid #26402F;border-radius:0;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#8FA697;transition:all .15s}
.f-social a:hover{border-color:var(--accent);color:var(--accent)}

/* ALERTS */
.alert{padding:.85rem 1.25rem;border-radius:0;border:2px solid;margin-bottom:1.5rem;font-size:.875rem}
.alert-success{background:#EAFBE0;color:#1F5C0F;border-color:#1F5C0F}
.alert-error{background:#FEE2E2;color:#B91C1C;border-color:#B91C1C}
.field-error{color:#B91C1C;font-size:.73rem;margin-top:.3rem}

/* REVEAL */
.reveal{opacity:0;transform:translateY(22px);transition:opacity .5s ease,transform .5s ease}
.reveal.visible{opacity:1;transform:translateY(0)}

/* CTA STRIP (shared) */
.cta-strip{background:var(--accent);padding:5rem clamp(1rem,4vw,3rem);border-top:var(--bw) solid var(--ink);border-bottom:var(--bw) solid var(--ink)}
.cta-strip-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr auto;gap:4rem;align-items:center}
.cta-strip h2{font-family:var(--fh);font-size:clamp(1.7rem,3.2vw,2.6rem);letter-spacing:-.01em;line-height:1.05;text-transform:uppercase;color:var(--ink)}
.cta-strip h2 em{font-style:normal;-webkit-text-stroke:1.5px var(--ink);color:var(--paper)}
.cta-strip p{color:#1B3A26;font-size:.9rem;line-height:1.8;margin-top:.75rem;max-width:48ch}
.cta-strip-btns{display:flex;flex-direction:column;gap:.85rem;flex-shrink:0}
.btn-cta-p{padding:.95rem 1.9rem;background:var(--ink);color:#fff;border:2px solid var(--ink);border-radius:0;font-family:var(--fm);font-weight:700;font-size:.85rem;text-transform:uppercase;letter-spacing:.03em;white-space:nowrap;transition:transform .15s,box-shadow .15s;display:block;text-align:center}
.btn-cta-p:hover{transform:translate(-2px,-2px);box-shadow:2px 2px 0 var(--ink)}
.btn-cta-g{padding:.95rem 1.9rem;border:2px solid var(--ink);color:var(--ink);border-radius:0;font-family:var(--fm);font-weight:700;font-size:.85rem;text-transform:uppercase;letter-spacing:.03em;white-space:nowrap;transition:background .15s,color .15s;display:block;text-align:center;background:transparent}
.btn-cta-g:hover{background:var(--ink);color:var(--accent)}

/* RESPONSIVE */
@media(max-width:1024px){.footer-top{grid-template-columns:1fr 1fr;gap:2rem}}
@media(max-width:768px){
  .nav-links,.nav-right{display:none}
  .nav-hamburger{display:flex}
  .nav.mobile-open .nav-links,.nav.mobile-open .nav-right{
    display:flex;flex-direction:column;position:fixed;top:var(--nav-h);left:0;right:0;
    background:var(--paper);padding:1.5rem 2rem;gap:1.1rem;border-bottom:var(--bw) solid var(--ink);z-index:190
  }
  .nav.dark-nav.mobile-open .nav-links,.nav.dark-nav.mobile-open .nav-right{background:var(--ink);border-color:var(--ink)}
  .cta-strip-inner{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr 1fr}
}
.nav-hamburger.active span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.nav-hamburger.active span:nth-child(2){opacity:0}
.nav-hamburger.active span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
@media(max-width:480px){.footer-top{grid-template-columns:1fr}}
</style>
