<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="css/global.css" />
    
    <meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="ROBOTS" content="ALL" />
    <title>Edinburgh Twins Club :: Announcements</title>
  </head>
  <body>
      
        <div class="pagetitle">
      <a href="."><img src="graphics/twinslogo7.gif" alt="Edinburgh and Lothians Twin Club" /></a><hr/>
      </div>

<div id="nav">
      <div class="button"></div>
      <div id="links" class="menu">
        <div class="title">On <a href=".">Edinburgh Twins Club</a></div>
        <div class="menucontent">
          <ul class="makeMenu">
            <li>
              <a class="ulheading" href="aboutus/">About Us</a>
                <ul>
                  <li><a class="ulheading" href="aboutus/howitstarted">How it started</a></li>
                  <li><a class="ulheading" href="aboutus/committee">Committee</a></li>
                </ul>
              <div>What is the ETC?</div>
            </li>
            <li>
              <a class="ulheading" href="groups/">Groups</a>
              <div>Babies, Bumps &amp; Toddler Groups</div>
            </li>
            <li>
              <a class="ulheading" href="events/">Events</a>
              <div>Social Events for twins and their parents?</div>
            </li>
            <li>
              <a class="ulheading" href="messageboard.php">Messageboard</a>
              <div>Chat with other twin mums and dads</div>
            </li>
            <li>
              <a class="ulheading" href="triplets/">Triplets</a>
              <div>Information for parents and carers of triplets</div>
            </li>
            <li>
              <a class="ulheading" href="tips/">Tips</a>
                <ul>
                  <li><a class="ulheading" href="tips/feeding">Feeding</a></li>
                  <li><a class="ulheading" href="tips/outabout">Out and About</a></li>
                  <li><a class="ulheading" href="tips/prams">Prams</a></li>
                  <li><a class="ulheading" href="tips/general">General</a></li>
                  <li><a class="ulheading" href="tips/goodbuys">Good Buys</a></li>
                </ul>
              <div>Useful tips on how to cope with new twins</div>
            </li>
            <li>
              <a class="ulheading" href="join">Join</a>
              <div>Lots of benefits for only &pound;12 a year</div>
            </li>
            <li>
              <a class="ulheading" href="recommended">Recommended</a>                <ul>
                  <li><a class="ulheading" href="recommended/pregnancy">Pregnancy</a></li>
                  <li><a class="ulheading" href="recommended/baby">Baby</a></li>
                  <li><a class="ulheading" href="recommended/toddler">Toddler</a></li>
                  <li><a class="ulheading" href="recommended/child">Child</a></li>
                  <li><a class="ulheading" href="recommended/feeding">Feeding</a></li>
                  <li><a class="ulheading" href="recommended/booksforchildren">Books For Children</a></li>
                  <li><a class="ulheading" href="recommended/general">General</a></li>
                </ul>              <div>Useful Books</div>
            </li>
            <!-- <li>
              <a class="ulheading" href="members">Members</a>
                <ul>
                  <li><a class="ulheading" href="members/newsletters">Newsletters</a></li>
                  <li><a class="ulheading" href="members/discounts">Discounts</a></li>
                </ul>
              <div>Members Only Area</div>
            </li>
            <li>
              <a class="ulheading" href="committee">Committee</a>
                <ul>
                  <li><a class="ulheading" href="committee/constitution">Constitution</a></li>
                  <li><a class="ulheading" href="committee/minutes">Minutes</a></li>
                  <li><a class="ulheading" href="committee/reports">Reports</a></li>
                </ul>
              <div>Committee Members Only Area</div>
            </li> -->
            <li>
              <a class="ulheading" href="links">Links</a>
              <div>Some twin related links</div>
            </li>
            <li>
              <a class="ulheading" href="contacts/">Contacts</a>
              <div>Any questions? Drop us an email...</div>
            </li>
          </ul>
<iframe style="margin: 0 auto; border: 0; width: 120px; height: 90px;" src="http://rcm-uk.amazon.co.uk/e/cm?t=edinburghtwin-21&amp;o=2&amp;p=20&amp;l=qs1&amp;f=ifr" scrolling="no"></iframe>
        </div>
      </div>
</div>


    <div id="content">

        <p><b>Hello and welcome to the Edinburgh and Lothians Twins and
        Multiples Club website.  If you have or are expecting twins or
        more, and if you live in the Lothians, then this is the
        website for you.</b></p>

	<p>We are a registered charity, celebrating and supporting
	families of multiples in Edinburgh and the Lothians since
	1978. With an ever increasing membership, we are the largest
	club in Scotland.</p>

	<p>We provide peer support, from ante-natal classes to toddler
	groups, working alongside our local hospitals, midwives and
	health visitors to support parents of multiples. We can offer
	advice on pregnancy and birth, getting help and support,
	buying the right equipment, and feeding and caring for your
	babies once they are here.</p>

	<p>Come on in and take a look around. Our website is packed
	with useful information; check out
	our <a href="messageboard.php">message board</a> for information on times of
	our groups, items for sale, or join in the chat with any
	multiple related questions you may have.</p>

	<p>We look forward to hearing from you!</p>

        <div class="announcements">
          <h2>Announcements</h2>
<?php
    include('inc/etc.php');

    $result = etc_announcements('GUESTS', 'f_list');

    echo "<ul>\n";
    while ($row = $db->sql_fetchrow($result))
    {
        echo call_user_func_array("etc_render_announcement", $row);
    }
    echo "</ul>\n";
?>
        </div>


<div class="photo-bar">
<img src="graphics/photos/IMG_3660.JPG" alt="twin picture #1"/>
<img src="graphics/photos/AandL3.jpg" alt="twin picture #2"/>
<img src="graphics/photos/DSC01163.JPG" alt="twin picture #3"/>
<img src="graphics/photos/IMG_1873.JPG" alt="twin picture #4"/>
</div>

<div class="support-footer">
Please support the twin club -- if you buy from amazon using the search box on the menu we earn a small commission
</div>


</div>

<div id="footer">
	<a href="http://www.tamba.org.uk">A member of TAMBA</a>
	<hr />
	The Edinburgh Twins Club.  Registered Charity SCO39623
</div>
  </body>
</html>

