/* Nav scroll */
const nav = document.querySelector('.nav');
if (nav) {
  window.addEventListener('scroll', () => nav.style.boxShadow = scrollY > 8 ? '0 2px 20px rgba(0,0,0,0.4)' : 'none', { passive: true });
}

/* Marquee duplicate */
const mq = document.getElementById('mq1'); if (mq) mq.parentNode.appendChild(mq.cloneNode(true));

/* Intersection observer for animations */
if (typeof IntersectionObserver !== 'undefined') {
  const io = new IntersectionObserver(e => {
    e.forEach(en => {
      if (en.isIntersecting) {
        setTimeout(() => en.target.classList.add('vis'), (+(en.target.dataset.i || 0)) * 90);
        io.unobserve(en.target);
      }
    });
  }, { threshold: 0.08 });
  document.querySelectorAll('.fc,.sc-p,.st,.tcard,.cl-item,.sc2').forEach((el, i) => { el.dataset.i = i % 4; io.observe(el); });

  /* Scroll showcase */
  document.querySelectorAll('.sc-p').forEach(p => {
    new IntersectionObserver(e => {
      if (e[0].isIntersecting && e[0].intersectionRatio > 0.35) {
        const i = +e[0].target.dataset.panel;
        document.querySelectorAll('.ss').forEach((s, j) => s.classList.toggle('on', j === i));
        e[0].target.classList.add('vis');
      }
    }, { threshold: 0.35 }).observe(p);
  });

  /* Counter */
  const targets = [5, 2, 0, 0, 0];
  const cntSec = document.getElementById('cntSec');
  const tlFill = document.getElementById('tlFill');
  function animDigit(id, target, delay) {
    setTimeout(() => {
      const el = document.getElementById(id); if (!el) return;
      let cur = 0; const step = () => { el.textContent = cur; if (cur < target) { cur++; setTimeout(step, 150); } }; step();
    }, delay);
  }
  const cntObs = new IntersectionObserver(e => {
    if (e[0].isIntersecting) {
      targets.forEach((t, i) => animDigit('r' + i, t, i * 200));
      if (tlFill) setTimeout(() => tlFill.classList.add('go'), 500);
      cntObs.unobserve(cntSec);
    }
  }, { threshold: 0.25 });
  if (cntSec) cntObs.observe(cntSec);

  /* Progress bars reveal */
  const progObs = new IntersectionObserver(e => {
    if (e[0].isIntersecting) {
      setTimeout(() => {
        document.querySelectorAll('.cp-fill').forEach(f => { f.style.width = f.style.width; });
      }, 400);
      progObs.unobserve(e[0].target);
    }
  }, { threshold: 0.4 });
  const resSection = document.querySelector('.reseller');
  if (resSection) progObs.observe(resSection);

  /* Stats animate */
  document.querySelectorAll('.st').forEach((el, i) => {
    new IntersectionObserver(en => {
      if (en[0].isIntersecting) { setTimeout(() => el.classList.add('vis'), i * 100); en[0].target._io && en[0].target._io.unobserve(en[0].target); }
    }, { threshold: 0.4 }).observe(el);
  });
}

/* Mouse parallax on hero card */
document.addEventListener('mousemove', e => {
  const x = (e.clientX / innerWidth - .5) * 12, y = (e.clientY / innerHeight - .5) * 12;
  const card = document.querySelector('.card-inner');
  if (card) card.style.transform = `perspective(1000px) rotateX(${-y * .3}deg) rotateY(${x * .3}deg)`;
}, { passive: true });
