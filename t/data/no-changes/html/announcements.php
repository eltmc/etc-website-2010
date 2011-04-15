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
                 <ul>
                  <li><a class="ulheading" href="groups/map">Map</a></li>
                 </ul>
              <div>Babies, Bumps &amp; Toddler Groups</div>
            </li>
            <li>
              <a class="ulheading" href="events/">Events</a>
              <div>Social Events for twins and their parents?</div>
                 <ul>
                  <li><a class="ulheading" href="events/map">Map</a></li>
                 </ul>
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
              <div>Lots of benefits for only &pound;10 a year</div>
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


<?php
define('IN_PHPBB', true);
$phpbb_root_path = "messageboard/";

$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'config.' . $phpEx);
include($phpbb_root_path . 'includes/constants.' . $phpEx);
include($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
include($phpbb_root_path . '/includes/functions_content.' . $phpEx); 
include($phpbb_root_path . 'includes/session.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx); // for bbcode

// some globals the phpBB code seems to require.
$db             = new $sql_db();
$user           = new user();
$user->lang['IMAGE'] = 'image'; // a frig for bbcode class

$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false);

// We do not need this any longer, unset for safety purposes
unset($dbpasswd);

// This query selects the announcements to show.
$group = 'GUESTS';
$acl_option = 'f_list';
$sql = "
    SELECT t.topic_id, t.forum_id, topic_type, topic_title, post_text, post_time, username_clean, 
           bbcode_uid, bbcode_bitfield, enable_bbcode
    FROM " . TOPICS_TABLE      . " as t, /* for the topics */
         " . GROUPS_TABLE      . " as g, /* for group names */
         " . POSTS_TABLE . " as p, /* for option names */
         " . USERS_TABLE . " as u, /* for option names */

         " . ACL_OPTIONS_TABLE . " as ao, /* for ACL option names */
         " . ACL_GROUPS_TABLE . " as ag /* for group ACL settings */

    WHERE t.topic_id = p.topic_id           /* join posts table */
      AND t.topic_first_post_id = p.post_id /* for the first post */

      AND p.poster_id = u.user_id           /* join users table */

      AND t.forum_id = ag.forum_id          /* join the forum ACLs */

      AND ag.group_id = g.group_id              /* join the groups table */
      AND ag.auth_option_id = ao.auth_option_id /* join the acl_options table */

      AND t.topic_type != 0                      /* non-normal posts */
      AND ao.auth_option = '$acl_option'         /* listable */
      AND g.group_name = '$group'                /* by guests */

      AND (topic_time_limit = 0 OR 
           unix_timestamp() < post_time + topic_time_limit) /* unexpired */
      
    ORDER BY post_time DESC
    LIMIT 20
";

$result = $db->sql_query($sql);

//$db->sql_close();

?>
    <div id="content">

        <p><strong>Hello and welcome to the Edinburgh Twins Club
        website. If you have or are expecting twins or more,
        especially if you live in or around Edinburgh, then there
        should be some useful information for you here.</strong></p>

        <p>We are a registered charity, celebrating and supporting
        families of multiples in Edinburgh and the Lothians.  We have
        a membership of over 150 families - the largest in
        Scotland!</p>
        
        <p>In 2008 we celebrated 30 years since the club started.</p>
        
        <p>Come on in and take a look around.  If there is anything
        you need from our old website, you can still find it <a
        href="oldwebsite">here</a>.</p>

        <div class="announcements">
          <h2>Announcements</h2>
<?php
    function render_announcement($topic_id, $forum_id, $topic_type, $title, $text, $time, $user, 
                                 $bbcode_uid, $bbcode_bitfield, $enable_bbcode)
    {
	global $phpbb_root_path;

        // untaint the variables
        $topic_id = (int)$topic_id;
        $forum_id = (int)$forum_id;
        $topic_type = (int)$topic_type;

//        $title = htmlentities($title);

	// remove all but the first paragraph
	$eop = strpos($text, "\n\n");
	if ($eop !== false)
	{
	    $text = substr($text, 0, $eop);
	}

	// Parse bbcode if bbcode uid stored and bbcode enabled
	// There is some liberal frigging of the bbcode class here
	if ($bbcode_uid && $enable_bbcode)
	{
	     $bbcode = new bbcode();
	     $bbcode->template_filename = "$phpbb_root_path/styles/prosilver/template/bbcode.html";
	     $bbcode->template_bitfield = new bitfield(base64_encode(0xfff));
	     $bbcode->bbcode_second_pass($text, $bbcode_uid, $bbcode_bitfield);
	}
	$topic_url = "messageboard/viewtopic.php?f=$forum_id&amp;t=$topic_id";
	return "<li><b><a href='$topic_url'>$title</a></b> $text <small>(<a href='$topic_url'>more...</a>)</small></li>\n";
    }

    echo "<ul>\n";
    while ($row = $db->sql_fetchrow($result))
    {
        echo call_user_func_array("render_announcement", $row);
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

