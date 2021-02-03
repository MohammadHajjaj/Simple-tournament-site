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
	
	if(isset($_POST["register_team"]))
	{
		$_SESSION["teamname"] 			= $_POST["teamname"];
		$_SESSION["player1_discord"] 	= $_POST["player1_discord"];
			
		$_SESSION["player1_name"] 		= $_POST["player1_name"];
		$_SESSION["player1_steam"] 		= $_POST["player1_steam"];
				
		$_SESSION["player2_name"] 		= $_POST["player2_name"];
		$_SESSION["player2_steam"] 		= $_POST["player2_steam"];
				
		$_SESSION["player3_name"] 		= $_POST["player3_name"];
		$_SESSION["player3_steam"] 		= $_POST["player3_steam"];
				
		$_SESSION["player4_name"] 		= $_POST["player4_name"];
		$_SESSION["player4_steam"] 		= $_POST["player4_steam"];
				
		$_SESSION["player5_name"] 		= $_POST["player5_name"];
		$_SESSION["player5_steam"] 		= $_POST["player5_steam"];

		$_SESSION["player6_name"] 		= $_POST["player6_name"];
		$_SESSION["player6_steam"] 		= $_POST["player6_steam"];


			if(isUrlValid($_POST["player1_steam"]) AND isUrlValid($_POST["player2_steam"]) AND isUrlValid($_POST["player3_steam"]) AND 
				isUrlValid($_POST["player4_steam"]) AND isUrlValid($_POST["player5_steam"]))
			{
				if(registerTeam($_POST["teamname"], $_POST["player1_name"], $_POST["player1_steam"], $_POST["player1_discord"]))
				{
					registerUser($_POST["player1_name"], $_POST["player1_steam"], $_POST["teamname"]);
					registerUser($_POST["player2_name"], $_POST["player2_steam"], $_POST["teamname"]);
					registerUser($_POST["player3_name"], $_POST["player3_steam"], $_POST["teamname"]);
					registerUser($_POST["player4_name"], $_POST["player4_steam"], $_POST["teamname"]);
					registerUser($_POST["player5_name"], $_POST["player5_steam"], $_POST["teamname"]);

					if($_POST["player6_name"] != "" && $_POST["player6_steam"] != "")
						registerUser($_POST["player6_name"], $_POST["player6_steam"], $_POST["teamname"]);

					session_unset();
					$_SESSION["MSG"] = "created team " . $_POST["teamname"] . "!";
					$_SESSION["MSG_TYPE"] = "success";
				}
			}
			else
			{
				$_SESSION["MSG"] = "one or more member's steamurls were invalid!";
				$_SESSION["MSG_TYPE"] = "fail";
			}
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

			<?php 

			if(isset($_SESSION["MSG"]) && $_SESSION["MSG"] != "")
			{
				if($_SESSION["MSG_TYPE"] == "success") { ?> <div class = "container-message success"> <?php } else { ?> <div class = "container-message fail"> <?php } ?>

					<?php echo $_SESSION["MSG"]; ?>

				</div>

			<?php } $_SESSION["MSG"] = ""; $_SESSION["MSG_TYPE"] = ""; ?>

			<div class = "container-top"> 
				Countdown: <?php echo getCountdownTime(); ?>
			</div>

			<div class = "container-top"> 
				register
			</div>

			<div class = "container-bottom"> 

				<form method="post">

				<div> 
				<label> Team Name:
					<input class = "form" type="text" name="teamname" <?php if(isset($_SESSION["teamname"])) { echo "value = '" . $_SESSION["teamname"] . "'"; } ?> placeholder="team name" autocomplete="off" required>
				</label>
				<label>      Captain's Discord: 
					<input class = "form" type="text" name="player1_discord" <?php if(isset($_SESSION["player1_discord"])) { echo "value = '" . $_SESSION["player1_discord"] . "'"; } ?> placeholder="captain discord" autocomplete="off" required>
				</label>

				</div> 
				<label> Player 1 Name:
					<input class = "form spacer" type="text" name="player1_name" <?php if(isset($_SESSION["player1_name"])) { echo "value = '" . $_SESSION["player1_name"] . "'"; } ?> placeholder="captain name" autocomplete="off" required> 				
					</label> 
				<label> Player 1 Link:

					<input class = "form spacer" type="text" name="player1_steam" <?php if(isset($_SESSION["player1_steam"])) { echo "value = '" . $_SESSION["player1_steam"] . "'"; } ?> placeholder ="steamcommunity.com/id/example" autocomplete="off" required>
					</label> 

				<div> 
				<label> Player 2 Name:
					<input class = "form spacer" type="text" name="player2_name" <?php if(isset($_SESSION["player2_name"])) { echo "value = '" . $_SESSION["player2_name"] . "'"; } ?> placeholder="player 2 name" autocomplete="off" required> 
					<label> Player 2 Link:
					<input class = "form spacer" type="text" name="player2_steam" <?php if(isset($_SESSION["player2_steam"])) { echo "value = '" . $_SESSION["player2_steam"] . "'"; } ?> placeholder="steamcommunity.com/id/example" autocomplete="off" required>
				</div> 

				<div> 
				<label> Player 3 Name:
					<input class = "form spacer" type="text" name="player3_name" <?php if(isset($_SESSION["player3_name"])) { echo "value = '" . $_SESSION["player3_name"] . "'"; } ?> placeholder="player 3 name" autocomplete="off" required> 
					<label> Player 3 Link:

					<input class = "form spacer" type="text" name="player3_steam" <?php if(isset($_SESSION["player3_steam"])) { echo "value = '" . $_SESSION["player3_steam"] . "'"; } ?> placeholder="steamcommunity.com/id/example" autocomplete="off" required>

					</div> 
				<label> Player 4 Name:

					<input class = "form spacer" type="text" name="player4_name" <?php if(isset($_SESSION["player4_name"])) { echo "value = '" . $_SESSION["player4_name"] . "'"; } ?> placeholder="player 4 name" autocomplete="off" required> 
				<label> Player 4 Link:

					<input class = "form spacer" type="text" name="player4_steam" <?php if(isset($_SESSION["player4_steam"])) { echo "value = '" . $_SESSION["player4_steam"] . "'"; } ?> placeholder="steamcommunity.com/id/example" autocomplete="off" required>

					<div> 
				<label> Player 5 Name:

					<input class = "form spacer" type="text" name="player5_name" <?php if(isset($_SESSION["player5_name"])) { echo "value = '" . $_SESSION["player5_name"] . "'"; } ?> placeholder="player 5 name" autocomplete="off" required>
				<label> Player 5 Link:

					<input class = "form spacer" type="text" name="player5_steam" <?php if(isset($_SESSION["player5_steam"])) { echo "value = '" . $_SESSION["player5_steam"] . "'"; } ?> placeholder="steamcommunity.com/id/example" autocomplete="off" required>

					</div> 
				<label> Player 6 Name:

					<input class = "form spacer" type="text" name="player6_name" <?php if(isset($_SESSION["player6_name"])) { echo "value = '" . $_SESSION["player6_name"] . "'"; } ?> placeholder="player 6 name" autocomplete="off"> 
				<label> Player 6 Link:

					<input class = "form spacer" type="text" name="player6_steam" <?php if(isset($_SESSION["player6_steam"])) { echo "value = '" . $_SESSION["player6_steam"] . "'"; } ?> placeholder="steamcommunity.com/id/example" autocomplete="off">
					* not required
					<div> 

					<button class = "form spacer" name="register_team"> submit </button>

					</div> 
				</form>
			</div>

		</center>
	</body>
</html>