<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Map of Babies, Bumps and Toddler group locations</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA5bAG-2z1ktdggkrfsgTAOxT9YQ5uhpV5MLJbpYlcp8iNHGueshS0Mp6Mr5shVyvl9qAmYM_dt_1_ww"
      type="text/javascript"></script>
    <script type="text/javascript">
    </script>

   <script language="JavaScript">
    dataSet = new Array()
    dataObj = new Object()
    dataColorObj = new Object();
    datalongObj =  new Object();
    datalatObj =  new Object();
    datasiteObj = new Object();
    dataSiteLongObj=new Object();
    dataSiteLatObj=new Object();
   </script>

    <script type="text/javascript">
    //<![CDATA[



    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(55.809403, -3.100699), 10);
      }
       // Create our "tiny" marker icon
       var icon = new GIcon();
       icon.image = "http://labs.google.com/ridefinder/images/mm_20_" + color + ".png";
       //icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
       icon.iconSize = new GSize(12, 20);
       icon.shadowSize = new GSize(22, 20);
       icon.iconAnchor = new GPoint(6, 20);
       icon.infoWindowAnchor = new GPoint(5, 1);
       return icon;
    }

    function createMyIcon(color) {
       // Create our "tiny" marker icon
       var icon = new GIcon();
       icon.image = "http://labs.google.com/ridefinder/images/mm_20_" + color + ".png";
       //icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
       icon.iconSize = new GSize(12, 20);
       icon.shadowSize = new GSize(22, 20);
       icon.iconAnchor = new GPoint(6, 20);
       icon.infoWindowAnchor = new GPoint(5, 1);
       return icon;
      }

      // Event Listeners
      //
      // Event listeners are registered with =GEvent.addListener=. In this example,
      // we echo the lat/lng of the center of the map after it is dragged or moved
      // by the user.
      var map = new GMap(document.getElementById("map"));
      map.addControl(new GLargeMapControl());
      map.addControl(new GMapTypeControl());
      map.centerAndZoom(new GPoint(-5.0, 56.6), 10);
      //
      // Creates a marker whose info window displays the given number
      //

      function createMarker(point, text, site) {
        if (dataObj[site]==undefined) {
         var color='blue';
        }else{
         var color=getColorData(site);
        }
         var icon=new createMyIcon(color);
         var marker = new GMarker(point,icon);
       
        // Show this marker's index in the info window when it is clicked
        var html = getData(site)
 
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }
//Heriot Watt University
var point  = new 
GPoint(-3.320699,55.909403);
var marker = createMarker(point);
map.addOverlay(marker);
    //]]>
    </script>
  </head>
  <body onload="load()" onunload="GUnload()">
[%
    SET title = "Edinburgh Twins Club :: Groups - Map";
    SET root = "../../";
        INCLUDE '../../includes/header.inc';
    INCLUDE '../../includes/sidebar.inc';
%]

    </div>
    <div id="content">
    <h1 class="header">Groups Map</h1>
        <p>
        <strong>
        </strong>
        </p>
<h2></h2>
    <div id="map" style="width: 700px; height: 700px"></div>
    </div>
    <div class="clear">
      <br />
    </div>
[% INCLUDE '../../includes/footer.inc'; %]



