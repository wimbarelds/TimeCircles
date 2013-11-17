> Unfortunately I am limited in what I can do with these pages. For better formatted documentation, see: http://git.wimbarelds.nl/TimeCircles/readme.php

# TimeCircles documentation
TimeCircles is a jQuery plugin that provides a nice looking way to either count down towards a certain time, or to count up from a certain time. The goal for TimeCircles is to provide a simple yet dynamic tool that makes it very easy to provide visitors an attractive countdown or timer.

This documentation will provide some examples of how to use TimeCircles. Usage of TimeCircles can be very simple, but for those willing to work a little harder can also provide more sophisticated functionality. The examples aim to provide a good basic idea of how various features can be used without overcomplicating things.

## General use

The first thing to do is to include the javascript files for jQuery and TimeCircles, as well as the TimeCircles stylesheet. These should ideally be included in the head of your html file.

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="TimeCircles.js"></script>
    <link href="TimeCircles.css" rel="stylesheet">

When the neccesary files have been included, it's very simple to set up TimeCircles on your page, simply target the element you wish to use with jQuery, and execute the TimeCircles function on it. This will create TimeCircles inside the targeted element, counting up from 0 (when the page was opened).

> **Important note:** TimeCircles will automatically make it self the size of whatever element you place it in. If you do not have a height set, it will attempt to determine a height based on the element's width. For the best results however, it's recommended to set both the width and height.

### Setting your reference time

Of course, you might not want to start counting up from 0. Perhaps you're counting down the time until a wedding, or alternatively counting how long you've been with your girlfriend (or whatever else really). Really, TimeCircles is most useful if you're using it with some reference time and/or date.

Setting up your reference date and time is also fairly simple. The best way to do it is to simply include it in the html element you've set aside for TimeCircles. Create an attribute called data-date and provide a value in the format of `yyyy-mm-dd hh:mm:ss`

    <div class="example" data-date="2014-01-01 00:00:00"></div>

### Creating a stopwatch

It is also possible that you want to use TimeCircles to count down a specific amount of time, like 15 minutes. This works similarly as creating a reference time, however here the attribute data-timer is used, and the value is the time to count down from (in seconds).

    <div class="example" data-timer="900"></div>

## Options

It's nice that TimeCircles comes in yellow, green, blue, and red- but wouldn't it be even nicer if it came in the color theme of your own website? Alternatively, wouldn't it be great if you could change other aspects of the way it looks? Perhaps change the language of the text to whatever you want?

To customize TimeCircles to fit precisely what you're looking for you can use the options. In this section we will look into what options are available and what each option means.

### start `(default: true)`

This option determines whether or not TimeCircles should start immediately. If for example you wish to create a stopwatch that starts when the users clicks a button, you'll want to set this to false.

    $(".example").TimeCircles({start: false}); 

### refresh_interval `(default: 0.1)`

This option determines how frequently TimeCircles is updated. The value is expressed in seconds, so 0.1 means one tenth of a second.

    $(".example").TimeCircles({refresh_interval: 1});

### count_past_zero `(default: true)`

This option is only really useful for when counting down. What it does is either give you the option to stop the timer, or start counting up after you've hit the predefined date (or your stopwatch hits zero).

    $(".example").TimeCircles({count_past_zero: false}); 

### circle_bg_color `(default: "#60686F")`

This option determines the color of the background circle.

    $(".example").TimeCircles({circle_bg_color: "#000000"}); 

### use_background `(default: true)`

This options sets whether any background circle should be drawn at all. Disabling this option could be used in isolation, or you could use a background of your own to place behind TimeCircles.

    $(".example").TimeCircles({use_background: false}); 

### fg_width `(default: 0.1)`

This option sets the width of the foreground circle. The width is set relative to the size of the circle as a whole. A value of 0.1 means 10%, so if your TimeCircles are 100 pixels high, the foreground circle will be 10 percent of that (10 pixels).

    $(".example").TimeCircles({fg_width: 0.05});

### bg_width `(default: 1.2)`

This option sets the width of the backgroundground circle. The width of the background is set relative to the width of the foreground. A value of 1 means 100%, so a value of 1 would mean having a width equal to your foreground ring. Higher and you get wider, lower you get thinner.

    $(".example").TimeCircles({bg_width: 0.5}); 

### time

The time option is actually a group of options that allows you to control the options of each time unit independently. As such, within time each unit of time has its own sub-category. These categories are: Days, Hours, Minutes, and Seconds. The options available within each category are as follows:

* **show:** Determines whether the time unit should be shown at all
* **text:** Determines the text shown below the time. Useful for use on non-English websites
* **color:** Determines the color of the foreground circle of the time unit

    $(".example").TimeCircles({ time: {
        Days: { color: "#C0C8CF" },
        Hours: { color: "#C0C8CF" },
        Minutes: { color: "#C0C8CF" },
        Seconds: { color: "#C0C8CF" }
    }});

## Functions

Functions will allow you to interact with your TimeCircles as they're running. Generally speaking, this functionality is most often used by other developers who want their own javascript to interact with TimeCircles. However, if you're not a developer yourself, there are still a few functions that are quite simple to use and shouldn't be too hard to tackle.

Before we go into what each function does however, it should be pointed out how these functions can be used. Unlike quite a lot of other jQuery plugins, TimeCircles does not return a jQuery object after instantiating. Instead, it returns a TimeCircles object. This means that function chaining will work slightly differently than it does for other jQuery plugins. To find out more about how to chain other jQuery plugins and functions, have a look at the `end()` function.

TimeCircles functions themselves (with the exception of the `end()` function) will return the TimeCircles object. This allows you to chain several functions into each other. IE: You could chain `start()` straight into `addEventListener(callback)`.

### start() and stop()

These are the most basic functions provided. They allow you to temporarily stop TimeCircles. This is especially useful when you're using TimeCircles as a sort of stopwatch (ie: counting down a certain number of seconds). If you're using TimeCircles to count down to a certain point in the future, obviously pausing TimeCircles isn't going to stop time itself.

**Html**

    <div class="example stopwatch" data-timer="900"></div>
    <button class="btn btn-success start">Start</button>
    <button class="btn btn-success start">Stop</button>

**Javascript**

    $(".example").TimeCircles();
    $(".stop").click(function(){ $(".example.stopwatch").TimeCircles().stop(); });
    $(".start").click(function(){ $(".example.stopwatch").TimeCircles().start(); });

### destroy()

If for some reason, you need to get rid of your TimeCircles, or you want to allow users remove them at the click of a button; you can do that with destroy.

    $(".example").TimeCircles().destroy();

### addListener `(callback)`

The most powerful interactions with TimeCircles can be achieved using listeners. Using listeners, you can make a ticking sound play every second, or you can make a sound whenever a minute passes. You could even use it to trigger some alarm or whole other javascript when the timer hits zero.

To add a listener, use the `addEventListener(callback)` function. Callback is a function you pass to the event listener. The callback will then be triggered for each event. Three parameters are passed to your callback function, namely:

* **unit:** The time unit in string format. So, "Days"/"Hours"/"Minutes"/"Seconds".
* **value:** The new value of the time unit that changed. I.e.: 15.
* **total:** This is the total time left (or elapsed) since the zero point.

### end()

To allow you to chain TimeCircles to other jQuery functions, you can use the end() function. The end function returns the jQuery object and allows you to trigger jQuery function as desired.

    $(".example").TimeCircles().end().fadeOut(); 
