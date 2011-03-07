<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="../css/global.css" />
    <link rel="shortcut icon" href="../img/favicon.png" />
    <meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="ROBOTS" content="ALL" />
    <title>Edinburgh Twins Club :: Groups - Map</title>

    <style type="text/css">
    v:* {
      behavior:url(#default#VML);
    }
    </style>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA5bAG-2z1ktdggkrfsgTAOxT9YQ5uhpV5MLJbpYlcp8iNHGueshS0Mp6Mr5shVyvl9qAmYM_dt_1_ww"

 type="text/javascript">
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
   <script language="JavaScript" src="groupdata.js"></script>
   <script language="JavaScript" src="gpsdata.js"></script>

    <script type="text/javascript">
    //<![CDATA[

    function onLoad() {
      //
      // The site marker should be colour indexed to refelect the SFT site summary (SFT=10<=>OK=green, sft=50<=>Error=red)
      // 
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
      map.centerAndZoom(new GPoint(-3.240699, 55.959403), 7);
      //
      // Creates a marker whose info window displays the given number
      //
      function date() {
       var d=new Date();
       return d;
      }
      function getData(site) { 
          var xxx=dataObj[site];
          if (xxx==undefined) {
           xxx='No further information';
          }
          return xxx;
      }
      function getColorData(site) {
          var xxx=dataColorObj[site];
          return xxx;
      }
      function getLong(site) {
          var xxx=dataSiteLongObj[site];
          return xxx;
      }
      function getLat(site) {
          var xxx=dataSiteLatObj[site];
          return xxx;
      }
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

var point  = new GPoint(dataSiteLongObj['West-Edinburgh'],dataSiteLatObj['West-Edinburgh']);
var marker = createMarker(point,"West-Edinburgh","West-Edinburgh");
map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['Oxgangs'],dataSiteLatObj['Oxgangs']);
var marker = createMarker(point,"Oxgangs","Oxgangs");
map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['Longniddry'],dataSiteLatObj['Longniddry']);
var marker = createMarker(point,"Longniddry","Longniddry");
map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['First-Years'],dataSiteLatObj['First-Years']);
var marker = createMarker(point,"First-Years","First-Years");
map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['Comely-Bank'],dataSiteLatObj['Comely-Bank']);
var marker = createMarker(point,"Comely-Bank","Comely-Bank");
map.addOverlay(marker);

    }
    //]]>
    </script>


  </head>
  <body onload="onLoad()">
      <div class="pagetitle">
      </div>
      [%
    SET title = "Edinburgh and Lothians Twins and Multiples Club :: Groups - Map";
    SET root = "../../";
    INCLUDE '../../includes/header.inc';
    INCLUDE '../../includes/sidebar.inc';
      %]
    </div>
    <h1 class="header">Map of Regular Group Locations</h1>
    <div id="map" style="width: 600px; height: 400px"></div>
    <div id="message"></div>
<br><br>The above map shows all our regular group meeting locations.  Babies, Bumps and Toddler groups are marked with green balloons.  The First Years group is marked in yellow.  Click on a balloon for more information about meeting times, and directions. Double click on the map to zoom in on a location.<br><br>
[%
    INCLUDE '../../includes/footer.inc';
      %]
</div>
  </body>
</html>


