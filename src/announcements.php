[%
    SET title = "Edinburgh Twins Club :: Announcements";
    SET root = "";
    INCLUDE 'header.inc';
    INCLUDE 'sidebar.inc';
%]

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

[% INCLUDE 'footer.inc'; %]
