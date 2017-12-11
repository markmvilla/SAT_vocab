<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<!--
    SAT Vocabulary
    http://satvocab.org
    Author     Mark Villa
		Date 			2011
    Website    http://www.markvilla.org
    Copyright  2011 Mark Villa.
    -->

		<!-- meta stuff -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SAT Vocab - master the SAT Critical Reading section</title>
		<meta name="title" content="SAT Vocabulary" />
    <meta name="description" content="Tailored SAT vocabulary training for mastering the SAT Critial Reading section" />
    <meta name="keywords" content="master, SAT vocabulary, collegeboard, improve, score, critical, reading, Section" />

    <meta property="og:title" content="SAT Vocabulary"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="http://satvocab.org/images/og_logo.png"/>
    <meta property="og:url" content="http://satvocab.org"/>
    <meta property="og:site_name" content="SAT Vocabulary"/>
    <meta property="og:description" content="Tailored SAT vocabulary training."/>

		<!-- stylesheets -->
		<link rel="stylesheet" href="style.css" type="text/css" media="all" />

		<!-- jQuery -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

		<!-- vocabulary table -->
		<script type="text/javascript" src="dynatable/jquery.dynatable.js"></script>
		<link rel="stylesheet" href="dynatable/jquery.dynatable.css" type="text/css" media="all" />

		<!-- dynamic update -->
		<script type="text/javascript" src="main.js"></script>

	</head>
	<body<?php if($mini == true) { ?> class="mini"<?php } ?>>
		<div class="header">
			<img src="images/logo.png" alt="logo" height="60" width="60">
			<h1>SAT Vocabulary</h1>
		</div>
		<?php if ($display_navigation_bar == true) { ?>
		<div class="navigation-bar">
			<a href="index.php" <?php if($tab == 'search') { ?> class="search" <?php } ?>>Search</a>
			<span class="right">
				<a href="logout.php">log out</a>
			</span>
		</div>
		<?php } ?>
		<div class="container">
			<h3><?php echo $title; ?></h3>
			<?php echo $content; ?>
		</div>
	</body>
</html>
