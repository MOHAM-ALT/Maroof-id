/* resources/js/maroof-home.js */

/* Nav scroll */
const nav = document.querySelector('.nav');
if (nav) {
  window.addEventListener('scroll', () => {
    if (nav) {
      nav.style.boxShadow = window.scrollY > 8 ? '0 2px 20px rgba(0,0,0,0.4)' : 'none';
      nav.classList.toggle('scrolled', window.scrollY > 8);
    }
  }, { passive: true });
}

/* Marquee duplicate */
const mq = document.getElementById('mq1');
if (mq) {
  const clone = mq.cloneNode(true);
  mq.parentNode.appendChild(clone);
}

/* Intersection observer for standard sections */
if (typeof IntersectionObserver !== 'undefined') {
  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const delay = (+(entry.target.dataset.i || 0)) * 90;
        setTimeout(() => {
          entry.target.classList.add('vis');
        }, delay);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 });

  document.querySelectorAll('.fc, .sc-p, .st, .tcard, .cl-item, .sc2').forEach((el, i) => {
    el.dataset.i = i % 4;
    io.observe(el);
  });

  /* Scroll showcase logic */
  document.querySelectorAll('.sc-p').forEach(p => {
    new IntersectionObserver(entries => {
      if (entries[0].isIntersecting && entries[0].intersectionRatio > 0.35) {
        const i = +entries[0].target.dataset.panel;
        document.querySelectorAll('.ss').forEach((s, j) => {
          s.classList.toggle('on', j === i);
        });
        entries[0].target.classList.add('vis');
      }
    }, { threshold: 0.35 }).observe(p);
  });

  /* Counter Animation */
  const targets = [5, 2, 0, 0, 0];
  const cntSec = document.getElementById('cntSec');
  const tlFill = document.getElementById('tlFill');

  function animDigit(id, target, delay) {
    setTimeout(() => {
      const el = document.getElementById(id);
      if (!el) return;
      let cur = 0;
      const step = () => {
        el.textContent = cur;
        if (cur < target) {
          cur++;
          setTimeout(step, 150);
        }
      };
      step();
    }, delay);
  }

  if (cntSec) {
    const cntObs = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) {
        targets.forEach((t, i) => animDigit('r' + i, t, i * 200));
        if (tlFill) {
          setTimeout(() => tlFill.classList.add('go'), 500);
        }
        cntObs.unobserve(cntSec);
      }
    }, { threshold: 0.25 });
    cntObs.observe(cntSec);
  }

  /* Progress bars reveal for reseller section */
  const resellerSec = document.querySelector('.reseller');
  if (resellerSec) {
    const progObs = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) {
        setTimeout(() => {
          document.querySelectorAll('.cp-fill').forEach(f => {
            // Re-triggering the transition by setting the width again or just ensuring it's loaded
            const w = f.style.width;
            f.style.width = '0';
            setTimeout(() => { f.style.width = w; }, 10);
          });
        }, 400);
        progObs.unobserve(resellerSec);
      }
    }, { threshold: 0.4 });
    progObs.observe(resellerSec);
  }

  /* Stats Animation */
  document.querySelectorAll('.st').forEach((el, i) => {
    new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) {
        setTimeout(() => el.classList.add('vis'), i * 100);
      }
    }, { threshold: 0.4 }).observe(el);
  });
}

/* Mouse parallax on hero card */
document.addEventListener('mousemove', e => {
  const x = (e.clientX / window.innerWidth - 0.5) * 12;
  const y = (e.clientY / window.innerHeight - 0.5) * 12;
  const card = document.querySelector('.card-inner');
  if (card) {
    card.style.transform = `perspective(1000px) rotateX(${-y * 0.3}deg) rotateY(${x * 0.3}deg)`;
  }
}, { passive: true });
