
<?php
session_start();
$uid=$_SESSION['userid'];
$utype= $_SESSION["usertype"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hiking";
$conn= new mysqli($servername, $username, $password, $dbname);
?>




<!DOCTYPE html>
<html lang="en">
<style>

</style>
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>pomato</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="css/responsive.css">
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
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<!-- body -->

<body class="main-layout ">
    <!-- loader  -->
    <div class="loader_bg">
        <div class="loader"><img src="images/loading.gif" alt="#" /></div>
    </div>
    <!-- end loader -->
    <!-- header -->
    <header>
        <!-- header inner -->
        <div class="header">

            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo">
                                    <a id="log" href="index.html"><img src="logo.jpg" alt="#"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <div class="menu-area">
                            <div class="limit-box">
                                <nav class="main-menu">
                                    <ul class="menu-area-main">
                                        <li class="active"> <a href="hiker_homepage.php">Home</a> </li>

                                       
                                       
                                                <li><a href='editHiker.php'>View Information</a></li>
                                                <li><a href='view_products.php'>Products</a></li>
                                                <li><a href='viewgroupsuser.php'>Hiking Groups</a></li>
                                                <li><a href='cart.php'>Cart</a></li>
                                                <li><a href='orderHikerAll.php'>Order</a></li>
                                                <li><a href='chatview.php'>Contact us</a></li>

                                            
                                        

                                        <li><a href="Logout.php">Logout</a></li>
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
  
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
$(document).ready(function(){
    $('#search').on('change',function(){
        var countryID = $(this).val();
        //console.log(countryID);
        if(countryID){
            $.ajax({
                type:'POST',
                url:'ajax.php',
                data:'IDD='+countryID,
                
                success:function(html){
                    $('#customers').html(html);
                   
                    //$('#city').html('<option value="">Select state first</option>'); 
                }
            }); 
           
        
           // $('#City').html('<option value="">Select countryyy first</option>');
          //  $('#city').html('<option value="">Select state first</option>'); 
        }
    });
});
</script> -->
<script>
function showResult(str) {
  // if (str.length==0) {
  //   document.getElementById("livesearch").innerHTML="";
  //   document.getElementById("livesearch").style.border="0px";
  //   return;
  // }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","ajax.php?q="+str,true);
  xmlhttp.send();
}
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
</head>

<form action="view_products.php" method="post">
Search Product: <input type="text" name="product" id="search" onkeyup="showResult(this.value)" placeholder="Search">

</form>
<body>

<?php


		// Create connection

		// Check connection
		if (!$conn) {
		  die("Connection failed: " . mysqli_connect_error());
		}
    $sqls = "SELECT * FROM products   ";

    $result5=mysqli_query($conn,$sqls);
    // if(isset($_POST["search"])) 
    // {     
    //     $product=$_POST['product'];
    // }
        $sql = "SELECT * FROM products ";
		    $result = mysqli_query($conn, $sql);
if( !$result ){
    echo 'Retrieval of data from Database Failed - #'.mysqli_errno().': '.mysqli_error();
  }else{
	    
?>
<div id="livesearch">
<table id="customers">
<thead>
  <tr>
    <th>Product name</th>
    <th>Price</th>
    <th>Desciption</th>  
    <th>Image</th>
    <th>Quantity</th>
    <th>Buy</th>
    <th>Average Rate</th>
    <th>Rating</th>
  </tr>
</thead>
  <tbody>
    <?php
    if($utype==4){
      
        while( $row = mysqli_fetch_assoc( $result ) ){ 
          $sql2 = "SELECT  SUM(Rate) FROM productsreview WHERE ProductID	 = ".$row['ID']." ";
              $rs = mysqli_query($conn, $sql2);
          if($rs->num_rows > 0){
            while($row2= $rs->fetch_assoc()){
              $sqll = "SELECT count(*) FROM productsreview WHERE ProductID	=".$row['ID']." ";
              $rss = mysqli_query($conn, $sqll);
               if($rss->num_rows > 0){
                 while($roww= $rss->fetch_assoc()){
                  if($roww["count(*)"]!=0){
                    $_SESSION['totall']=$row2['SUM(Rate)']/$roww["count(*)"];
                  }
                  else{
                      $_SESSION['totall']=0;
                  }
              }
          }
      }
    }
            
       // echo '<p>Average Rating: '.$_SESSION['total'].'</p>';
         echo "<tr><td>{$row['ProductName']}</td><td>{$row['Price']}</td><td>{$row['Description']}</td><td><img src='".$row['IMG']."' height='100' width='100'></td>
         <td><form action='view_products.php' method='post'><input type='number' name='quantity' placeholder='Enter quantity' min=1></form></td>
         <td><input type='submit' name='submit' value='Buy'>
         <input hidden type='text' name='id' value='".$row["ID"]."'>
         <input hidden type='text' name='p' value='".$row["Price"]."'></td>
         <td><p> ".$_SESSION['totall']."</p></td>
         <td><form action='productsStars.php' method='post'><input type='submit' name='rate' value='Rate'>
         <input hidden type='text' name='id' value='".$row["ID"]."'></form></td>
         </tr>\n";
        }
        if(isset($_POST['submit']))
        {  
            $pid=$_POST['id'];
            $quan=$_POST['quantity'];  
            $pprice=$_POST['p'];    
            $sql="SELECT * FROM cart_item WHERE product_ID='$pid'";
            $result=mysqli_query($conn, $sql);
            while( $row = mysqli_fetch_assoc( $result ) ){ 
                $productid=$row['product_ID'];
                $userid=$row['User_ID'];
                $quantity=$row['quantity'];
            }

            $total=$quan+$quantity;
            if($productid==$pid && $userid==$uid)
            {
              // echo "<script>alert('".$total."');</script>";
                $sqledit="UPDATE cart_item SET quantity='$total' WHERE product_ID='$productid'";
                $resulte=mysqli_query($conn, $sqledit);
            }
            else{
                $sql2="INSERT cart_item (product_ID,User_ID,quantity,price,IsDeleted) VALUES ($pid,$uid,$quan,$pprice,'0') ";
                $result2 = mysqli_query($conn, $sql2);
            }
           header('Location: cart.php');
        }
      }
      else if($utype==1){
        while( $row = mysqli_fetch_assoc( $result ) ){ 
         echo "<tr><td>{$row['ProductName']}</td><td>{$row['Price']}</td><td>{$row['Description']}</td><td><img src='".$row['IMG']."' height='100' width='100'></td>
         </tr>\n";
        }
      }
    ?>
  </tbody>
</table>
<!-- <script>
$("search").on("keyup",function()
{
  var value=$(this).val();
  $("table tr").each(function(result5)
  {
    if(result5!=0){
      var id=$(this).find("td:first").text();
      if(id.indexOf(value)!=0 && id.toLoweCase().indexOf(value.toLowerCase())<0){
        $(this).hide();
      }
      else{
        $(this).show();
      }
    }
  });
});
</script> -->

<?php
	    }
    mysqli_close($conn);

?>
<footer>
        <div id="contact" class="footer">
            <div class="container">
                <div class="row pdn-top-30">
                    <div class="col-md-12 ">
                        <div class="footer-box">
                            <div class="headinga">
                                <h3>Address</h3>
                                <span>Healing Center, 176 W Streetname,New York, NY 10014, US</span>
                                <p>(+71) 8522369417
                                    <br>demo@gmail.com</p>
                            </div>
                            <ul class="location_icon">
                                <li> <a href="#"><i class="fa fa-facebook-f"></i></a></li>
                                <li> <a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li> <a href="#"><i class="fa fa-instagram"></i></a></li>

                            </ul>
                            <div class="menu-bottom">
                                <ul class="link">
                                    <li> <a href="#">Home</a></li>
                                    <li> <a href="#">About</a></li>
                                    
                                    <li> <a href="#">Brand </a></li>
                                    <li> <a href="#">Specials  </a></li>
                                    <li> <a href="#"> Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </footer>
    <!-- end footer -->
    <!-- Javascript files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script src="js/plugin.js"></script>
    <!-- sidebar -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- javascript -->
    <script src="js/owl.carousel.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });

            $(".zoom").hover(function() {

                $(this).addClass('transition');
            }, function() {

                $(this).removeClass('transition');
            });
        });
    </script>
</div>

</html>