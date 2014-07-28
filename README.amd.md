# AMD script loaders

TimeCircles can also be used with an AMD script loader like require.js (http://requirejs.org).

The following instructions apply only to requirejs and have not be tested with another AMD loader (almond, etc. ...).

## Define TimeCircles in your configuration
	
	requirejs.config({
		paths : {
			"timecircles": 'timecircles install dir/inc/TimeCircles'
		}
	}

Replace `timecircles install dir` with the path to the TimeCircles directory in your project.
	
## Use TimeCircles as a dependency

At the beginning of your module, insert `timecircles` in your dependencies array :

	define(['require', 'jquery', 'timecircles'], 

	function(require, $, TimeCircles) {
		
		...
		
		var tc = new TimeCircles(('jquery selector'), options);
		
		OR
		
		var tc = $('jquery selector').TimeCircles(options);
		
		...
		// Use the tc variable as usual ...
	});

## CSS injection

### Using a CSS loader

You also can use the CSS loader written by guybedford here: https://github.com/guybedford/require-css.

This allows you to automatically inject the TimeCircles CSS only when the module using the library is loaded.

Also, you won't have to handle it in the `<head>` section of your html files.

For this, you first need to activate the CSS loader in your project. Use the instructions listed there: https://github.com/guybedford/require-css#installation-and-setup.

When you are done enabling the CSS loader, inject a `css!timecircles install dir/inc/TimeCircles` dependency in your module's definition :

	define(['require', 'jquery', 'timecircles', `css!timecircles install dir/inc/TimeCircles`]

Finally, replace `timecircles install dir` with the path to the TimeCircles directory in your project.
	
### Using a requirejs shim config

If you can't use a CSS loader, you can use a shim init callback in your requirejs config. It should be noted that, as a side effect, this technique only works if your html files are all at the same depth in your project's structure.

	requirejs.config({
		shim : {
			'timecircles': {
				init: function() {
					$('head').append('<link rel="stylesheet" href="../bower_dependencies/timecircles/inc/TimeCircles" type="text/css" />');
				}			
			},
		}
	});
	
In the example above, all the HTML files have to be in a subdirectory of the parent of bower_dependencies.