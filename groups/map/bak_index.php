<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Map of Babies, Bumps and Toddler group locations</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA5bAG-2z1ktdggkrfsgTAOxT9YQ5uhpV5MLJbpYlcp8iNHGueshS0Mp6Mr5shVyvl9qAmYM_dt_1_ww"
      type="text/javascript"></script>
    <script type="text/javascript">



    //<![CDATA[

    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(55.909403, -3.320699), 13);
      }
    }

    //]]>
    </script>
  </head>
  <body onload="load()" onunload="GUnload()">
<?
        $title="Edinburgh Twins Club :: Groups - Map";
        $root = "../../";
        INCLUDE '../../includes/header.inc';
    INCLUDE '../../includes/sidebar.inc';
?>

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
<? INCLUDE '../../includes/footer.inc'; ?>



