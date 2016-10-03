<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hourglass</title>
//-v--Call to Load CSS
<link rel="stylesheet" type="text/css" href="menubase.css" />
<link rel="stylesheet" type="text/css" href="menutopbar.css" />
<link rel="stylesheet" type="text/css" href="menusidebar.css" />
//-v--Call to Load JS
<script src="ctqmenu-3.0.2.min.js" type="text/javascript"></script>
</head>
<body>
//-v--HTML Module Section of Menu
<div id="topmenubar" class="slantedmenu">
<ul>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/index.html">Home</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/" rel="submenu1">Information Technology</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/travel/" rel="submenu2">Travel</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/chat/" rel="submenu3">Support</a></li><!--/forum.html-->
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/down.html">Downloads</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/prjects.html">Projects</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/contact.html">Contact Us</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/" rel="submenu4">Archive</a></li>
</ul>
</div>
<script type="text/javascript">
<!--
menu.setup("topmenubar", "topbar")
-->
</script>
<ul id="submenu1" class="submenustyle">
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/custom.html">Custom Built Computers</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/upgrade.html">Upgrades</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/repair.html">Repairs</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/special.html">Special Service</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/deliver.html">Getting Service</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/it/warranty.html">Warranties</a></li>
</ul>
<ul id="submenu2" class="submenustyle">
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/travel/">Travel</a></li>
</ul>
<ul id="submenu3" class="submenustyle">
<!--<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/forum.html">Forum</a></li>-->
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/chat/">Live Chat</a>
<ul>
</ul>
</li>
</ul>
<ul id="submenu4" class="submenustyle">
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2006.html">2006</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2007.html">2007</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2008.html">2008</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2009.html">2009</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2010.html">2010</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2011.html">2011</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2012.html">2012</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2013.html">2013</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2014.html">2014</a></li>
<li><a href="<?php echo $_SESSION['sessionbase'] . '?ctqrt=' ?>/archive/2015.html">2015</a></li>
</ul>
</body>