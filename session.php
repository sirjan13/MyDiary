<?php

session_start();

if (array_key_exists("userIn", $_COOKIE)) {
    
    $_SESSION['id'] = $_COOKIE['userIn'];
    
}

if (array_key_exists("id", $_SESSION)) {
    
    
    include 'connect.php';
    
    $query = "Select `content` from users WHERE email = '" . mysqli_real_escape_string($link, $_SESSION['id']) . "'";
    
    $row = mysqli_fetch_array(mysqli_query($link, $query));
    
    $diaryContent = $row['content'];
    
} else {
    
    header("location: index.php");
}

include 'header.php';

$today = date('m/d/y h:i:s A');

?>

<nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
    <a class="navbar-brand" href="#">Secret Diary</a>
    <div id="test">
        <a class="float-lg-right" href='index.php?Logout=1'><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Log Out</button></a>
    </div>
</nav>


<div class="container diaryContainer">
    
    <textarea id="diary" class="form-control"><?php echo $diaryContent.'&#10;'.$today.'-> ';?></textarea>
    
</div>

<?php

include 'footer.php';

?>

