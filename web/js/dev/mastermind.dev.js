function mastermind() {

    var game = {

        canvas: null,

        attempts: [],

        combos: [],

        scores: [],

        pins: [],

        combination: [],

        token: null,

        progressApiUrl: null,

        setupPins: function() {

            var i, pins;

            pins = ['black', 'blue', 'brown', 'green', 'yellow', 'orange', 'red', 'white'];
            game.pins = [];

            for (i = 0; i < pins.length; i++) {
                //game.drawPin(pins[i], 20 + (i * 35), 280, 10);
                var img = new Image();

                img.src = '/game/image/mastermind/pin-' + pins[i] + '.png';
                img.dimensions = {x: 20 + (i * 50), y: game.canvas.height -50, color: pins[i]};
                img.onload = function () {
                    game.pins.push(this.dimensions);
                    game.canvas.getContext('2d').drawImage(
                        this,
                        this.dimensions.x,
                        this.dimensions.y
                    );
                };

            }
        },

        drawNewCombo: function(offset) {

            var height = game.canvas.height - 150 - (offset * 80);
            var width = game.canvas.width - 100;


            // draw the pinholes
            var img = new Image();
            img.src = '/game/image/mastermind/pin-holes.png';
            img.dimensions = {
                x: 20,
                y: game.canvas.height - 150 - (offset * 80)
            };

            img.onload = function () {
                game.canvas.getContext('2d').drawImage(
                    this,
                    this.dimensions.x,
                    this.dimensions.y
                );
            };

            // draw the score pinholes
            var img = new Image();
            img.src = '/game/image/mastermind/score-pins.png';
            img.dimensions = {
                x: 340,
                y: game.canvas.height - 145 - (offset * 80)
            };

            img.scores = [
                {x: game.canvas.width -90, y: game.canvas.height - 140 - (offset * 80)},
                {x: game.canvas.width -60, y: game.canvas.height - 140 - (offset * 80)},
                {x: game.canvas.width -90, y: game.canvas.height - 114 - (offset * 80)},
                {x: game.canvas.width -60, y: game.canvas.height - 114 - (offset * 80)},
            ];

            img.onload = function () {
                game.canvas.getContext('2d').drawImage(
                    this,
                    this.dimensions.x,
                    this.dimensions.y
                );
            };

            game.scores.push(img.scores);
        },

        clearCanvas: function(x, y, w, h) {
            var context = game.canvas.getContext('2d');
            context.clearRect(x, y, w, h);
        },

        drawScorePins: function(score) {
            var c = 0;
            for (var i = 0; i < score.length; i++) {
                if (typeof score[i] !== 'undefined') {
                    var img = new Image();
                    img.src = '/game/image/mastermind/pin-score-' + score[i] + '.png';
                    img.dimensions = {
                        x: game.scores[game.attempts.length][c].x,
                        y: game.scores[game.attempts.length][c].y
                    };
                    img.onload = function () {
                        game.canvas.getContext('2d').drawImage(
                            this,
                            this.dimensions.x,
                            this.dimensions.y
                        );
                    };

                    c++;
                }
            }
        },

        addComboPin: function(color) {

            var img = new Image();
            var attempts = game.attempts.length;

            if (game.attempts.length >= 4) {
                attempts = 4;
            }

            img.src = '/game/image/mastermind/pin-' + color + '.png';
            img.dimensions = {
                x: 32 + (game.combination.length * 61),
                y: game.canvas.height - 140 - (attempts * 80)
            };
            img.onload = function () {
                game.pins.push(this.dimensions);
                game.canvas.getContext('2d').drawImage(
                    this,
                    this.dimensions.x,
                    this.dimensions.y
                );
            };

            game.combination.push(color);

            if (game.combination.length === 4) {
                game.verify();
            }
        },

        verify: function() {

            var score;
            score = [];

            var data = {
                solution: game.combination,
                token: game.token
            };

            $.ajax({
                url: game.progressApiUrl,
                type: 'POST',
                data: JSON.stringify(data),
                success: function(response, textStatus, xhr) {

                    if(xhr.status == 200) {

                        if (response.completed === true) {
                            game.gameEnd(response);
                            return;
                        }
                        game.drawScorePins(response.progress);
                        game.attempts.push(game.combination);
                        game.combination = [];

                        setTimeout(function() {
                            if (game.attempts.length >= 5) {
                                localStorage.setItem('tempMmImg', game.canvas.toDataURL());
                                game.redraw();
                            }
                        }, 100);
                    }
                }
            });
        },

        gameEnd: function(response) {

            game.canvas.removeEventListener('click', game.canvasClickEvent);
            document.getElementById('timePlayed').innerHTML = response.data.timePlayed;

            if (response.data.topTen !== false) {
                document.getElementById('topTenPos').innerHTML = response.data.topTen;
                document.getElementById('topTen').style.display = 'block';
            }

            $('#gameModal').modal('show');
        },

        redraw: function() {
            var dataURL = localStorage.getItem('tempMmImg');
            var img = new Image();
            img.src = dataURL;
            img.onload = function () {
                game.clearCanvas(0, 0, game.canvas.width, game.canvas.height);
                game.canvas.getContext('2d').drawImage(img, 0, 80);
                game.clearCanvas(0, game.canvas.height - 80, game.canvas.width, 80);
                game.drawNewCombo(4);
                game.setupPins();
            };
        },

        canvasClickEvent: function(e) {

            var rect = game.canvas.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;

            game.pins.forEach(function(pin) {
                if (y > (pin.y -10) && y < (pin.y + 50)
                    && x > (pin.x -10) && x < pin.x + 50) {
                    game.addComboPin(pin.color);
                }
            });
        }
    };

    this.public = {

        init: function(config) {

            if (document.getElementById('mastermind')) {
                var p = document.getElementById('mastermind').parentNode;
                p.removeChild(document.getElementById('mastermind'));
            }

            var canvas = document.createElement('CANVAS');
            canvas.id = 'mastermind';
            canvas.style.backgroundColor = '#ac613b';
            canvas.style.border = 'border:solid 1px #ccc';

            game.attempts = [];
            game.combination = [];

            document.getElementById('canvasContainer').appendChild(canvas);

            game.canvas = document.getElementById('mastermind');
            game.canvas.width = config.canvasWidth;
            game.canvas.height = config.canvasHeight;
            game.progressApiUrl = config.progressApiUrl;
            game.token = config.token;

            game.setupPins();

            for (var i = 0; i < 5; i++) {
                game.drawNewCombo(i);
            }

            game.canvas.addEventListener('click', game.canvasClickEvent, false);
        }
    }

    return this.public;

}

var Mastermind = new mastermind();
