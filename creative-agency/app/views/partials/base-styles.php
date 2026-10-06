<!-- partials/base-styles.php — include di <head> setiap halaman -->
<style>
:root {
  --ink:    #0D0D0D; --paper: #F7F5F0; --cream: #EDEBE4;
  --accent: #C8412B; --accent2:#2B6CC8;
  --muted:  #7A7570; --white:  #FFFFFF;
  --gap:    clamp(1rem,4vw,2.5rem);
  --fh:     'Syne', sans-serif;
  --fb:     'Inter', sans-serif;
  --nav-h:  68px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--fb);background:var(--paper);color:var(--ink);overflow-x:hidden;line-height:1.6}
a{text-decoration:none;color:inherit}
img{max-width:100%;display:block}

/* NAV */
.nav{position:fixed;top:0;left:0;right:0;z-index:200;height:var(--nav-h);display:flex;align-items:center;padding:0 clamp(1rem,4vw,3rem);justify-content:space-between;transition:background .3s,border-color .3s,backdrop-filter .3s}
.nav.scrolled{background:rgba(247,245,240,.96);backdrop-filter:blur(14px);border-bottom:1px solid var(--cream)}
.nav.dark-nav{background:transparent}
.nav.dark-nav.scrolled{background:rgba(13,13,13,.96)}
.nav-logo{font-family:var(--fh);font-weight:800;font-size:1.25rem;letter-spacing:-.02em}
.nav-logo span{color:var(--accent)}
.nav-links{display:flex;gap:2rem;list-style:none}
.nav-links a{font-size:.875rem;font-weight:500;color:var(--muted);transition:color .15s}
.nav-links a:hover,.nav-links a.active{color:var(--ink)}
.dark-nav .nav-links a{color:#555}
.dark-nav .nav-links a:hover,.dark-nav .nav-links a.active{color:#fff}
.nav-right{display:flex;gap:.75rem;align-items:center}
.btn{display:inline-block;padding:.6rem 1.35rem;border-radius:4px;font-family:var(--fh);font-weight:700;font-size:.82rem;letter-spacing:.04em;cursor:pointer;border:2px solid transparent;transition:all .2s}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:#a83422}
.btn-outline{border-color:var(--ink);color:var(--ink)}
.btn-outline:hover{background:var(--ink);color:#fff}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px}
.nav-hamburger span{width:22px;height:2px;background:var(--ink);display:block;transition:all .3s}

/* SHARED SECTION */
.section{padding:6rem clamp(1rem,4vw,3rem)}
.s-inner{max-width:1200px;margin:0 auto}
.eyebrow{display:inline-flex;align-items:center;gap:.5rem;font-family:var(--fh);font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--accent);margin-bottom:1rem}
.eyebrow-line{width:2rem;height:2px;background:var(--accent);flex-shrink:0}
h2.stitle{font-family:var(--fh);font-weight:800;font-size:clamp(1.8rem,3.5vw,2.8rem);letter-spacing:-.025em;line-height:1.12;margin-bottom:.75rem}
.ssub{color:var(--muted);font-size:.95rem;max-width:52ch;line-height:1.8}

/* PAGE HERO (inner pages) */
.page-hero{background:var(--ink);padding:calc(var(--nav-h) + 4rem) clamp(1rem,4vw,3rem) 4rem;position:relative;overflow:hidden}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 70% 50%,rgba(200,65,43,.15) 0%,transparent 65%);pointer-events:none}
.page-hero .s-inner{position:relative;z-index:1}
.breadcrumb{display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem}
.breadcrumb a{font-size:.8rem;color:#555;transition:color .15s}
.breadcrumb a:hover{color:#999}
.breadcrumb-sep{color:#333;font-size:.8rem}
.breadcrumb span{font-size:.8rem;color:#888}
.page-hero h1{font-family:var(--fh);font-weight:800;font-size:clamp(2rem,5vw,3.5rem);line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:1rem}
.page-hero h1 em{font-style:normal;color:var(--accent)}
.page-hero p{color:#666;font-size:.95rem;line-height:1.8;max-width:52ch}

/* FOOTER */
.footer{background:#080808;padding:4rem clamp(1rem,4vw,3rem) 2rem}
.footer-top{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid #111;margin-bottom:2rem}
.f-logo{font-family:var(--fh);font-weight:800;font-size:1.3rem;letter-spacing:-.02em;color:#fff;margin-bottom:.85rem}
.f-logo span{color:var(--accent)}
.f-tagline{font-size:.82rem;color:#333;line-height:1.75;max-width:26ch}
.f-col h4{font-family:var(--fh);font-size:.67rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#2a2a2a;margin-bottom:1.1rem}
.f-col ul{list-style:none}
.f-col li{margin-bottom:.6rem}
.f-col a{font-size:.82rem;color:#3a3a3a;transition:color .15s}
.f-col a:hover{color:#777}
.footer-bottom{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.f-copy{font-size:.75rem;color:#222}
.f-social{display:flex;gap:.6rem}
.f-social a{width:32px;height:32px;border:1px solid #161616;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#2a2a2a;transition:all .15s}
.f-social a:hover{border-color:#333;color:#666}

/* ALERTS */
.alert{padding:.85rem 1.25rem;border-radius:4px;margin-bottom:1.5rem;font-size:.875rem}
.alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}
.alert-error{background:#fee2e2;color:#b91c1c;border:1px solid #fecaca}
.field-error{color:#b91c1c;font-size:.73rem;margin-top:.3rem}

/* REVEAL */
.reveal{opacity:0;transform:translateY(22px);transition:opacity .65s ease,transform .65s ease}
.reveal.visible{opacity:1;transform:translateY(0)}

/* CTA STRIP (shared) */
.cta-strip{background:var(--ink);padding:5rem clamp(1rem,4vw,3rem)}
.cta-strip-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr auto;gap:4rem;align-items:center}
.cta-strip h2{font-family:var(--fh);font-weight:800;font-size:clamp(1.6rem,3vw,2.5rem);letter-spacing:-.025em;line-height:1.15;color:#fff}
.cta-strip h2 em{font-style:normal;color:var(--accent)}
.cta-strip p{color:#555;font-size:.9rem;line-height:1.8;margin-top:.75rem;max-width:48ch}
.cta-strip-btns{display:flex;flex-direction:column;gap:.85rem;flex-shrink:0}
.btn-cta-p{padding:.9rem 1.85rem;background:var(--accent);color:#fff;border-radius:4px;font-family:var(--fh);font-weight:700;font-size:.9rem;letter-spacing:.04em;white-space:nowrap;transition:background .15s;display:block;text-align:center}
.btn-cta-p:hover{background:#a83422}
.btn-cta-g{padding:.9rem 1.85rem;border:1.5px solid #222;color:#555;border-radius:4px;font-family:var(--fh);font-weight:700;font-size:.9rem;letter-spacing:.04em;white-space:nowrap;transition:all .15s;display:block;text-align:center}
.btn-cta-g:hover{border-color:#444;color:#888}

/* RESPONSIVE */
@media(max-width:1024px){.footer-top{grid-template-columns:1fr 1fr;gap:2rem}}
@media(max-width:768px){
  .nav-links,.nav-right{display:none}
  .nav-hamburger{display:flex}
  .cta-strip-inner{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr 1fr}
}
@media(max-width:480px){.footer-top{grid-template-columns:1fr}}
</style>
