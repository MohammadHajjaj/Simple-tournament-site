<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/database.php';

session_start();
if(!isset($_SESSION["logged_in"]))
	$_SESSION["logged_in"] = false;

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

	if(isset($_POST["login"]))
	{
		$username = $_POST["login_name"];
		$password = $_POST["login_pass"];

		if($username == $ADMIN_ACCOUNT_NAME && $password == $ADMIN_ACCOUNT_PASSWORD)
		{
			// login
			$_SESSION["logged_in"] = true;
		}

	}

	if(isset($_POST["team_update"]))
		updateTeam($_POST["team_id"],  $_POST["team_newvalue"]);

	if(isset($_POST["countdown_update"]))
		updateCountdownTime($_POST["countdown_time"]);

	if(isset($_POST["delete_team"]))
		deleteTeam($_POST["delete_id"]);


	if(isset($_POST["champion_update"]))
		 updateChampion($_POST["champion_id"]);

	if(isset($_POST["champion_reset"]))
		 updateChampion(0);

	if(isset($_POST["avatars_update"]))
		updateAvatars();
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
			
			<?php if(isset($_SESSION["MSG"]) && $_SESSION["MSG"] != "")
			{
				if($_SESSION["MSG_TYPE"] == "success") { ?> <div class = "container-message success"> <?php } else { ?> <div class = "container-message fail"> <?php } ?>

					<?php echo $_SESSION["MSG"]; ?>

				</div>

			<?php } $_SESSION["MSG"] = ""; $_SESSION["MSG_TYPE"] = ""; ?>

			<div class = "container-top"> 
				Countdown: <?php echo getCountdownTime(); ?>
			</div>

			<?php if($_SESSION["logged_in"] == false) { ?>
			<div class = "container-top">
				login
			</div>

			<div class = "container-bottom">
				<form method = "post"> 

					<input class="form" type="text" name="login_name" placeholder="username" autocomplete="off" required="">

					<br>

					<input class="form spacer" type="password" name="login_pass" placeholder="password" autocomplete="off" required="">

					<br>

					<button class ="form spacer" name="login"> login </button>

				</form>
			</div>

			<?php } else { ?>

				<div class = "container-top">
					admin control panel
				</div>

				<div class="container-bottom">

					<form method="post">
						<fieldset class="form">
							<legend class="form"> count down </legend>

							<input class="form" type="text" name="countdown_time" placeholder="new unix timestamp" autocomplete="off" required="">
							<br>
							<button class ="form spacer" name="countdown_update"> update </button>

						</fieldset>
					</form>

					<form method="post">
						<fieldset class="form">
							<legend class="form"> champion </legend>

							<select class="form spacer" name="champion_id">

								<?php 

									$teams = $db->prepare("SELECT * FROM teams WHERE accepted=:accepted;");
									$teams->bindValue(":accepted", "1");
									$teams->execute();

									while($team = $teams->fetch())
										echo '<option value="' . $team["id"] . '"> ' . $team["teamname"] . ' </option>';
								?>

							</select>
							<br>

							<button class ="form spacer" name="champion_update"> update </button>
							<button class ="form spacer" name="champion_reset"> reset </button>
						</fieldset>
					</form>

					<form method="post">
						<fieldset class="form">
							<legend class="form"> teams </legend>

							<?php 

								$teams = $db->prepare("SELECT * FROM teams;");
								$teams->execute();

								while($team = $teams->fetch())
								{
									$members = $db->prepare("SELECT * FROM members WHERE teamname=:teamname;");
									$members->bindValue(":teamname", $team["teamname"]);
									$members->execute();

									$count = 0;
									echo "<center> <div class = 'teamcontainer spacer'>";
									echo "team: <b style = 'color: " . ($team["accepted"] == 1 ? '#b4e61e' : '#e61515') . " ;'>" . $team["teamname"] . "</b> [" . $team["registrant_ip"] . "] |";

									while($member = $members->fetch())
									{
										if($count == 6)
											break;
										
										if($count == 5)
										{
											if($member["username"] == "")
												break;
										}

										echo " [<a target = '_blank' href = '" . $member["steamurl"] . "'>  {$member['username']} </a> (". $member['steamid64'] .") ]";
										
										$count = $count + 1;
									}

									echo "</div> </center>";
								}

							?>

							<hr>

							<select class="form spacer" name="team_id">

								<?php 

									$teams = $db->prepare("SELECT * FROM teams;");
									$teams->execute();

									while($team = $teams->fetch())
										echo '<option value="' . $team["id"] . '"> ' . $team["teamname"] . ' </option>';
								?>

							</select>

							<br>

							<select class="form spacer" name="team_newvalue">
								<option value="0"> not accepted </option>
								<option value="1"> accepted </option>
							</select>

							<br>
							<button class ="form spacer" name="team_update"> update </button>

						</fieldset>
					</form>

					<form method="post">
						<fieldset class="form">
							<legend class="form"> update avatars </legend>
							<button class ="form spacer" name="avatars_update"> update </button>
						</fieldset>
					</form>

					<form method="post">
						<fieldset class="form">
							<legend class="form"> delete team </legend>

							<select class="form spacer" name="delete_id">

								<?php 

									$teams = $db->prepare("SELECT * FROM teams;");
									$teams->execute();

									while($team = $teams->fetch())
										echo '<option value="' . $team["id"] . '"> ' . $team["teamname"] . ' </option>';
								?>

							</select>
							<br>
							<button class ="form spacer" name="delete_team"> delete </button>

						</fieldset>
					</form>

				</div>

			<?php } ?>


		</center>

	</body>

</html>