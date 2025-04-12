<!DOCTYPE html>
<html>
	<head>
		<title>Carbuy Auctions</title>
		<link rel="stylesheet" href="carbuy.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

	</head>

	<body>
		<header>
			<h1><span class="C">C</span>
 			<span class="a">a</span>
			<span class="r">r</span>
			<span class="b">b</span>
			<span class="u">u</span>
			<span class="y">y</span></h1>

			<form action="#">
				<input type="text" name="search" placeholder="Search for a car" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>
				<li><a class="categoryLink" href="#">Estate</a></li>
				<li><a class="categoryLink" href="#">Electric</a></li>
				<li><a class="categoryLink" href="#">Coupe</a></li>
				<li><a class="categoryLink" href="#">Saloon</a></li>
				<li><a class="categoryLink" href="#">4x4</a></li>
				<li><a class="categoryLink" href="#">Sports</a></li>
				<li><a class="categoryLink" href="#">Hybrid</a></li>
				<li><a class="categoryLink" href="#">More</a></li>

				
				<?php if (isset($_SESSION['user_id'])): ?>
      		<li>
	  			<a href="profile.php" style="color: black; font-size: 20px;">
  					<i class="fa-solid fa-user"></i>
				</a>

      		</li>
   	 			<?php else: ?>
     			<li><a class="authLink" href="login.php">Login</a></li>
    			<?php endif; ?>
  			</ul>
		</nav>
<?php
require_once('db.php');
$categories = $Connection->query("SELECT * FROM category ORDER BY category_name ASC")->fetchAll();
?>

<nav>
    <ul>
        <?php foreach ($categories as $cat): ?>
            <li><a class="categoryLink" href="categoryPage.php?category=<?= urlencode($cat['category_name']) ?>">
                <?= htmlspecialchars($cat['category_name']) ?>
            </a></li>
        <?php endforeach; ?>
    </ul>
</nav>
