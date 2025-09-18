(function($) {
    // "Circles" canvas animation jQuery plugin
    // based on http://tympanus.net/Development/AnimatedHeaderBackgrounds/index2.html
    $.fn.thumbsAnimate = function(options) {

        if (!this.length) { return this; }

        var opts = $.extend(true, {}, $.fn.thumbsAnimate.defaults, options);

        this.each(function(index) {
            var $this = $(this);

            if ($this.data('thumbsAnim')) return true;
            $this.data('thumbsAnim', '1');
            
            var width, height, largeHeader, canvas, ctx, circles, target, animateNow = true;
            var $wrapper = $this.wrap('<div class="anim-wrapper" style="position: relative; float: left;"></div>').parent();
            var $canvas = $('<canvas class="thumbsanim" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;"></canvas>').insertAfter($this).attr('width', $wrapper.width()).attr('height', $wrapper.height());

            width = $canvas.width();
            height = $canvas.height();
            target = {x: 0, y: height};
            ctx = $canvas[0].getContext('2d');

            var animate = function() {
                if(animateNow) {
                    ctx.clearRect(0,0,width,height);
                    for(var i in circles) {
                        circles[i].draw();
                    }
                }
                requestAnimationFrame(animate);
            };

            var Circle = function() {
                var _this = this;
                var dir = opts.direction;
                if (dir == 'both' && Math.random() > 0.5) dir = 'up';
                // constructor
                (function() {
                    _this.pos = {};
                    init();
                    //console.log(_this);
                })();

                function init() {
                    _this.pos.x = Math.random()*width;
                    if (dir == 'up')
                        _this.pos.y = height+Math.random()*100;
                    else
                        _this.pos.y = -Math.random()*100;
                    _this.alpha = 0.1+Math.random()*parseFloat(opts.alpha);
                    _this.scale = 0.1+Math.random()*parseFloat(opts.size);
                    _this.velocity = Math.random()*parseFloat(opts.speed);
                }

                this.draw = function() {

                    if(_this.alpha <= 0 || _this.scale == 0 || (dir == 'up' && _this.pos.y < 0) || (dir == 'down' && _this.pos.y > height)) {
                        init();
                    }
                    if (dir == 'up')
                        _this.pos.y -= _this.velocity;
                    else
                        _this.pos.y += _this.velocity;

                    _this.alpha -= parseFloat(opts.fading);
                    _this.scale = Math.max(0, _this.scale - parseFloat(opts.scaling));
                    ctx.beginPath();
                    ctx.arc(_this.pos.x, _this.pos.y, _this.scale*10, 0, 2 * Math.PI, false);
                    ctx.fillStyle = 'rgba('+opts.color+','+ _this.alpha+')';
                    ctx.fill();
                };
            };

            // create particles
            circles = [];
            for(var x = 0; x < width*parseFloat(opts.density); x++) {
                var c = new Circle();
                circles.push(c);
            }
            animate();

            // scroll & resize listeners
            $(window).scroll(function(event) {
                if ($this.isOnScreen()) {
                    //if (!animateNow) console.log('#'+index+' started');
                    animateNow = true;
                } else {
                    //if (animateNow) console.log('#'+index+' stopped');
                    animateNow = false;
                }
            }).resize(function(event) {
                width = $canvas.width();
                height = $canvas.height();
            });

        });

        return this;
    };

    // default options
    $.fn.thumbsAnimate.defaults = {
      color: '255,255,255', // stick to this format
      alpha: '0.3',
      size: '0.3',          
      speed: '1',           
      fading: '0.0005',     // fade out per frame
      scaling: '0',         // scale down per frame
      density: '0.5',       // number of circles = width * density
      direction: 'up'
    };

    if (typeof $.fn.isOnScreen === 'undefined') {
        $.fn.isOnScreen = function(){
        
            var win = $(window);
            
            var viewport = {
                top : win.scrollTop(),
                left : win.scrollLeft()
            };
            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();
            
            var bounds = this.offset();
            bounds.right = bounds.left + this.outerWidth();
            bounds.bottom = bounds.top + this.outerHeight();
            
            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
            
        };
    }

    // Init plugin
    var opts = {
        speed: 1,
        density: 0.4,
        alpha: 0.3,
        size: 0.3,
        fading: 0.001,  // fade out per frame
        direction: 'up' // up/down/both
    };
    $(window).load(function() {
        $('.wp-post-image').thumbsAnimate(opts);
    });

    // Apply to elements loaded via AJAX
    $( document ).ajaxComplete(function(event, xhr, settings) {
        $('.more-page').imagesLoaded(function() { $('.wp-post-image').thumbsAnimate(opts); });
    });

})(jQuery);
