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
		header("Location: #");
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
		<title> <?php echo $panel_name; ?> </title>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	</head>

	<body>
		<center>
			<div class = "container-top"> 
				<h1> <?php echo $panel_name; ?> </h1>
				
				<form method = "post">
					
					<button class ="noborder" name="home_btn" > home </button>
					
					<button class ="noborder" name="teams_btn" > teams </button>
					
					<button class ="noborder" name="register_btn" > register </button>

					<button class ="noborder" name="brackets_btn" > brackets </button>
					
					<button class ="noborder" name="discord_btn" > discord </button>					

					<button class ="noborder" name="admin_btn" > admin </button>
					
				</form>
				
			</div>
			
			<div class = "container-bottom"> 
				<?php echo $welcome_msg; ?>
			</div>


			<div class = "container-top"> 
				Countdown: <?php echo getCountdownTime(); ?>
			</div>

			<?php 

				if(getChampion() != 0) { ?>

				<div class = "container-top"> 
					Champions
				</div>
				
				<div class = "container-bottom"> 

					<?php 					

						$champtions = $db->prepare("SELECT * FROM teams WHERE id=:id;");
						$champtions->bindValue(":id", getChampion());
						$champtions->execute();
						
						while($team = $champtions->fetch())
						{
							echo "<p style = 'color: gold;' class = 'teamname'>" . $team["teamname"] . "<p>";

							$members = $db->prepare("SELECT * FROM members WHERE teamname=:teamname;");
							$members->bindValue(":teamname", $team["teamname"]);
							$members->execute();

							$count = 0;
							echo "<center> <div class = 'teamcontainer'>";

							while($member = $members->fetch())
							{
								if($count==5)
									break;
								
								echo "<div class = 'teammember'> <a href = '" . $member["steamurl"] . "'> <img src='" . $member["avatarurl"] . "'> </a> <p> {$member['username']} </p> </div>";
								
								$count = $count + 1;
							}

							echo "</div> </center>";
						}
					?>

				</div>

				<?php }

			?>

			<div class = "container-top"> 
				Teams
			</div>

			<div class = "container-bottom"> 
				
				<?php 

					$teams = $db->prepare("SELECT * FROM teams;");
					$teams->execute();
					
					while($team = $teams->fetch())
					{
						if($team["accepted"] != "1")
							continue;

						if(getChampion() != 0 && $team["id"] == getChampion())
							continue;
						
						echo "<p class = 'teamname'>" . $team["teamname"] . "<p>";

						$members = $db->prepare("SELECT * FROM members WHERE teamname=:teamname;");
						$members->bindValue(":teamname", $team["teamname"]);
						$members->execute();

						$count = 0;
						echo "<center> <div class = 'teamcontainer'>";

						while($member = $members->fetch())
						{
							if($count==5)
								break;
							
							echo "<div class = 'teammember'> <a href = '" . $member["steamurl"] . "'> <img src='" . $member["avatarurl"] . "'> </a> <p> {$member['username']} </p> </div>";
							
							$count = $count + 1;
						}

						echo "</div> </center>";
					}

				?>

			</div> 
			

		</center>
	</body>
</html>