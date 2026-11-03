// A multi-particle collision simulation in which the particles slowly form "404".
//
// The particles spray out of the center of an empty screen, and stream freely for a
// number of frames, after which the particles in each cell of a grid collide: their
// velocities are rotated around the average velocity of the cell. The cells in the shape of "404" are wells: particles of the matching colour
// that collide in such a cell lose most of their speed, so they pile up there over time.
//
// Particles that leave the screen spray back into it from the center. Slow particles are
// drawn brighter than fast ones, so that the 404 stands out from the particles that are
// still moving around.
(function () {
    // The colours of the website: white, pink and crimson particles on navy (the background
    // comes from the css). A particle of colour i is caught by the wells with number i
    var COLOURS = ['255, 255, 255', '240, 136, 154', '224, 64, 90'];
    var RADIUS = 4;

    // The opacity of a particle depends on its speed, relative to the typical speed
    var BRIGHTNESS = [
        { maxSpeed: 0.3, opacity: 1 },
        { maxSpeed: 0.8, opacity: 0.55 },
        { maxSpeed: Infinity, opacity: 0.25 },
    ];

    var STREAM_STEPS = 25;

    // The screen starts empty: every frame this many particles spray out of the center
    var RELEASED_PER_FRAME = 20;
    var ROTATION = Math.PI * 0.25;

    // The shape of 404 in cells of the grid, starting at cell (4, 4)
    var PATTERN = [
        '#.#.####.#.#',
        '#.#.#..#.#.#',
        '###.#..#.###',
        '..#.#..#...#',
        '..#.#..#...#',
        '..#.####...#',
    ];
    var PATTERN_START = { x: 4, y: 4 };

    var Particle = function (x, y, vx, vy) {
        this.x = x;
        this.y = y;
        this.vx = vx;
        this.vy = vy;
        this.number = Math.floor(Math.random() * COLOURS.length);
    };

    // Particles that leave the simulation come back in its center, from where they spray
    // into the screen again with the same velocity
    Particle.prototype.update = function (bounds) {
        this.x += this.vx;
        this.y += this.vy;

        if (this.x < 0 || this.x >= bounds.x || this.y < 0 || this.y >= bounds.y) {
            this.x = bounds.x / 2;
            this.y = bounds.y / 2;
        }
    };

    var GridCell = function () {
        this.reset();
        this.well = false;
        this.number = null;
    };

    GridCell.prototype.reset = function () {
        this.particles = [];
        this.tvx = 0;
        this.tvy = 0;
    };

    GridCell.prototype.addParticle = function (particle) {
        this.particles.push(particle);
        this.tvx += particle.vx;
        this.tvy += particle.vy;
    };

    GridCell.prototype.applyCollisions = function (cos, sin) {
        var n = this.particles.length;
        if (n === 0) {
            return;
        }

        var avx = this.tvx / n;
        var avy = this.tvy / n;

        if (Math.random() < 0.5) {
            sin = -sin;
        }

        for (var i = 0; i < n; i++) {
            var particle = this.particles[i];
            var dx = particle.vx - avx;
            var dy = particle.vy - avy;

            particle.vx = avx + cos * dx + sin * dy;
            particle.vy = avy - sin * dy + cos * dx;

            if (this.well && particle.number === this.number) {
                particle.vx *= 0.1;
                particle.vy *= 0.1;
            }
        }
    };

    var Grid = function (n, m, width, height) {
        this.n = n;
        this.m = m;
        this.width = width;
        this.height = height;

        this.cells = [];
        for (var i = 0; i < n; i++) {
            this.cells[i] = [];
            for (var j = 0; j < m; j++) {
                this.cells[i][j] = new GridCell();
            }
        }
    };

    Grid.prototype.findCell = function (x, y) {
        var i = Math.min(this.n - 1, Math.floor(x * this.n / this.width));
        var j = Math.min(this.m - 1, Math.floor(y * this.m / this.height));

        return this.cells[i][j];
    };

    Grid.prototype.forEachCell = function (callback) {
        for (var i = 0; i < this.n; i++) {
            for (var j = 0; j < this.m; j++) {
                callback(this.cells[i][j]);
            }
        }
    };

    // All lines of the grid in a single path
    Grid.prototype.draw = function (context) {
        var cellWidth = this.width / this.n;
        var cellHeight = this.height / this.m;

        context.beginPath();
        for (var i = 1; i < this.n; i++) {
            context.moveTo(i * cellWidth, 0);
            context.lineTo(i * cellWidth, this.height);
        }
        for (var j = 1; j < this.m; j++) {
            context.moveTo(0, j * cellHeight);
            context.lineTo(this.width, j * cellHeight);
        }
        context.lineWidth = 1;
        context.strokeStyle = 'rgba(255, 255, 255, 0.05)';
        context.stroke();
    };

    var Simulation = function (canvas, amountOfParticles) {
        this.canvas = canvas;
        this.context = canvas.getContext('2d');
        this.temperature = 50;
        this.bounds = { x: canvas.width, y: canvas.height };
        this.grid = new Grid(20, 14, this.bounds.x, this.bounds.y);
        this.streamSteps = 5;
        this.running = false;
        this.released = 0;

        this.createParticles(amountOfParticles);
        this.placeWellsIn404Pattern();
        this.draw();
    };

    // Random velocities without a net momentum, scaled to the temperature
    Simulation.prototype.createParticles = function (n) {
        var magnitude = 10;
        var tvx = 0;
        var tvy = 0;
        var i;

        this.particles = [];
        for (i = 0; i < n; i++) {
            var vx = Math.random() * (magnitude * 2) - magnitude;
            var vy = Math.random() * (magnitude * 2) - magnitude;
            tvx += vx;
            tvy += vy;

            this.particles[i] = new Particle(
                this.bounds.x / 2,
                this.bounds.y / 2,
                vx,
                vy
            );
        }

        var temp = 0;
        for (i = 0; i < n; i++) {
            this.particles[i].vx -= tvx / n;
            this.particles[i].vy -= tvy / n;
            temp += Math.pow(this.particles[i].vx, 2) + Math.pow(this.particles[i].vy, 2);
        }
        temp = Math.sqrt(this.temperature / (temp / (2 * n)));

        var tempx = temp * this.grid.n / this.grid.width;
        var tempy = temp * this.grid.m / this.grid.height;
        var scale = Math.sqrt(tempx * tempx + tempy * tempy) * 10;

        var squaredSpeed = 0;
        for (i = 0; i < n; i++) {
            this.particles[i].vx *= scale;
            this.particles[i].vy *= scale;
            squaredSpeed += Math.pow(this.particles[i].vx, 2) + Math.pow(this.particles[i].vy, 2);
        }

        // The speed to which the brightness of the particles is compared
        this.typicalSpeed = Math.sqrt(squaredSpeed / n);
    };

    Simulation.prototype.placeWellsIn404Pattern = function () {
        var numbers = { 0: 1, 4: 2, 9: 0 };
        var grid = this.grid;

        PATTERN.forEach(function (row, y) {
            row.split('').forEach(function (character, x) {
                if (character !== '#') {
                    return;
                }

                // The first 4 catches pink, the 0 crimson and the second 4 white particles
                var digitStart = x < 4 ? 0 : x < 9 ? 4 : 9;
                var cell = grid.cells[PATTERN_START.x + x][PATTERN_START.y + y];
                cell.well = true;
                cell.number = numbers[digitStart];
            });
        });
    };

    // The particles are only sorted into cells right before they collide
    Simulation.prototype.stream = function () {
        var sortIntoCells = this.streamSteps === 1;
        this.released = Math.min(this.particles.length, this.released + RELEASED_PER_FRAME);

        for (var i = 0; i < this.released; i++) {
            var particle = this.particles[i];
            particle.update(this.bounds);

            if (sortIntoCells) {
                this.grid.findCell(particle.x, particle.y).addParticle(particle);
            }
        }

        this.streamSteps--;
    };

    Simulation.prototype.collide = function () {
        var cos = Math.cos(ROTATION);
        var sin = Math.sin(ROTATION);

        this.grid.forEachCell(function (cell) {
            cell.applyCollisions(cos, sin);
            cell.reset();
        });

        this.streamSteps = STREAM_STEPS;
    };

    // The particles are drawn in one path per colour and brightness
    Simulation.prototype.draw = function () {
        var context = this.context;
        context.clearRect(0, 0, this.bounds.x, this.bounds.y);
        this.grid.draw(context);

        var batches = BRIGHTNESS.map(function () {
            return COLOURS.map(function () { return []; });
        });

        for (var i = 0; i < this.released; i++) {
            var particle = this.particles[i];
            var speed = Math.sqrt(particle.vx * particle.vx + particle.vy * particle.vy) / this.typicalSpeed;
            var level = 0;
            while (speed > BRIGHTNESS[level].maxSpeed) {
                level++;
            }
            batches[level][particle.number].push(particle);
        }

        // The faint particles first, so that the bright ones are drawn on top
        for (var b = BRIGHTNESS.length - 1; b >= 0; b--) {
            for (var c = 0; c < COLOURS.length; c++) {
                var particles = batches[b][c];
                if (particles.length === 0) {
                    continue;
                }

                context.beginPath();
                for (var p = 0; p < particles.length; p++) {
                    context.moveTo(particles[p].x + RADIUS, particles[p].y);
                    context.arc(particles[p].x, particles[p].y, RADIUS, 0, 2 * Math.PI);
                }
                context.fillStyle = 'rgba(' + COLOURS[c] + ', ' + BRIGHTNESS[b].opacity + ')';
                context.fill();
            }
        }
    };

    Simulation.prototype.update = function () {
        if (!this.running) {
            return;
        }

        if (this.streamSteps > 0) {
            this.stream();
        } else {
            this.collide();
        }
        this.draw();

        window.requestAnimationFrame(this.update.bind(this));
    };

    Simulation.prototype.start = function () {
        if (this.running) {
            return;
        }
        this.running = true;
        window.requestAnimationFrame(this.update.bind(this));
    };

    Simulation.prototype.stop = function () {
        this.running = false;
    };

    var canvas = document.getElementById('mpcCanvas');
    if (!canvas) {
        return;
    }

    var simulation = new Simulation(canvas, 2500);

    // Only simulate while the canvas can be seen
    if ('IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) {
                simulation.start();
            } else {
                simulation.stop();
            }
        }).observe(canvas);
    } else {
        simulation.start();
    }
})();
