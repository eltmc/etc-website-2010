[%
    SET title = "Edinburgh Twins Club :: Events";
    SET root = "../";
    INCLUDE 'header.inc';
    INCLUDE 'sidebar.inc';
%]

    <div id="content">
    <h1 class="header">Events</h1>

<p>Besides our regular <a href="[% root %]groups">groups</a>, we organise a
variety of events for our members and their families.  Upcoming events
are listed below.</p>

<p>Note, that we also have a <a href="calendar">calendar of events and
groups</a>. Follow the link to view.</p>

<p>To book any event please email our social convener at <a
href="mailto:social@edinburghtwins.co.uk">social@edinburghtwins.co.uk</a></p>


<?php
    include('[% root %]inc/etc.php');

    $result = etc_announcements('GUESTS', 'f_list', array(5, 7));

    while ($row = $db->sql_fetchrow($result))
    {
        echo call_user_func_array("etc_render_event", $row);
    }
?>

</div>
[% INCLUDE 'footer.inc'; %]
