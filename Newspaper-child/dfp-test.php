<?php
/*
	Template Name: Test DFP
*/
?>

<!DOCTYPE html>
<html>
<head>
	<title>Test DFP</title>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="http://tiroliberoweb.it/wp-content/themes/Newspaper-child/js/isMobileNewtek.js"></script>

  <!-- Google Tag -->
  <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
  <script>
    var googletag = googletag || {};
    googletag.cmd = googletag.cmd || [];
  </script>

  <script>
  	var gptAdSlots = []; // Created the Array To Get BrowserSize,Ad Size
    googletag.cmd.push(function() {
	  var mapping = googletag.sizeMapping().
	  //addSize is the browser size and each subsequent dimension is an ad size addSize([Browser Width,Browser Height],[Ad Width,Ad Height]).
	  // Screens of any size smaller than infinite but bigger than 768x0
	  addSize([768, 0], [[728, 90],[970, 250]]).
	  addSize([0, 0], [320, 50]).
	  build();
      googletag.defineSlot('/165059490/Tiroliberoweb.it/Mpu_Midlle', [300, 250], 'div-gpt-ad-Mpu_Midlle').addService(googletag.pubads());
      googletag.defineSlot('/165059490/Tiroliberoweb.it/Mpu_Top', [[300, 250], [300, 600]], 'div-gpt-ad-Mpu_Top').addService(googletag.pubads());
      googletag.defineOutOfPageSlot('/165059490/Tiroliberoweb.it/Overlay', 'div-gpt-ad-Overlay').addService(googletag.pubads());
      googletag.defineSlot('/165059490/Tiroliberoweb.it/Skin', [1, 1], 'div-gpt-ad-Skin').addService(googletag.pubads());
      googletag.defineSlot('/165059490/Tiroliberoweb.it/Top', [[728, 90], [970, 250]], 'div-gpt-ad-Top').addService(googletag.pubads());
      googletag.defineSlot('/165059490/Tiroliberoweb.it/Bot', [320, 50], 'div-gpt-ad-Bot').addService(googletag.pubads());
      googletag.defineSlot('/165059490/Tiroliberoweb.it/Top_Mobile', [[320, 50], [320, 100]], 'div-gpt-ad-Top_Mobile').addService(googletag.pubads());
      googletag.pubads().enableSingleRequest();
      googletag.pubads().collapseEmptyDivs();
      googletag.enableServices();
    });
  </script>

</head>
<body>

<!-- /165059490/Tiroliberoweb.it/Skin -->
<div id='div-gpt-ad-Skin' style='height:1px; width:1px;'>
<script>
googletag.cmd.push(function() { googletag.display('div-gpt-ad-Skin'); });
</script>
</div>



<!-- /165059490/Tiroliberoweb.it/Top -->
  <div id='div-gpt-ad-Top'>
  	<script>
  	 googletag.cmd.push(function() { googletag.display('div-gpt-ad-Top'); });
  	</script>
  </div>


<!-- /165059490/Tiroliberoweb.it/Top_Mobile -->
  <div id='div-gpt-ad-Top_Mobile'>
    <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-Top_Mobile'); });
    </script>
  </div>



</body>
</html>