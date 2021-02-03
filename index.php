<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/database.php';


if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST["home_btn"]))
	{
		header("Location: index.php");
	}

	if(isset($_POST["teams_btn"]))
	{
		header("Location: teams.php");
	}

	if(isset($_POST["register_btn"]))
	{
		header("Location: register.php");
	}

	if(isset($_POST["brackets_btn"]))
	{
		header("Location: brackets.php");
	}

	if(isset($_POST["discord_btn"]))
	{
		header("Location: #"); // discord link
	}

	if(isset($_POST["admin_btn"]))
	{
		header("Location: admin.php");
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title> <?php echo $panel_name; //$panel_name can be changed in includes/database.php ?> </title>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	</head>

	<body>
		<center>
			<div class = "container-top"> 
				<h1> <?php echo $panel_name; ?> </h1>
				<nav>
				<form method = "post">
					
					<button class ="noborder" name="home_btn" > home </button>
					
					<button class ="noborder" name="teams_btn" > teams </button>
					
					<button class ="noborder" name="register_btn" > register </button>

					<button class ="noborder" name="brackets_btn" > brackets </button>

					<button class ="noborder" name="discord_btn" > discord </button>
					
					<button class ="noborder" name="admin_btn" > admin </button>
					
				</form>
				</nav>
			</div>
			
			<div class = "container-bottom"> 
				<?php echo $welcome_msg; //includes/database.php?>
			</div>

			
			<div class = "container-top"> 
				Countdown: <?php echo getCountdownTime(); //includes/utils.php ?>
			</div>


			<div class = "container-top"> 
				Informations
			</div>

			<div class = "container-bottom"> 
				1. The captains will veto for the map.
				<br>
				2. 2 timeouts are allowed per team, each timeout is 60 seconds.
				<br>
				3. The servers are located in France.
				<br>
				4. The maplist is: Mirage, Cache, Inferno, Overpass, Dust 2, Cobblestone, Train
				<br>
				5. First round is a knife round. The winner of the kniferound gets to choose side in the first half.
			</div>

			<div class = "container-top"> 
				Rules
			</div>

			<div class = "container-bottom"> 
				1. No player changes are allowed after the tournament has started.
				<br>
				2. Backup players can only join if one of the main players can't play. 
				<br>
				3. Do NOT use any sort of cheats/exploits, if anyone on the team caught using them, it will result in a disqualification.
				<br>
				4. The captain has to answer his discord within 5 minutes to make sure your team is ready.
			</div>

			<div class = "container-top"> 
				Prizes
			</div>

			<div class = "container-bottom"> 
				100$ For 1st spot
				<br>
				50$ For 2nd spot
			</div>


		</center>
	</body>
</html>