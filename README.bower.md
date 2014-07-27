# Method for installing TimeCircles in your project using bower (http://bower.io)

If you don't have node.js, npm and bower installed, please fallback to the installation method described in README.md or install them before going any further. 
This method assumes that you have basic knowledge of these tools and already have them installed and available on a cli based shell.
  
If you don't already use bower in your project, type the following command in a cli shell at the root of your project and answer all the questions asked :
  
    bower init
  
Once bower is installed, in a cli shell at the root of your project, type the following :
  
    bower install timecircles --save
  
And then, in your html files, include the following in the `<head>` section :    
  
    <script type="text/javascript" src="bower_components dir\jquery\dist\jquery.min.js""></script>
    <script type="text/javascript" src="bower_components dir\timecircles\inc\TimeCircles.js"></script>
    <link href="bower_components dir\timecircles\inc\TimeCircles.css" rel="stylesheet">
      
Replace `bower_components dir` with the relative path to the bower install directory configured in your project's bower.json file (`bower_components` at the root of your site by default).
     
If you prefer using a public CDN for jquery, you could replace the first line by :
  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
