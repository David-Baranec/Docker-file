<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controller\PersonController;

include '../app/vendor/autoload.php';
require_once("config.php");
$output = "";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$Err = "Saved successfully";
###################################################################
if (isset($_SESSION['previous']) && !empty($_SESSION['previous'])) {
    if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
        session_destroy();
        //header('Location:index.php');
        ###################################################################
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // exec('octave-cli --eval "pkg load control;m1 = 2500; m2 = 320;k1 = 80000; k2 = 500000;b1 = 350; b2 = 15020;A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];C=[0 0 1 0]; D=[0 0];Aa = [[A,[0 0 0 0]\'];[C, 0]];Ba = [B;[0 0]];Ca = [C,0]; Da = D;K = [0 2.3e6 5e8 0 8e6];sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);t = 0:0.01:5;r =0.1;initX1=0; initX1d=0;initX2=0; initX2d=0;[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[initX1;initX1d;initX2;initX2d;0]);save out.txt y"', $output);
            //exec('octave-cli --eval "pkg load control;m1 = 2500; m2 = 320;k1 = 80000; k2 = 500000;b1 = 350; b2 = 15020;A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];C=[0 0 1 0]; D=[0 0];Aa = [[A,[0 0 0 0]\'];[C, 0]];Ba = [B;[0 0]];Ca = [C,0]; Da = D;K = [0 2.3e6 5e8 0 8e6];sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);t = 0:0.01:5;r =0.1;initX1=0; initX1d=0;initX2=0; initX2d=0;[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[initX1;initX1d;initX2;initX2d;0]);y"', $output);
            exec('octave-cli --eval "pkg load control;' . $_POST['input'] . 'save out.txt x"', $output);
            exec('octave-cli --eval "pkg load control;' . $_POST['input'] . 'save out1.txt y"', $output);
            exec('octave-cli --eval "pkg load control;' . $_POST['input'] . 'save out2.txt t"', $output);



            $sql = " INSERT INTO `orders`( user_id,date_date,text_text) VALUES (?,?,?);";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_SESSION['id'], date('Y-m-d H:i:s'), $_POST['input']]);
        }

?>
        <!doctype html>
        <html lang="en">

        <head>
            <title>Gallery</title>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <link rel="stylesheet" href="style.css">
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        </head>

        <body class="p-3 mb-2 bg-dark text-white">

            <div class="container">
                <h1 class="text-center ">Final project template</h1>
                <h2 class="text-right small">Signed in : <?php echo " " . $_SESSION['username']; ?></h2>
                <form action="show.php" method="post">
                    <div class="container">

                        <label for="input"><b>Input</b></label>
                        <textarea class="form-control" id="input" name="input" rows="7"></textarea>

                        <br> <br>
                        <button type="submit">Send</button>
                    </div>

                </form>
                <div class="container">
                    <textarea class="form-control" type="text" id="output" name="output" readonly rows="7"><?php
                                                                                                            $txt_file = fopen('out1.txt', 'r');
                                                                                                            $a = 1;
                                                                                                            while ($line = fgets($txt_file)) {
                                                                                                                echo $line;
                                                                                                                $a++;
                                                                                                            }
                                                                                                            fclose($txt_file);
                                                                                                            //$fh = fopen('out2.txt', 'w');
                                                                                                            //fclose($fh);
                                                                                                            if ($output != null) {
                                                                                                                // for($i=0;$i<$output.size();$i++){

                                                                                                                echo ($output[0]);
                                                                                                                //echo($output[$i]);
                                                                                                                // }
                                                                                                            }
                                                                                                            ?>
                                                                                                    </textarea>
                </div>
                <?php
                //var_dump($output);
                ?>
            </div>



            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        </body>

        </html>

    <?php
        ##############################################
    }
} else {
    ?>
    <!doctype html>
    <html lang="en">

    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body class="p-3 mb-2 bg-dark text-white">
        <br>
        <h3 class="text-white text-center">You are not allowed to use this content. Please continue to log in page </h3>
        <br>
        <div class="container">
            <div class="mybtn-right">
                <a href="index.php" class="btn btn-primary">Log in page</a>

            </div>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>

    </html>
<?php
}
##############################################
?>