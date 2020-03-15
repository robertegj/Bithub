<!DOCTYPE html>
<html lang="en"><head>
    <title>bithub</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="This is a way different page."/>
    <style type="text/css">
		html {height: 100vh;}
        body {background-repeat: no-repeat; background-attachment:fixed; font-size:10px; color:#777777; font-family:arial; text-align:center;}
        h1 {font-size:64px; color:#555555; margin: 70px 0 50px 0;}
        p {width:320px; text-align:center; margin-left:auto;margin-right:auto; margin-top: 30px }
        div {width:320px; text-align:center; margin-left:auto;margin-right:auto;}
        a:link {color: #34536A;}
        a:visited {color: #34536A;}
        a:active {color: #34536A;}
        a:hover {color: gold;}
		#prettyBox {background-color:rgba(255,10,10,0.7);font-size:1em;padding:1vw;box-shadow: inset 0px 1px 10px rgba(100,100,100,0.5);}
    </style>
</head>

<body>

<h1><a href="../../"><font color="#0850A4">bithub</font></a></h1>

<h2>lessons <a href="index.php">forgotten</a></h2><br><h3>write it down -- you'll need it later</h3>
<div id="prettyBox">
	<h2 id="txt">
	<?php
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		chdir('files/');
		$max_file_size = 1024*10000000; //a lot of kb
		$path = ""; // Upload directory
		$count = 0;
		$files = 0;

	  if(isset($_POST["upload"]) and $_SERVER['REQUEST_METHOD'] == "POST"){
			// Loop $_FILES to execute all files
			foreach ($_FILES['files']['name'] as $f => $name) {
				if ($_FILES['files']['error'][$f] == 4) {
					continue; // skip file if error found
				}
				if ($_FILES['files']['error'][$f] == 0) {
					if ($_FILES['files']['size'][$f] > $max_file_size) {
						$message[] = "$name is too large!.";
						continue; // skip big files
					}
					else{ // no error found! move uploaded files
						if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path.$name))
						$count++; // number of successfully uploaded file
					}
				}
			}
			// echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";
		}

	  if(isset($_POST['delete'])){
			$file_name=$_POST['delete'];
			$file_name=urldecode($file_name);
			unlink($file_name);
			echo "<mark>'" . $file_name . "' has been deleted. </mark><br>";
			echo "<meta http-equiv='refresh' content='2;URL=index.php'>";
		}
      if(isset($_GET['d'])){
            $file_name=$_GET['d'];
            $enc_file=urldecode($file_name);
            unlink($enc_file);
            echo "<mark>'" . $file_name . "' has been deleted. </mark><br>";
            echo "<meta http-equiv='refresh' content='2;URL=index.php'>";
		}
      if(isset($_GET['killall'])){
            $files = glob('*');
            foreach($files as $file) {
                unlink($file);
            }
            echo "<mark>all of it has been deleted. </mark><br>";
            echo "<meta http-equiv='refresh' content='2;URL=index.php'>";
		}
      if(isset($_GET['e'])){
			$file_name=$_GET['e'];
			$enc_file=urldecode($file_name);
			echo "editing: " . $enc_file . "<br>";
			echo "<textarea form='fileupdate' name='chat_input'>";
			echo file_get_contents("$enc_file");
			echo "</textarea><br><form id='fileupdate' method='post'><input type='submit' value='save' name='chat_send'/></form>";
		}
      if(isset($_POST['chat_send'])) {
			$chat_input=$_POST['chat_input'];
			$new_chat = fopen($enc_file, 'w');
			fwrite($new_chat, $chat_input);
			fclose($new_chat);
			echo "wow";
			// header('Location: index.php');
        }
	  foreach (glob("*") as $file) {
			$fileext = pathinfo($file, PATHINFO_EXTENSION);
			if ($fileext == "txt") {
            echo "<div class='item'><a href='files/$file'>" . $file . "</a> <a href='?e=" . urlencode($file) ."'><button>edit</button></a> <a href='?d=" . urlencode($file) ."'><button>delete</button></a></div>";
            continue;
          }
		echo "<div class='item'><a href='files/$file'>" . $file . "</a> <a href='?d=" . urlencode($file) ."'><button>delete</button></a></div>";
		}
		?>
	</h2>
</div>
<br><br>
<a href='?killall=1'><button>delete all</button></a>
<h1 style="text-decoration: underline;font-style:italic;font-size: 4.20em;"> site backups </h1><h2>
  <?php
			chdir('../backups/');
			foreach (glob("*") as $file) {
				echo "<div class='item'><a href='backups/$file'>" . $file . "</a> <a style='display:none;' href='?d=" . urlencode($file) ."'><button>delete</button></a></div>";
			}
	?>
</h2>

<br>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" id="file" name="files[]" multiple="multiple" accept="*" />
<input type="submit" name="upload" value="Upload" />
</form>
<br>
<form action="" method="post">
<input type="text" name="delete" />
<input type="submit" value="Delete" />
</form>
<br>
    <footer style="flex:1;width:100%;">
        <a href="http://anicyber.com/">Powered by Anicyber</a>
	<form id="themeselector">
	  <input type="radio" id="clara" name="theme" value="clara"> light
	  <input type="radio" id="darko" name="theme" value="darko"> dark
	  <input type="radio" id="peachy" name="theme" value="peachy"> peachy<br>
	</form>
    </footer>
</body>
<script src="../../bknd/themer.js"></script>
</html>
