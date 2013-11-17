<?php

    function highlightJS($code, $removeBreaks = false) {
        $str = highlight_string("<?php ".$code." ?>", true);
        $str = substr($str, strpos($str, 'php') + 9);
        $str = substr($str, 0, strrpos($str, '?'));
        if($removeBreaks) {
            $str = str_replace(array('<br>', '<br />'), '', $str);
        }
        return '<small class="codeType">Javascript</small>'.$str;
    }

    function highlightHTML($s) {
        // This xml highlight function was taken from "Dmitry S" in the comments on http://www.php.net/highlight_string
        $s = preg_replace("|<([^/?]*)\s(.*)>|isU", "[1]<[2]\\1[/2] [5]\\2[/5]>[/1]", $s);
        $s = preg_replace("|</(.*)>|isU", "[1]</[2]\\1[/2]>[/1]", $s);
        $s = preg_replace("|<\?(.*)\?>|isU", "[3]<?\\1?>[/3]", $s);
        $s = preg_replace("|\=\"(.*)\"|isU", "[6]=[/6][4]\"\\1\"[/4]", $s);
        $s = htmlspecialchars($s);

        $replace = array(1 => '0000FF', 2 => '0000FF', 3 => '800000', 4 => '800080', 5 => '009900', 6 => '0000FF');
        foreach($replace as $k => $v){
            $s = preg_replace("|\[".$k."\](.*)\[/".$k."\]|isU", "<font color=\"".$v."\">\\1</font>", $s);
        }
        return '<small class="codeType">HTML</small>'.$s;
    }
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>TimeCircles Readme / documentation</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
        <link href="inc/TimeCircles.css" rel="stylesheet">
        <link href="inc/readme.css" rel="stylesheet">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="inc/TimeCircles.js"></script>
        <script type="text/javascript">
            $(function() {
                $(".example").TimeCircles();
                
                $(".stop").click(function(){
                    $(".example.stopwatch").TimeCircles().stop();
                });
                $(".start").click(function(){
                    $(".example.stopwatch").TimeCircles().start();
                });
                $('body').scrollspy({ target: '#sideNav' });
            });
        </script>
    </head>
    <body data-spy="scroll" data-target="#sideNav">
        <div class="container">
            <div class="row show-grid" style="padding-top: 70px;">
                <div class="col-lg-3">
                    <div class="bs-sidebar affix" id="sideNav" style="width: 270px;">
                        <ul class="nav bs-sidenav" style="padding-top: 10px;padding-bottom: 10px;text-shadow: 0 1px 0 #fff;background-color: #f7f5fa;border-radius: 5px;">
                            <li><a href="#Documentation">TimeCircles documentation</a></li>
                            <li><a href="#GeneralUse">General use</a></li>
                            <li><a href="#ReferenceTime">Setting yoru reference time</a></li>
                            <li><a href="#StopWatch">Creating a stopwatch</a></li>
                            <li>
                                <a href="#Options">Options</a>
                                <ul>
                                    <li><a href="#opt_start">Start</a></li>
                                    <li><a href="#opt_refresh_interval">Refresh interval</a></li>
                                    <li><a href="#opt_count_past_zero">Count past zero</a></li>
                                    <li><a href="#opt_circle_bg_color">Background color</a></li>
                                    <li><a href="#opt_use_background">Use background</a></li>
                                    <li><a href="#opt_fg_width">Foreground width</a></li>
                                    <li><a href="#opt_bg_width">Background width</a></li>
                                    <li><a href="#opt_time">Time</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#Functions">Functions</a>
                                <ul>
                                    <li><a href="#func_start_stop">Start and stop</a></li>
                                    <li><a href="#func_destroy">Destroy</a></li>
                                    <li><a href="#func_addListener">Event Listeners</a></li>
                                    <li><a href="#func_end">End</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <h1 id="Documentation">TimeCircles documentation</h1>
                    <p>
                        TimeCircles is a jQuery plugin that provides a nice looking way to either count down towards a certain time, or to count up from a certain time.
                        The goal for TimeCircles is to provide a simple yet dynamic tool that makes it very easy to provide visitors an attractive countdown or timer.
                    </p>
                    <p>
                        This documentation will provide some examples of how to use TimeCircles.
                        Usage of TimeCircles can be very simple, but for those willing to work a little harder can also provide more sophisticated functionality.
                        The examples aim to provide a good basic idea of how various features can be used without overcomplicating things.
                    </p>
                    <h2 id="GeneralUse">General use</h2>
                    <p>
                        The first thing to do is to include the javascript files for jQuery and TimeCircles, as well as the TimeCircles stylesheet.
                        These should ideally be included in the head of your html file.
                    </p>
                    <pre>
<?php echo highlightHTML('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="TimeCircles.js"></script>
<link href="TimeCircles.css" rel="stylesheet">');?>
                    </pre>
                    <p>
                        When the neccesary files have been included, it's very simple to set up TimeCircles on your page, simply target the element you wish to use with jQuery,
                        and execute the TimeCircles function on it. This will create TimeCircles inside the targeted element, counting up from 0 (when the page was opened)
                    </p>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="example"></div>
                        </div>
                        <div class="panel-footer code"><?php echo highlightJS('$(".example").TimeCircles();'); ?></div>
                    </div>
                    <div class="alert alert-danger small-text">
                        <small>
                            <strong>Important note:</strong>
                            TimeCircles will automatically make it self the size of whatever element you place it in.
                            If you do not have a height set, it will attempt to determine a height based on the element's width.
                            For the best results however, it's recommended to set both the width and height.
                        </small>
                    </div>
                    <h3 id="ReferenceTime">Setting your reference time</h3>
                    <p>
                        Of course, you might not want to start counting up from 0. Perhaps you're counting down the time until a wedding,
                        or alternatively counting how long you've been with your girlfriend (or whatever else really).
                        Really, TimeCircles is most useful if you're using it with some reference time and/or date.
                    </p>
                    <p>
                        Setting up your reference date and time is also fairly simple.
                        The best way to do it is to simply include it in the html element you've set aside for TimeCircles.
                        Create an attribute called <code>data-date</code> and provide a value in the format of <code>yyyy-mm-dd hh:mm:ss</code>
                    </p>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="example" data-date="2014-01-01 00:00:00"></div>
                        </div>
                        <div class="panel-footer code"><?php echo highlightHTML('<div class="example" data-date="2014-01-01 00:00:00"></div>'); ?></div>
                    </div>                    
                    <h3 id="StopWatch">Creating a stopwatch</h3>
                    <p>
                        It is also possible that you want to use TimeCircles to count down a specific amount of time, like 15 minutes.
                        This works similarly as creating a reference time, however here the attribute <code>data-timer</code> is used,
                        and the value is the time to count down from (<em>in seconds</em>).
                    </p>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="example" data-timer="900"></div>
                        </div>
                        <div class="panel-footer code"><?php echo highlightHTML('<div class="example" data-timer="900"></div>'); ?></div>
                    </div>                    
                    <h2 id="Options">Options</h2>
                    <p>
                        It's nice that TimeCircles comes in yellow, green, blue, and red- but wouldn't it be even nicer if it came in the color theme of your own website?
                        Alternatively, wouldn't it be great if you could change other aspects of the way it looks? Perhaps change the language of the text to whatever you want?
                    </p>
                    <p>
                        To customize TimeCircles to fit precisely what you're looking for you can use the options. In this section we will look into what options are available and what each option means.
                    </p>
                    <h3 id="opt_start">start <small><code>(default: true)</code></small></h3>
                    <p>
                        This option determines whether or not TimeCircles should start immediately.
                        If for example you wish to create a stopwatch that starts when the users clicks a button, you'll want to set this to false.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({start: false});'); ?></pre>
                    <h3 id="opt_refresh_interval">refresh_interval <small><code>(default: 0.1)</code></small></h3>
                    <p>
                        This option determines how frequently TimeCircles is updated. The value is expressed in seconds, so 0.1 means one tenth of a second.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({refresh_interval: 1});'); ?></pre>
                    <h3 id="opt_count_past_zero">count_past_zero <small><code>(default: true)</code></small></h3>
                    <p>
                        This option is only really useful for when counting down.
                        What it does is either give you the option to stop the timer,
                        or start counting up after you've hit the predefined date (or your stopwatch hits zero).
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({count_past_zero: false});'); ?></pre>
                    <h3 id="opt_circle_bg_color">circle_bg_color <small><code>(default: &quot;#60686F&quot;)</code></small></h3>
                    <p>
                        This option determines the color of the background circle.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({circle_bg_color: "#000000"});'); ?></pre>
                    <h3 id="opt_use_background">use_background <small><code>(default: true)</code></small></h3>
                    <p>
                        This options sets whether any background circle should be drawn at all.
                        Disabling this option could be used in isolation, or you could use a background of your own to place behind TimeCircles.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({use_background: false});'); ?></pre>
                    <h3 id="opt_fg_width">fg_width <small><code>(default: 0.1)</code></small></h3>
                    <p>
                        This option sets the width of the foreground circle. The width is set relative to the size of the circle as a whole.
                        A value of 0.1 means 10%, so if your TimeCircles are 100 pixels high, the foreground circle will be 10 percent of that (10 pixels).
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({fg_width: 0.05});'); ?></pre>
                    <h3 id="opt_bg_width">bg_width <small><code>(default: 1.2)</code></small></h3>
                    <p>
                        This option sets the width of the backgroundground circle. The width of the background is set relative to the width of the foreground.
                        A value of 1 means 100%, so a value of 1 would mean having a width equal to your foreground ring. Higher and you get wider, lower you get thinner.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({bg_width: 0.5});'); ?></pre>
                    <h3 id="opt_time">time</h3>
                    <p>
                        The time option is actually a group of options that allows you to control the options of each time unit independently.
                        As such, within time each unit of time has its own sub-category. These categories are: Days, Hours, Minutes, and Seconds.
                        The options available within each category are as follows:
                    </p>
                    <ul>
                        <li><strong>show</strong>: Determines whether the time unit should be shown at all</li>
                        <li><strong>text</strong>: Determines the text shown below the time. Useful for use on non-English websites</li>
                        <li><strong>color</strong>: Determines the color of the foreground circle of the time unit</li>
                    </ul>
                    <pre><?php echo highlightJS('$(".example").TimeCircles({ time: {
    Days: { color: "#C0C8CF" },
    Hours: { color: "#C0C8CF" },
    Minutes: { color: "#C0C8CF" },
    Seconds: { color: "#C0C8CF" }
}});', true); ?></pre>
                    <h2 id="Functions">Functions</h2>
                    <p>
                        Functions will allow you to interact with your TimeCircles as they're running.
                        Generally speaking, this functionality is most often used by other developers who want their own javascript to interact with TimeCircles.
                        However, if you're not a developer yourself, there are still a few functions that are quite simple to use and shouldn't be too hard to tackle.
                    </p>
                    <p>
                        Before we go into what each function does however, it should be pointed out how these functions can be used.
                        Unlike quite a lot of other jQuery plugins, TimeCircles does not return a jQuery object after instantiating. Instead, it returns a TimeCircles object.
                        This means that function chaining will work slightly differently than it does for other jQuery plugins.
                        To find out more about how to chain other jQuery plugins and functions, have a look at the <code>end()</code> function.
                    </p>
                    <p>
                        TimeCircles functions themselves (with the exception of the <code>end()</code> function) will return the TimeCircles object.
                        This allows you to chain several functions into each other. IE: You could chain <code>start()</code> straight into <code>addEventListener(callback)</code>.
                    </p>
                    <h3 id="func_start_stop">start() and stop()</h3>
                    <p>
                        These are the most basic functions provided. They allow you to temporarily stop TimeCircles.
                        This is especially useful when you're using TimeCircles as a sort of stopwatch (ie: counting down a certain number of seconds).
                        If you're using TimeCircles to count down to a certain point in the future, obviously pausing TimeCircles isn't going to stop time itself.
                    </p>
                    <div class="panel panel-default">
                        <div class="panel-heading code"><?php echo highlightHTML('<div class="example stopwatch" data-timer="900"></div>
<button class="btn btn-success start">Start</button>
<button class="btn btn-success start">Stop</button>'); ?></div>
                        <div class="panel-body">
                            <div class="example stopwatch" data-timer="900"></div>
                            <button class="btn btn-success btn-small start">Start</button>
                            <button class="btn btn-danger btn-small stop">Stop</button>
                        </div>
                        <div class="panel-footer code"><?php echo highlightJS('$(".example").TimeCircles();
$(".stop").click(function(){ $(".example.stopwatch").TimeCircles().stop(); });
$(".start").click(function(){ $(".example.stopwatch").TimeCircles().start(); });', true); ?></div>
                    </div>
                    <h3 id="func_destroy">destroy()</h3>
                    <p>
                        If for some reason, you need to get rid of your TimeCircles, or you want to allow users remove them at the click of a button; you can do that with destroy.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles().destroy();'); ?></pre>
                    <h3 id="func_addListener">addListener <small><code>(callback)</code></small></h3>
                    <p>
                        The most powerful interactions with TimeCircles can be achieved using listeners.
                        Using listeners, you can make a ticking sound play every second, or you can make a sound whenever a minute passes.
                        You could even use it to trigger some alarm or whole other javascript when the timer hits zero.
                    </p>
                    <p>
                        To add a listener, use the <code>addEventListener(callback)</code> function.
                        Callback is a function you pass to the event listener. The callback will then be triggered for each event.
                        Three parameters are passed to your callback function, namely:
                    </p>
                    <ul>
                        <li><strong>unit</strong>: The time unit in string format. So, "Days"/"Hours"/"Minutes"/"Seconds".</li>
                        <li><strong>value</strong>: The new value of the time unit that changed. I.e.: 15.</li>
                        <li><strong>total</strong>: This is the total time left (or elapsed) since the zero point.</li>
                    </ul>
                    <h3 id="func_end">end()</h3>
                    <p>
                        To allow you to chain TimeCircles to other jQuery functions, you can use the <code>end()</code> function.
                        The end function returns the jQuery object and allows you to trigger jQuery function as desired.
                    </p>
                    <pre><?php echo highlightJS('$(".example").TimeCircles().end().fadeOut();'); ?></pre>
                    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                </div>
            </div>
        </div>
    </body>
</html>
