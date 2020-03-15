<!DOCTYPE html>
<html lang="en"><head>
    <title>bithub</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="File storage, upload/download."/>
	<link rel="stylesheet" href="../../assets/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
	html {height: 100vh;}
        body {background-repeat: no-repeat; background-attachment:fixed; font-size:10px; color:#777777; font-family:arial; text-align:center;}
        body:focus {outline: red;}
        h1 {font-size:64px; color:#555555;}
        p {width:320px; text-align:center; margin-left:auto;margin-right:auto; margin-top: 30px }
        a:link {color: #34536A;}
        a:hover {color: gold;}
	#prettyBox {background-color:rgba(255,255,255,0.7);font-size:1em;padding:1vw;box-shadow: inset 0px 1px 10px rgba(100,100,100,0.5); width: 80vw;}
        #fileList {
		font-size: 2em;
	}
        .item {
		    padding: .5em;
		flex: 1;
			background-color: rgba(50,50,50,0.2);
			margin: auto;
			margin-bottom: .5em;
			width: fit-content;
        }
		.item:hover {
			box-shadow: 3px 3px 4px #333;
		}
        .item > a > button   {
            margin-left: .5em;
        }
        #uploads {
            border: 2px dashed #34536A;
            display: inline-block;
            padding: 2em;
        }
    </style>
</head>

<body>
    <h1><a href="../../"><font color="#0850A4">bithub</font></a></h1>

    <h2>
  <div class="form-check mb-2 mr-sm-2">
    <input class="form-check-input" type="checkbox" id="inlineFormCheck">
    <label class="form-check-label" for="inlineFormCheck">
      Delete button
    </label>
  </div>
	</h2><br>
	
	<h3>write it down -- you'll need it later</h3>
    <div id="fileList" class="col-auto mb-3">
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
          if(isset($_GET['e'])){
                $file_name=$_GET['e'];
                $enc_file=urldecode($file_name);
                echo "editing: <form id='fileupdate' method='post'><input name='nn' value='$enc_file' /><br>";
                echo "<textarea form='fileupdate' name='bit_input' autofocus='autofocus' style='resize:both'>";
                echo file_get_contents("$enc_file");
                echo "</textarea><br><input class='btn btn-success' type='submit' value='save' name='bit_send'/><input class='btn btn-danger' type='button' value='discard' name='bit_send'/></form><br>";
            }
          if(isset($_POST['bit_send'])) {
                $file_name=$_GET['e'];
                $enc_file=urldecode($file_name);
                $new_name=$_POST['nn'];
                $bit_input=$_POST['bit_input'];
                $new_bit = fopen($enc_file, 'w');
                fwrite($new_bit, $bit_input);
                fclose($new_bit);
                rename($enc_file, $new_name);
                header('Location: index.php');
            }
          if(isset($_POST['nBname'])) {
              echo "you edited " . $_POST['nBname'];
                $bit_name=$_POST['nBname'];
                $bit_input=$_POST['nBinput'];
                touch($bit_name);
                $new_bit = fopen($bit_name, 'w');
                fwrite($new_bit, $bit_input);
                fclose($new_bit);
                header('Location: index.php');
          }
          foreach (glob("*") as $file) {
                $fileext = pathinfo($file, PATHINFO_EXTENSION);
                if ($fileext == TRUE OR $fileext == "py" OR $fileext == "sh" OR $fileext == "bat" OR $fileext == "js" OR $fileext == "htm" OR $fileext == "html") {
					echo "<div class='card'><a class='card-title' id='$file' href='files/$file'>" . $file . "</a><a class='card-body' href='?e=" . urlencode($file) ."'><button class='btn btn-primary'>edit</button></a></div>";
					continue;
				}
                echo "<div class='card' onclick=\"document.location.href='files/$file'\">
                <a id='$file' href='files/$file' download>" . $file . "</a></div>";
            }
        ?>
    </div>

    <h1 style="text-decoration: underline;font-style:italic;font-size: 4.20em;"> site backups </h1><h2>
      <?php
            chdir('../backups/');
            foreach (glob("*") as $file) {
                echo "<div class='card'><a class='card-title' href='backups/$file'>" . $file . "</a><a style='display:none;' href='?d=" . urlencode($file) ."'><button class='btn btn-secondary'>delete</button></a></div>";
            }
        ?>
    </h2>
    <br />
    <div class="btn" onclick="toggleBitBox()">create new bit</div>
    <br />
    <div id="newBit" style="display: none;">
        <form id='newBitForm' method='post'>
            <input name='nBname' type='text' placeholder='file name' />
            <br />
            <textarea form='newBitForm' name='nBinput' type='text' placeholder='file contents'></textarea>
            <br />
            <input class='btn' type='submit' />
        </form>
    </div>

    <h2>upload</h2>
    <form id="uploads" action="" method="post" enctype="multipart/form-data">
        <input type="file" id="file" name="files[]" multiple="multiple" accept="*" />
        <input type="submit" name="upload" value="Upload" />
    </form>
    
    <br>
    
    <footer style="flex:1;width:100%;">
	<form id="themeselector">
	  <input type="radio" id="clara" name="theme" value="clara"> light
	  <input type="radio" id="darko" name="theme" value="darko"> dark
	  <input type="radio" id="peachy" name="theme" value="peachy"> peachy<br>
	</form>
    </footer>
</body>
<script src="../../bknd/themer.js"></script>
<script>
function toggleBitBox(){
    let bitBox = document.getElementById("newBit");
    if(bitBox.style.display == "none") {
        bitBox.style.display = "inline-block";
    } else {
        bitBox.style.display = "none";
    } 
}    
</script>
</html>
