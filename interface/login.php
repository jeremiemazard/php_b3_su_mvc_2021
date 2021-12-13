<html>
    <style>
        #form{
            position:relative;
top:200px;
left:900px;
width:500px;
        }
        #t1{
            width:500px;
        }
        #btn{
            background-color:#f44336; /* Green */
  border: none;
  color: white;
  padding: 16px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
  width:500px;
        }
        
        </style>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->

    <!-- bootstrap css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">

    <!-- fevicon -->
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    
</head>

<body>
 
<div class="header">

<div class="container">
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
            <div class="full">
                <div class="center-desk">
                    <div class="logo">
                        <a href="index.html"><img src="logo.jpg" alt="#"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
            <div class="menu-area">
                <div class="limit-box">
                    <nav class="main-menu">
                        <ul class="menu-area-main">
                            <li class="active"> <a href="index.html">Home</a> </li>
                            <li> <a href="about.html">About</a> </li>
                            <li><a href="brand.html">Contact Us</a></li>
                            <li><a href="singup.php">SignUp </a></li>
                            <li><a href="contact.html">Login</a></li>
                            <li class="last">
                                <a href="#"><img src="images/search_icon.png" alt="icon" /></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
       </div>
</div>
</div>
<!-- end header inner -->
</header>



        <form id="form"method="POST" action="">
                <h2>Email:</h2><br>
                <input id="t1"type="email" name="username" placeholder="username" /><br><br>
                <h2>Password:</h2><br>
                <input id="t1"type="password" name="password" placeholder="password" />  <br><br>
                <input id="btn"type="submit" value="Login" name="submit" /> 
                </form>
</body>
</html>
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hiking";
$conn = new mysqli($servername, $username, $password, $dbname);

if(isset($_POST["submit"])) 
    {     

        $name = $_POST["username"]; 
        $password = $_POST["password"]; 
        $result = mysqli_query($conn,"SELECT Email, Password,UserTypeID,ID FROM users WHERE Email = '".$name."' AND  Password = '".$password."'");

        if(mysqli_num_rows($result) == 0)
        {
            echo '<script>alert("Mail or Password in incorrect")
            window.location.herf="login.php"</script>';
        }
        else{
            echo 'success';
            
            while( $row = mysqli_fetch_assoc( $result ))
            {
                $type=$row['UserTypeID'];
                $id=$row['ID'];
                $_SESSION["usertype"] = $type;
                $_SESSION["userid"]=$id;
                
            }
            if($type==4)
            {
                header('Location: hiker_homepage.php');
            }
            elseif($type==1)
            {
                header('Location: admin_homepage.php');
            }
            elseif($type==2)
            {
                header('Location: auditorhomepage.php');
            }
            elseif($type==3)
            {
                header('Location: HR.php');
            }
            
        }
}

?>