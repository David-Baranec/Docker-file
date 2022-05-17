<?php
//session_start();
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

use App\Controller\PersonController;

include 'language.php';
include '../app/vendor/autoload.php';
require_once("config.php");
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$pswErr = '';
$genErr = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_POST["uname"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $new = false;
    if ($user == null) {
        $sql = " INSERT INTO `users`( username) VALUES (?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST["uname"]]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user == null) {
            $genErr =$lang['gen_err']; 
            $new = true;
        }
    }
    if ($_POST['psw'] == $API_key && $genErr == null) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['previous'] = 'index.php';
        header('Location:show.php');
    } else {
        $pswErr = $lang['psw_err'];
        if ($new == true) {
            $pswErr =  $lang['insert_psw'];
        }
        //header('Location:show.php');
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title><?php echo $lang['title']; ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="./js/script.js"></script>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body class="p-3 mb-2 bg-dark text-white">
    <h1 class="text-center"><?php echo $lang['title']; ?></h1>
    <div class="container">
        <form action="index.php" method="post">
            <div class="container">
                <label for="uname"><b><?php echo $lang['username']; ?></b></label>
                <input type="text" placeholder="<?php echo $lang['enter_username']; ?>" name="uname" required>
                <span class="error text-danger"><?php echo $genErr; ?></span>
                <br> <br>
                <label for="psw"><b><?php echo $lang['API_KEY']; ?></b></label>
                <input type="password" placeholder="<?php echo $lang['enter_API_KEY']; ?>" name="psw" autocomplete="off" required>
                <span class="error text-danger"> <?php echo $pswErr; ?></span>
                <br> <br>
                <button type="submit"><?php echo $lang['login']; ?></button>
            </div>

        </form>
        <?php

        $output = "";
        // exec('octave-cli --eval "pkg load control;m1 = 2500; m2 = 320;k1 = 80000; k2 = 500000;b1 = 350; b2 = 15020;A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];C=[0 0 1 0]; D=[0 0];Aa = [[A,[0 0 0 0]\'];[C, 0]];Ba = [B;[0 0]];Ca = [C,0]; Da = D;K = [0 2.3e6 5e8 0 8e6];sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);t = 0:0.01:5;r =0.1;initX1=0; initX1d=0;initX2=0; initX2d=0;[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[initX1;initX1d;initX2;initX2d;0]);save out.txt y"', $output);
        exec('octave-cli --eval "pkg load control;m1 = 2500; m2 = 320;k1 = 80000; k2 = 500000;b1 = 350; b2 = 15020;A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];C=[0 0 1 0]; D=[0 0];Aa = [[A,[0 0 0 0]\'];[C, 0]];Ba = [B;[0 0]];Ca = [C,0]; Da = D;K = [0 2.3e6 5e8 0 8e6];sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);t = 0:0.01:5;r =0.1;initX1=0; initX1d=0;initX2=0; initX2d=0;[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[initX1;initX1d;initX2;initX2d;0]);y"', $output);

        // var_dump($output);
        ?>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   
    <footer class="bg-dark text-center text-lg-start">
        <!-- Copyright -->
        <div class="container">
            <a href="index.php?lang=en"><?php echo $lang['lang_en']; ?></a>|
            <a href="index.php?lang=sk"><?php echo $lang['lang_sk']; ?></a>
        </div>
        <div class="text-center p-3" >
            © 2022 Copyright: Andrašovič, Baranec, Brosman, Teplanský
            
        </div>
        <!-- Copyright -->
    </footer>
</body>

</html>
<!doctype html>