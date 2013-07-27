(function($) {
    // An unrelated function used in TimeCircles, can be moved outside private scope
    // placed inside to prevent potential collisions
    function hexToRgb(hex) {
        // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
        var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function(m, r, g, b) {
            return r + r + g + g + b + b;
        });

        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    // Time circle class
    var TC_Class = function(elements, options) {
        
        this.timer = null;
        this.config = $.extend(true, {
            ref_date: new Date(),
            refresh_interval: 0.1,
            count_past_zero: true,
            circle_bg_color: "#60686F",
            use_background: true,
            fg_width: 0.1,
            bg_width: 1.2,
            time: {
                Days: {
                    show: true,
                    text: "Days",
                    color: "#FC6"
                },
                Hours: {
                    show: true,
                    text: "Hours",
                    color: "#9CF"
                },
                Minutes: {
                    show: true,
                    text: "Minutes",
                    color: "#BFB"
                },
                Seconds: {
                    show: true,
                    text: "Seconds",
                    color: "#F99"
                }
            }
        }, options);
        this.target_elements = []; 
        //this.targetContainers = elements;

        // This holds current state data
        var _this = this; // We need access to our class inside inner functions
        elements.each(function() {
            
            var target_element = {
                element: this,
                text_elements: {
                    Days: null,
                    Hours: null,
                    Minutes: null,
                    Seconds: null
                },
                attributes: {
                    canvas: null,
                    context: null,
                    item_size: null,
                    line_width: null,
                    radius: null,
                    outer_radius: null
                },
                state: {
                    fading: {
                        Days: false,
                        Hours: false,
                        Minutes: false,
                        Seconds: false
                    }
                }
            };        
            
            var container = document.createElement('div');
            container.classList.add('time_circles');
            this.appendChild(container);

            target_element.attributes.canvas = document.createElement('canvas');
            target_element.attributes.context = target_element.attributes.canvas.getContext('2d');

            target_element.attributes.canvas.height = container.offsetHeight;
            target_element.attributes.canvas.width = container.offsetWidth;
            container.appendChild(target_element.attributes.canvas);

            target_element.attributes.item_size = Math.min(target_element.attributes.canvas.width / 4, target_element.attributes.canvas.height);
            target_element.attributes.line_width = target_element.attributes.item_size * _this.config.fg_width;
            target_element.attributes.radius = ((target_element.attributes.item_size * 0.8) - target_element.attributes.line_width) / 2;
            target_element.attributes.outer_radius = target_element.attributes.radius + 0.5 * Math.max(target_element.attributes.line_width, target_element.attributes.line_width * _this.config.bg_width);

            // Prepare Time Elements
            var i = 0;
            for (var key in target_element.text_elements) {
                var headerElement = document.createElement('h4');
                headerElement.innerText = _this.config.time[key].text; // Options
                var numberElement = document.createElement('span');

                var textElement = document.createElement('div');
                textElement.className = 'textDiv_' + key;
                textElement.appendChild(headerElement);
                textElement.appendChild(numberElement);
                textElement.style.top = Math.round(0.35 * target_element.attributes.item_size) + 'px';
                textElement.style.left = Math.round(i++ * target_element.attributes.item_size) + 'px';
                textElement.style.width = target_element.attributes.item_size + 'px';
                textElement.style.fontSize = Math.round(0.07 * target_element.attributes.item_size) + 'px';
                textElement.style.lineHeight = Math.round(0.07 * target_element.attributes.item_size) + 'px';
                container.appendChild(textElement);

                target_element.text_elements[key] = numberElement;
            }
            _this.target_elements.push(target_element);
        });

        this.start(this);
    };

    TC_Class.prototype.updateArc = function(obj) {
        var _this = obj;
        var diff, old_diff;

        var interval = (1000 * _this.config.refresh_interval);
        var curDate = new Date();
        
        for(var index in _this.target_elements) {
            var element = _this.target_elements[index];
            
            // Compare current time with reference
            if (_this.config.count_past_zero) {
                var prevDate = curDate - interval;
                diff = Math.abs(curDate - element.attributes.ref_date) / 1000;
                old_diff = Math.abs(element.attributes.ref_date - prevDate) / 1000;
            }
            else {
                diff = Math.max(element.attributes.ref_date - curDate, 0) / 1000;
                old_diff = diff + (curDate > element.attributes.ref_date) ? 0 : interval;
            }

            var time = {
                Days: (diff / 60 / 60 / 24),
                Hours: (diff / 60 / 60) % 24,
                Minutes: (diff / 60) % 60,
                Seconds: diff % 60
            };
            var old_time = {
                Days: (old_diff / 60 / 60 / 24),
                Hours: (old_diff / 60 / 60) % 24,
                Minutes: (old_diff / 60) % 60,
                Seconds: old_diff % 60
            };
            var pct = {
                Days: time.Days / 365,
                Hours: time.Hours / 24,
                Minutes: time.Minutes / 60,
                Seconds: time.Seconds / 60
            };
            
            
            var i = 0;
            var lastKey = null;
            for (var key in time) {
                // Set the text value
                element.text_elements[key].textContent = Math.floor(time[key]);
                
                var x = (i * element.attributes.item_size) + (element.attributes.item_size / 2);
                var y = element.attributes.item_size / 2;
                var color = _this.config.time[key].color;

                // TODO: Check options for fading == true
                if (lastKey !== null) {
                    if (Math.floor(time[lastKey]) > Math.floor(old_time[lastKey])) {
                        _this.radialFade(x, y, color, 1, key, _this, element);
                        element.state.fading[key] = true;
                    }
                    else if (Math.floor(time[lastKey]) < Math.floor(old_time[lastKey])) {
                        _this.radialFade(x, y, color, 0, key, _this, element);
                        element.state.fading[key] = true;
                    }
                }
                if (!element.state.fading[key]) {
                    _this.drawArc(x, y, color, pct[key], element);
                }
                lastKey = key;
                i++;
            }
        }
    };

    TC_Class.prototype.drawArc = function(x, y, color, pct, target_element) {
        var _this = this;
        target_element.attributes.context.clearRect(x - target_element.attributes.outer_radius, y - target_element.attributes.outer_radius, target_element.attributes.outer_radius * 2, target_element.attributes.outer_radius * 2);

        if (_this.config.use_background) {
            target_element.attributes.context.beginPath();
            target_element.attributes.context.arc(x, y, target_element.attributes.radius, 0, 2 * Math.PI, false);
            target_element.attributes.context.lineWidth = target_element.attributes.line_width * _this.config.bg_width;

            // line color
            target_element.attributes.context.strokeStyle = _this.config.circle_bg_color;
            target_element.attributes.context.stroke();
        }

        var startAngle = (-0.5 * Math.PI);
        var endAngle = (-0.5 * Math.PI) + (2 * pct * Math.PI);
        var counterClockwise = false;

        target_element.attributes.context.beginPath();
        target_element.attributes.context.arc(x, y, target_element.attributes.radius, startAngle, endAngle, counterClockwise);
        target_element.attributes.context.lineWidth = target_element.attributes.line_width;

        // line color
        target_element.attributes.context.strokeStyle = color;
        target_element.attributes.context.stroke();
    };

    TC_Class.prototype.radialFade = function(x, y, color, from, key, context, target_element) {
        var _this = context;
        var rgb = hexToRgb(color);

        var to = 1 - from;
        var step = 0.2 * ((from === 1) ? -1 : 1);

        var i;
        for (i = 0; from <= 1 && from >= 0; i++) {
            (function() {
                var rgba = "rgba(" + rgb.r + ", " + rgb.g + ", " + rgb.b + ", " + (Math.round(from * 10) / 10) + ")";
                setTimeout(function() {
                    _this.drawArc(x, y, rgba, 1, target_element);
                }, 50 * i);
            }());
            from += step;
        }
        setTimeout(function() {
            target_element.state.fading[key] = false;
        }, 50 * i);
    };
    
    TC_Class.prototype.timeLeft = function(_this) {
        if(typeof _this === "undefined" || _this === null) _this = this;
        
        // Go through each and check if they're using a time_left attribute
        var arr = [];
        for(var i in this.target_elements) {
            var now = new Date();
            arr.push((this.target_elements[i].attributes.ref_date - now) / 1000);
        }
        return arr;
    }
    
    TC_Class.prototype.start = function(_this) {
        if(typeof _this === "undefined" || _this === null) _this = this;
        
        for(var i in _this.target_elements) {
            var target_element = _this.target_elements[i];
            // Check if a date was passed in html attribute, if not, fall back to config
            var attr_data_date = $(target_element.element).attr('data-date');
            if(typeof attr_data_date === "string") {
                if(attr_data_date.match(/^[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{1,2}:[0-9]{2}:[0-9]{2}$/).length > 0) {
                    attr_data_date = attr_data_date.replace(' ', 'T');
                }
                target_element.attributes.ref_date = Date.parse(attr_data_date);
            }
            else {
                var attr_data_timer = $(target_element.element).attr('data-timer');
                if(typeof attr_data_timer === "string") {
                    target_element.attributes.timer = parseFloat(attr_data_timer);
                    $(target_element.element).removeAttr('data-timer');
                }
                else if(typeof _this.config.timer === "string") {
                    target_element.attributes.timer = parseFloat(_this.config.timer);
                    _this.config.timer = null;
                }
                else if(typeof _this.config.timer === "number") {
                    target_element.attributes.timer = _this.config.timer;
                    _this.config.timer = null;
                }

                if(typeof target_element.attributes.timer === "number") {
                    target_element.attributes.ref_date = (new Date()).getTime() + (target_element.attributes.timer * 1000);
                }
                else {
                    target_element.attributes.ref_date = _this.config.ref_date;
                }
            }
        }
        
        // Start running
        _this.timer = setInterval(function() {
            _this.updateArc(_this);
        }, _this.config.refresh_interval * 1000);
    };

    TC_Class.prototype.stop = function(_this) {
        if(typeof _this === "undefined" || _this === null) _this = this;
        
        // Go through each and check if they're using a time_left attribute
        var time_left = _this.timeLeft(_this);
        for(var i in _this.target_elements) {
            if(typeof _this.target_elements[i].attributes.timer === "number") {
                _this.target_elements[i].attributes.timer = time_left[i];
            }
        }
        // Stop running
        clearInterval(this.timer);
    };

    $.fn.TimeCircles = function(options) {
        return new TC_Class(this, options);
    };
}(jQuery));