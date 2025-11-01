const track = document.getElementById('track');
const slides = Array.from(track.children);
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');
const dotsWrap = document.getElementById('dots');
const slider = document.getElementById('slider');
const DURATION_MS = 10000;

let index = 0;
let timer = null;
let paused = false;

slides.forEach((_, i) => {
  const dot = document.createElement('button');
  dot.className = 'dot';
  dot.addEventListener('click', () => go(i));
  dotsWrap.appendChild(dot);
});

function update() {
  track.style.transform = `translateX(${-index * 100}%)`;
  Array.from(dotsWrap.children).forEach((d, i) =>
    d.setAttribute('aria-current', i === index)
  );
}

function go(i) {
  index = (i + slides.length) % slides.length;
  update();
  restart();
}

function next() { go(index + 1); }
function prev() { go(index - 1); }

function start() {
  clearInterval(timer);
  timer = setInterval(next, DURATION_MS);
}

function stop() {
  clearInterval(timer);
}

function restart() {
  if (!paused) start();
}

nextBtn.onclick = next;
prevBtn.onclick = prev;

slider.addEventListener('mouseenter', () => { paused = true; stop(); });
slider.addEventListener('mouseleave', () => { paused = false; start(); });

update();
start();
