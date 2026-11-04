// Draws the FCC lattice of the homepage (see the fcc-lattice mixin in home.scss) on a
// canvas in every element with a data-fcc-lattice attribute, so that the atoms can
// shrink when the mouse comes near, and so that a click sends a wave through the atoms.
// Without this script the element keeps its background pattern, which looks the same but
// doesn't react to the mouse
(function () {
    // The same sizes as the background pattern: tiles of 96px with atoms that touch
    // along the diagonal of each face
    var TILE = 96;
    var RADIUS = TILE * Math.SQRT2 / 4;
    var LINE_WIDTH = 1.5;

    // Atoms within this distance of the mouse shrink, the closest to this size
    var REACH = 320;
    var SMALLEST = 0.6;

    // How quickly the effect follows the mouse and fades in and out (per frame)
    var EASING = 0.15;

    // A click sends a ring outwards in which the atoms shrink and grow back: it moves this
    // many pixels per second, is this wide, shrinks the atoms at most to 1 - WAVE_DEPTH and
    // dies out after WAVE_DURATION milliseconds
    var WAVE_SPEED = 700;
    var WAVE_WIDTH = 70;
    var WAVE_DEPTH = 0.55;
    var WAVE_DURATION = 1600;

    // Clicks on these do something else, so they don't make a wave
    var INTERACTIVE = 'a, button, input, select, textarea, label, summary, [role="button"]';

    function start(element) {
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var canvas = document.createElement('canvas');
        canvas.className = 'home-lattice';
        canvas.setAttribute('aria-hidden', 'true');
        element.insertBefore(canvas, element.firstChild);
        element.classList.add('has-lattice');

        var context = canvas.getContext('2d');
        var style = window.getComputedStyle(canvas);
        var colors = {
            center: style.getPropertyValue('--lattice-center').trim(),
            corner: style.getPropertyValue('--lattice-corner').trim(),
            edge: style.getPropertyValue('--lattice-edge').trim()
        };

        var width = 0;
        var height = 0;

        // Where the mouse is (target) and where the effect currently is (eased)
        var target = { x: 0, y: 0, strength: 0 };
        var current = { x: 0, y: 0, strength: 0 };
        var animating = false;

        // The waves of clicks: where they started and when; now is the time of the frame
        var waves = [];
        var now = 0;

        function mouseScale(x, y) {
            if (current.strength < 0.001) {
                return 1;
            }
            var distance = Math.hypot(x - current.x, y - current.y);
            if (distance >= REACH) {
                return 1;
            }
            var closeness = 1 - distance / REACH;
            return 1 - current.strength * (1 - SMALLEST) * closeness * closeness;
        }

        // An atom shrinks while the ring of a wave passes it, less as the wave dies out
        function waveScale(x, y) {
            var result = 1;
            for (var i = 0; i < waves.length; i++) {
                var age = now - waves[i].start;
                var radius = WAVE_SPEED * age / 1000;
                var offset = (Math.hypot(x - waves[i].x, y - waves[i].y) - radius) / WAVE_WIDTH;
                if (offset > -2.5 && offset < 2.5) {
                    var fade = 1 - age / WAVE_DURATION;
                    result *= 1 - WAVE_DEPTH * fade * Math.exp(-offset * offset);
                }
            }
            return result;
        }

        function scale(x, y) {
            return mouseScale(x, y) * waveScale(x, y);
        }

        function draw() {
            context.clearRect(0, 0, width, height);
            context.lineWidth = LINE_WIDTH;

            // Line up with the background pattern, which is positioned at center top
            var offsetX = (((width - TILE) / 2) % TILE + TILE) % TILE;
            var columns = Math.ceil(width / TILE) + 1;
            var rows = Math.ceil(height / TILE) + 1;

            context.strokeStyle = colors.edge;
            context.beginPath();
            for (var column = -1; column <= columns; column++) {
                var lineX = offsetX + column * TILE;
                context.moveTo(lineX, 0);
                context.lineTo(lineX, height);
            }
            for (var row = 0; row <= rows; row++) {
                context.moveTo(0, row * TILE);
                context.lineTo(width, row * TILE);
            }
            context.stroke();

            for (var i = -1; i <= columns; i++) {
                for (var j = 0; j <= rows; j++) {
                    var x = offsetX + i * TILE;
                    var y = j * TILE;
                    atom(x, y, colors.corner);
                    atom(x + TILE / 2, y + TILE / 2, colors.center);
                }
            }
        }

        function atom(x, y, color) {
            context.beginPath();
            context.arc(x, y, RADIUS * scale(x, y), 0, 2 * Math.PI);
            context.strokeStyle = color;
            context.stroke();
        }

        function frame(time) {
            now = time;
            waves = waves.filter(function (wave) {
                return now - wave.start < WAVE_DURATION;
            });

            current.x += (target.x - current.x) * EASING;
            current.y += (target.y - current.y) * EASING;
            current.strength += (target.strength - current.strength) * EASING;
            draw();

            var settled = Math.abs(target.x - current.x) < 0.5
                && Math.abs(target.y - current.y) < 0.5
                && Math.abs(target.strength - current.strength) < 0.005;

            if (settled && waves.length === 0) {
                current.strength = target.strength;
                draw();
                animating = false;
            } else {
                window.requestAnimationFrame(frame);
            }
        }

        // Only draw while something changes
        function animate() {
            if (! animating) {
                animating = true;
                window.requestAnimationFrame(frame);
            }
        }

        function resize() {
            var ratio = window.devicePixelRatio || 1;
            width = element.clientWidth;
            height = element.clientHeight;
            canvas.width = width * ratio;
            canvas.height = height * ratio;
            context.setTransform(ratio, 0, 0, ratio, 0, 0);
            draw();
        }

        resize();
        // The element can change size without the window doing so, like the menu, which
        // gets wider when a section is opened
        if ('ResizeObserver' in window) {
            new ResizeObserver(resize).observe(element);
        } else {
            window.addEventListener('resize', resize);
        }

        if (reduceMotion) {
            return;
        }

        element.addEventListener('mousemove', function (event) {
            var bounds = canvas.getBoundingClientRect();
            target.x = event.clientX - bounds.left;
            target.y = event.clientY - bounds.top;

            // Start the effect where the mouse enters instead of sliding in from a corner
            if (current.strength < 0.001) {
                current.x = target.x;
                current.y = target.y;
            }
            target.strength = 1;
            animate();
        });

        element.addEventListener('mouseleave', function () {
            target.strength = 0;
            animate();
        });

        element.addEventListener('click', function (event) {
            if (event.target.closest(INTERACTIVE)) {
                return;
            }
            var bounds = canvas.getBoundingClientRect();
            waves.push({
                x: event.clientX - bounds.left,
                y: event.clientY - bounds.top,
                start: performance.now()
            });
            animate();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-fcc-lattice]').forEach(start);
    });
})();
