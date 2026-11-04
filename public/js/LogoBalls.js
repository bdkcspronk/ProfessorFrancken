// Lets the balls of the logo in the header (resources/views/layout/_logo-balls.blade.php)
// shrink when the mouse comes near, revealing the balls behind them. When the mouse
// is away the balls have their original size, so the logo looks exactly as usual
(function () {
    // Balls within this distance (in logo units, a ball has a radius of about 24)
    // of the mouse shrink, the closest to this part of their size
    var REACH = 70;
    var SMALLEST = 0.45;

    // How quickly the effect follows the mouse and fades in and out (per frame)
    var EASING = 0.2;

    function start(svg) {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        var balls = Array.prototype.map.call(svg.querySelectorAll('.logo-ball'), function (circle) {
            return {
                circle: circle,
                x: parseFloat(circle.getAttribute('cx')),
                y: parseFloat(circle.getAttribute('cy')),
                radius: parseFloat(circle.getAttribute('r'))
            };
        });
        var group = svg.querySelector('.logo-balls').parentNode;

        var target = { x: 0, y: 0, strength: 0 };
        var current = { x: 0, y: 0, strength: 0 };
        var animating = false;

        function draw() {
            balls.forEach(function (ball) {
                var scale = 1;
                if (current.strength > 0.001) {
                    var distance = Math.hypot(ball.x - current.x, ball.y - current.y);
                    if (distance < REACH) {
                        var closeness = 1 - distance / REACH;
                        scale = 1 - current.strength * (1 - SMALLEST) * closeness * closeness;
                    }
                }
                ball.circle.setAttribute('r', (ball.radius * scale).toFixed(3));
            });
        }

        function frame() {
            current.x += (target.x - current.x) * EASING;
            current.y += (target.y - current.y) * EASING;
            current.strength += (target.strength - current.strength) * EASING;
            draw();

            var settled = Math.abs(target.x - current.x) < 0.1
                && Math.abs(target.y - current.y) < 0.1
                && Math.abs(target.strength - current.strength) < 0.005;

            if (settled) {
                current.strength = target.strength;
                draw();
                animating = false;
            } else {
                window.requestAnimationFrame(frame);
            }
        }

        function animate() {
            if (! animating) {
                animating = true;
                window.requestAnimationFrame(frame);
            }
        }

        // The mouse position in the coordinates of the balls
        function toLogo(event) {
            var point = svg.createSVGPoint();
            point.x = event.clientX;
            point.y = event.clientY;
            return point.matrixTransform(group.getScreenCTM().inverse());
        }

        var area = svg.closest('a') || svg;

        area.addEventListener('mousemove', function (event) {
            var point = toLogo(event);
            target.x = point.x;
            target.y = point.y;

            // Start the effect where the mouse enters instead of sliding in from a corner
            if (current.strength < 0.001) {
                current.x = target.x;
                current.y = target.y;
            }
            target.strength = 1;
            animate();
        });

        area.addEventListener('mouseleave', function () {
            target.strength = 0;
            animate();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('svg[data-logo-balls]').forEach(start);
    });
})();
