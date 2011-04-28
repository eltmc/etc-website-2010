<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="../../css/global.css" />
    
    <meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="ROBOTS" content="ALL" />
    <title>Edinburgh Twins Club :: Events - Map</title>

    <style type="text/css">
    v:* {
      behavior:url(#default#VML);
    }
    </style>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA5bAG-2z1ktdggkrfsgTAOxT9YQ5uhpV5MLJbpYlcp8iNHGueshS0Mp6Mr5shVyvl9qAmYM_dt_1_ww"

 type="text/javascript">
    </script>
   <script language="javascript" type="text/javascript">
    dataSet = new Array()
    dataObj = new Object()
    dataColorObj = new Object();
    datalongObj =  new Object();
    datalatObj =  new Object();
    datasiteObj = new Object();
    dataSiteLongObj=new Object();
    dataSiteLatObj=new Object();
   </script>
   <script language="javascript" type="text/javascript" src="eventdata.js"></script>
   <script language="javascript" type="text/javascript" src="gpsdata.js"></script>

    <script language="javascript" type="text/javascript">
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
      map.centerAndZoom(new GPoint(-3.240699, 55.859403), 8);
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

// Add in the following after EGM has taken place ...
// var point  = new GPoint(dataSiteLongObj['Cumberland-Bar'],dataSiteLatObj['Cumberland-Bar']);
// var marker = createMarker(point,"Cumberland-Bar","Cumberland-Bar");
// map.addOverlay(marker);
//var point  = new GPoint(dataSiteLongObj['Triplets'],dataSiteLatObj['Triplets']);
//var marker = createMarker(point,"Triplets","Triplets");
//map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['NN'],dataSiteLatObj['NN']);
var marker = createMarker(point,"NN","NN");
map.addOverlay(marker);
//var point  = new GPoint(dataSiteLongObj['AD'],dataSiteLatObj['AD']);
//var marker = createMarker(point,"AD","AD");
//map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['MD'],dataSiteLatObj['MD']);
var marker = createMarker(point,"MD","MD");
map.addOverlay(marker);
var point  = new GPoint(dataSiteLongObj['EG'],dataSiteLatObj['EG']);
var marker = createMarker(point,"EG","EG");
map.addOverlay(marker);

    }
    //]]>
    </script>


</head>
<body onload="onLoad()">
      <div class="pagetitle">
      <a href="../../"><img src="../../graphics/twinslogo7.gif" alt="Edinburgh and Lothians Twin Club" /></a><hr/>
      </div>
<div id="nav">
      <div class="button"></div>
      <div id="links" class="menu">
        <div class="title">On <a href="../../">Edinburgh Twins Club</a></div>
        <div class="menucontent">
          <ul class="makeMenu">
            <li>
              <a class="ulheading" href="../../aboutus/">About Us</a>
                <ul>
                  <li><a class="ulheading" href="../../aboutus/howitstarted">How it started</a></li>
                  <li><a class="ulheading" href="../../aboutus/committee">Committee</a></li>
                </ul>
              <div>What is the ETC?</div>
            </li>
            <li>
              <a class="ulheading" href="../../groups/">Groups</a>
                 <ul>
                  <li><a class="ulheading" href="../../groups/map">Map</a></li>
                 </ul>
              <div>Babies, Bumps &amp; Toddler Groups</div>
            </li>
            <li>
              <a class="ulheading" href="../../events/">Events</a>
              <div>Social Events for twins and their parents?</div>
                 <ul>
                  <li><a class="ulheading" href="../../events/map">Map</a></li>
                 </ul>
            </li>
            <li>
              <a class="ulheading" href="../../messageboard.php">Messageboard</a>
              <div>Chat with other twin mums and dads</div>
            </li>
            <li>
              <a class="ulheading" href="../../triplets/">Triplets</a>
              <div>Information for parents and carers of triplets</div>
            </li>
            <li>
              <a class="ulheading" href="../../tips/">Tips</a>
                <ul>
                  <li><a class="ulheading" href="../../tips/feeding">Feeding</a></li>
                  <li><a class="ulheading" href="../../tips/outabout">Out and About</a></li>
                  <li><a class="ulheading" href="../../tips/prams">Prams</a></li>
                  <li><a class="ulheading" href="../../tips/general">General</a></li>
                  <li><a class="ulheading" href="../../tips/goodbuys">Good Buys</a></li>
                </ul>
              <div>Useful tips on how to cope with new twins</div>
            </li>
            <li>
              <a class="ulheading" href="../../join">Join</a>
              <div>Lots of benefits for only &pound;12 a year</div>
            </li>
            <li>
              <a class="ulheading" href="../../recommended">Recommended</a>                <ul>
                  <li><a class="ulheading" href="../../recommended/pregnancy">Pregnancy</a></li>
                  <li><a class="ulheading" href="../../recommended/baby">Baby</a></li>
                  <li><a class="ulheading" href="../../recommended/toddler">Toddler</a></li>
                  <li><a class="ulheading" href="../../recommended/child">Child</a></li>
                  <li><a class="ulheading" href="../../recommended/feeding">Feeding</a></li>
                  <li><a class="ulheading" href="../../recommended/booksforchildren">Books For Children</a></li>
                  <li><a class="ulheading" href="../../recommended/general">General</a></li>
                </ul>              <div>Useful Books</div>
            </li>
            <!-- <li>
              <a class="ulheading" href="../../members">Members</a>
                <ul>
                  <li><a class="ulheading" href="../../members/newsletters">Newsletters</a></li>
                  <li><a class="ulheading" href="../../members/discounts">Discounts</a></li>
                </ul>
              <div>Members Only Area</div>
            </li>
            <li>
              <a class="ulheading" href="../../committee">Committee</a>
                <ul>
                  <li><a class="ulheading" href="../../committee/constitution">Constitution</a></li>
                  <li><a class="ulheading" href="../../committee/minutes">Minutes</a></li>
                  <li><a class="ulheading" href="../../committee/reports">Reports</a></li>
                </ul>
              <div>Committee Members Only Area</div>
            </li> -->
            <li>
              <a class="ulheading" href="../../links">Links</a>
              <div>Some twin related links</div>
            </li>
            <li>
              <a class="ulheading" href="../../contacts/">Contacts</a>
              <div>Any questions? Drop us an email...</div>
            </li>
          </ul>
<iframe style="margin: 0 auto; border: 0; width: 120px; height: 90px;" src="http://rcm-uk.amazon.co.uk/e/cm?t=edinburghtwin-21&amp;o=2&amp;p=20&amp;l=qs1&amp;f=ifr" scrolling="no"></iframe>
        </div>
      </div>
</div>

<div id="content">
  <h1 class="header">Map of Event Locations</h1>
  <div id="map" style="width: 600px; height: 400px; border: solid 1px"></div>
  <div id="message"></div>
  <p>The above map shows all our event locations.  Click on a balloon for more information about meeting times, and directions. Double click on the map to zoom in on a location.</p>
</div>
<div id="footer">
	<a href="http://www.tamba.org.uk">A member of TAMBA</a>
	<hr />
	The Edinburgh Twins Club.  Registered Charity SCO39623
</div>
  </body>
</html>



