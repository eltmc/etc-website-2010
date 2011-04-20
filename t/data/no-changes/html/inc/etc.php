<?php

define('IN_PHPBB', true);
$phpbb_root_path = "../messageboard/";

$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'config.' . $phpEx);
include($phpbb_root_path . 'includes/constants.' . $phpEx);
include($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
include($phpbb_root_path . '/includes/functions_content.' . $phpEx); 
include($phpbb_root_path . 'includes/session.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx); // for bbcode



function etc_announcements_sql($group = 'GUESTS', $acl_option = 'f_list', $forum_list = array()) 
{
    $forum_constraint = '';
    if (isset($forum_list) && count($forum_list)) {
	$forum_constraint = 'AND t.forum_id in ('. implode(',', $forum_list).')';
    }

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

	  $forum_constraint /* limit the forum list */

	  AND t.topic_type != 0                      /* non-normal posts */
	  AND ao.auth_option = '$acl_option'         /* listable */
	  AND g.group_name = '$group'                /* by guests */

	  AND (topic_time_limit = 0 OR 
	       unix_timestamp() < post_time + topic_time_limit) /* unexpired */

	ORDER BY post_time DESC
	LIMIT 20
    ";

    return $sql;
}


function etc_announcements($group = 'GUESTS', $acl_option = 'f_list', $forum_list = array()) 
{
    // globals we have to get access to 
    global $db, $sql_db, $dbhost, $dbuser, $dbpasswd, $dbname, $dbport, $user;

    $db = new $sql_db();
    $db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false);

    $user = new user();
    $user->lang['IMAGE'] = 'image'; // a frig for bbcode class

    $sql = etc_announcements_sql($group, $acl_option, $forum_list);
    $result = $db->sql_query($sql);

    return $result;
}


function etc_render_announcement($topic_id, $forum_id, $topic_type, $title, $text, $time, $user, 
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


function etc_nl_to_p($text)
{
    return '<p>'.str_replace("\n\n", '</p><p>', $text).'</p>';
}


function etc_render_event($topic_id, $forum_id, $topic_type, $title, $text, $time, $user, 
                          $bbcode_uid, $bbcode_bitfield, $enable_bbcode)
{
    global $phpbb_root_path;

    // untaint the variables
    $topic_id = (int)$topic_id;
    $forum_id = (int)$forum_id;
    $topic_type = (int)$topic_type;

//        $title = htmlentities($title);

    // Parse bbcode if bbcode uid stored and bbcode enabled
    // There is some liberal frigging of the bbcode class here
    if ($bbcode_uid && $enable_bbcode)
    {
	 $bbcode = new bbcode();
	 $bbcode->template_filename = "$phpbb_root_path/styles/prosilver/template/bbcode.html";
	 $bbcode->template_bitfield = new bitfield(base64_encode(0xfff));
	 $bbcode->bbcode_second_pass($text, $bbcode_uid, $bbcode_bitfield);
    }

    // this URL assumes we know where we are in the tree.  Probably a dodgy assumption.
    $topic_url = "../messageboard/viewtopic.php?f=$forum_id&amp;t=$topic_id";
    $time = strftime('%R %d/%m/%Y', $time);
    $text = etc_nl_to_p($text);
    return "
   <h2>$title</h2>
   $text

   <p><a href='$topic_url'><span>Posted at $time</span></a></p>
";
}

?>