[%
    SET title = "Edinburgh Twins Club :: Announcements";
    SET root = "";
    INCLUDE 'header.inc';
    INCLUDE 'sidebar.inc';
%]

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

$sql = 'SELECT t.topic_id, t.forum_id, topic_type, topic_title, post_text, post_time, username_clean, 
               bbcode_uid, bbcode_bitfield, enable_bbcode
    FROM ' . TOPICS_TABLE . ' as t
    LEFT JOIN ' . POSTS_TABLE . ' AS p ON p.topic_id = t.topic_id
    LEFT JOIN ' . USERS_TABLE . ' AS u ON p.poster_id = u.user_id
    WHERE topic_type != 0  -- no normal posts
      AND (topic_time_limit = 0 OR unix_timestamp() > post_time + topic_time_limit)
    ORDER BY post_time
    LIMIT 20';

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
          <ul>

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
	return "<li><a href='messageboard/viewtopic.php?f=$forum_id&amp;t=$topic_id'>$title</a> $text</li>";
    }


    while ($row = $db->sql_fetchrow($result))
    {
        echo call_user_func_array("render_announcement", $row);
    }
?>
          </ul>
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

[% INCLUDE 'footer.inc'; %]
