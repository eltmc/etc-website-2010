[%
    SET title = "Edinburgh Twins Club :: Announcements";
    SET root = "";
    INCLUDE 'header.inc';
    INCLUDE 'sidebar.inc';
%]

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

[% INCLUDE 'footer.inc'; %]
