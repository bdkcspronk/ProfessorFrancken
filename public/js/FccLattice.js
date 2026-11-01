// Draws the FCC lattice of the homepage (see the fcc-lattice mixin in home.scss) on a
// canvas in every element with a data-fcc-lattice attribute, so that the atoms can
// shrink when the mouse comes near. Without this script the element keeps its
// background pattern, which looks the same but doesn't react to the mouse
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

        function scale(x, y) {
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

        function frame() {
            current.x += (target.x - current.x) * EASING;
            current.y += (target.y - current.y) * EASING;
            current.strength += (target.strength - current.strength) * EASING;
            draw();

            var settled = Math.abs(target.x - current.x) < 0.5
                && Math.abs(target.y - current.y) < 0.5
                && Math.abs(target.strength - current.strength) < 0.005;

            if (settled) {
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
        window.addEventListener('resize', resize);

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
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-fcc-lattice]').forEach(start);
    });
})();
