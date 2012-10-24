<?php
	require_once("./stpiadmin/includes/includes.php");
	require_once("./stpiadmin/includes/classes/content/clshead.php");
	require_once("./stpiadmin/includes/classes/javascript/clsjavascript.php");
	require_once("./stpiadmin/includes/classes/security/clslock.php");
	
		
	$strPage = basename($_SERVER["SCRIPT_NAME"]);
	
	$objTexte = new clstexte("./texte/home");
	$objHead = new clshead($objTexte->stpi_getArrTxt("headtitre"), $objTexte->stpi_getArrTxt("keywords"), $objTexte->stpi_getArrTxt("description"));
	$objJavaScript = new clsjavascript();
	$objLock = new clslock($strPage, "login.php");
	
	$objLock->stpi_pageNotEncrypted();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php
		$objHead->stpi_affPublicHead();
	?>
	<!--[if IE]>
	<style type="text/css">
		img {filter: alpha(opacity=100);}
	</style>
	<![endif]-->
	</head>
	<body onload="javascript:splash(2);">
	
		<center>
		
		<?php

			$objJavaScript->stpi_affNoJavaScript();
			
		?>
		
		<script type="text/javascript">
		<!--
		// IXF1.11 :: Image cross-fade 
		// *****************************************************
		//******************************************************
		//global object
		var ixf = { 'clock' : null, 'count' : 1 }
		/*******************************************************
		
		
		
		/*****************************************************************************
		 List the images that need to be cached
		*****************************************************************************/
		
		ixf.imgs = [
			'images/welcomeen.jpg',
			'images/welcomees.jpg',
			'images/welcomefr.jpg',
			'images/welcomeen_mo.jpg',
			'images/welcomees_mo.jpg',
			'images/welcomefr_mo.jpg',
			'images/splash/1.jpg',
			'images/splash/2.jpg',
			'images/splash/3.jpg',
			'images/splash/4.jpg',
			'images/splash/5.jpg'			
			];
		
		/*****************************************************************************
		*****************************************************************************/
		
		
		
		//cache the images
		ixf.imgsLen = ixf.imgs.length;
		ixf.cache = [];
		for(var i=0; i<ixf.imgsLen; i++)
		{
			ixf.cache[i] = new Image;
			ixf.cache[i].src = ixf.imgs[i];
		}
		
		
		//crossfade setup function
		function crossfade()
		{
			//if the timer is not already going
			if(ixf.clock == null)
			{
				//copy the image object 
				ixf.obj = arguments[0];
				
				//copy the image src argument 
				ixf.src = arguments[1];
				
				//store the supported form of opacity
				if(typeof ixf.obj.style.opacity != 'undefined')
				{
					ixf.type = 'w3c';
				}
				else if(typeof ixf.obj.style.MozOpacity != 'undefined')
				{
					ixf.type = 'moz';
				}
				else if(typeof ixf.obj.style.KhtmlOpacity != 'undefined')
				{
					ixf.type = 'khtml';
				}
				else if(typeof ixf.obj.filters == 'object')
				{
					//weed out win/ie5.0 by testing the length of the filters collection (where filters is an object with no data)
					//then weed out mac/ie5 by testing first the existence of the alpha object (to prevent errors in win/ie5.0)
					//then the returned value type, which should be a number, but in mac/ie5 is an empty string
					ixf.type = (ixf.obj.filters.length > 0 && typeof ixf.obj.filters.alpha == 'object' && typeof ixf.obj.filters.alpha.opacity == 'number') ? 'ie' : 'none';
				}
				else
				{
					ixf.type = 'none';
				}
				
				//change the image alt text if defined
				if(typeof arguments[3] != 'undefined' && arguments[3] != '')
				{
					ixf.obj.alt = arguments[3];
				}
				
				//if any kind of opacity is supported
				if(ixf.type != 'none')
				{
					//create a new image object and append it to body
					//detecting support for namespaced element creation, in case we're in the XML DOM
					ixf.newimg = document.getElementsByTagName('body')[0].appendChild((typeof document.createElementNS != 'undefined') ? document.createElementNS('http://www.w3.org/1999/xhtml', 'img') : document.createElement('img'));
		
					//set positioning classname
					ixf.newimg.className = 'idupe';
					
					//set src to new image src
					ixf.newimg.src = ixf.src
		
					//move it to superimpose original image
					ixf.newimg.style.left = ixf.getRealPosition(ixf.obj, 'x') + 'px';
					ixf.newimg.style.top = ixf.getRealPosition(ixf.obj, 'y') + 'px';
					
					//copy and convert fade duration argument 
					ixf.length = parseInt(arguments[2], 10) * 1000;
					
					//create fade resolution argument as 20 steps per transition
					ixf.resolution = parseInt(arguments[2], 10) * 20;
					
					//start the timer
					ixf.clock = setInterval('ixf.crossfade()', ixf.length/ixf.resolution);
				}
				
				//otherwise if opacity is not supported
				else
				{
					//just do the image swap
					ixf.obj.src = ixf.src;
				}
				
			}
		};
		
		
		//crossfade timer function
		ixf.crossfade = function()
		{
			//decrease the counter on a linear scale
			ixf.count -= (1 / ixf.resolution);
			
			//if the counter has reached the bottom
			if(ixf.count < (1 / ixf.resolution))
			{
				//clear the timer
				clearInterval(ixf.clock);
				ixf.clock = null;
				
				//reset the counter
				ixf.count = 1;
				
				//set the original image to the src of the new image
				ixf.obj.src = ixf.src;
			}
			
			//set new opacity value on both elements
			//using whatever method is supported
			switch(ixf.type)
			{
				case 'ie' :
					ixf.obj.filters.alpha.opacity = ixf.count * 100;
					ixf.newimg.filters.alpha.opacity = (1 - ixf.count) * 100;
					break;
					
				case 'khtml' :
					ixf.obj.style.KhtmlOpacity = ixf.count;
					ixf.newimg.style.KhtmlOpacity = (1 - ixf.count);
					break;
					
				case 'moz' : 
					//restrict max opacity to prevent a visual popping effect in firefox
					ixf.obj.style.MozOpacity = (ixf.count == 1 ? 0.9999999 : ixf.count);
					ixf.newimg.style.MozOpacity = (1 - ixf.count);
					break;
					
				default : 
					//restrict max opacity to prevent a visual popping effect in firefox
					ixf.obj.style.opacity = (ixf.count == 1 ? 0.9999999 : ixf.count);
					ixf.newimg.style.opacity = (1 - ixf.count);
			}
			
			//now that we've gone through one fade iteration 
			//we can show the image that's fading in
			ixf.newimg.style.visibility = 'visible';
			
			//keep new image in position with original image
			//in case text size changes mid transition or something
			ixf.newimg.style.left = ixf.getRealPosition(ixf.obj, 'x') + 'px';
			ixf.newimg.style.top = ixf.getRealPosition(ixf.obj, 'y') + 'px';
			
			//if the counter is at the top, which is just after the timer has finished
			if(ixf.count == 1)
			{
				//remove the duplicate image
				ixf.newimg.parentNode.removeChild(ixf.newimg);
			}
		};
		
		
		
		//get real position method
		ixf.getRealPosition = function()
		{
			this.pos = (arguments[1] == 'x') ? arguments[0].offsetLeft : arguments[0].offsetTop;
			this.tmp = arguments[0].offsetParent;
			while(this.tmp != null)
			{
				this.pos += (arguments[1] == 'x') ? this.tmp.offsetLeft : this.tmp.offsetTop;
				this.tmp = this.tmp.offsetParent;
			}
			
			return this.pos;
		};
		
		function splash(nnbImage)
		{
			var nbImages = 5;
			
			if (nnbImage > nbImages)
			{
				nnbImage = 1;
			}
			
			crossfade(document.getElementById("splash"), "images/splash/" + nnbImage + ".jpg", "2", "splash" + nnbImage);
			
			nnbImage++;
			
			var t = setTimeout("splash(" + nnbImage + ");", 4 * 1000);
		}
		
		-->
		</script>

		<img style="margin: 35px 0px 0px 0px;" id="splash" src="images/splash/1.jpg" width="550" height="500" alt="splash1" />

		<br/>

		<a href="./home.php?l=en"><img id="welcomeen" onmouseover="document.getElementById('welcomeen').src = './images/welcomeen_mo.jpg';" onmouseout="document.getElementById('welcomeen').src = './images/welcomeen.jpg';" alt="English" src="./images/welcomeen.jpg" /></a><a href="./home.php?l=es"><img alt="Espanol" id="welcomees" onmouseover="document.getElementById('welcomees').src = './images/welcomees_mo.jpg';" onmouseout="document.getElementById('welcomees').src = './images/welcomees.jpg';" src="./images/welcomees.jpg" /></a><a style="text-align: center;" href="./home.php?l=fr"><img alt="Français" id="welcomefr" onmouseover="document.getElementById('welcomefr').src = './images/welcomefr_mo.jpg';" onmouseout="document.getElementById('welcomefr').src = './images/welcomefr.jpg';" src="./images/welcomefr.jpg" /></a>
		
		</center>
				
	</body>	
</html>