<style>
    * {
        background-color:black;
        color:white;
    }
</style>
<a href="index.php">MAIN</a><br><br>
<form action='admin.php' method='post'>
<?php
$con = mysqli_init();
$con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
$con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
    'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
    session_start();
    if ($_SESSION['activate']['name'] == 'admin' && $_SESSION['activate']['id'] == 'admin') {
        $account = mysqli_fetch_all(mysqli_query($con,"select name,id from users"), MYSQLI_ASSOC);
        if (isset($_POST['id'])) {    
            for ($i = 0;$i < count($_POST['id']);$i++) {
                mysqli_query($con,"delete from users where id = '".$_POST['id'][$i]."'");
                header('location: admin.php');
            }
        }
        for ($i = 1;$i < count($account);$i++) {
            echo '<input class="ckbox" type="checkbox" name="id[]" 
            value='.$account[$i]['id'].'>'.$account[$i]['name'].' &nbsp; '.$account[$i]['id'].'<br>';
        }
        echo "<button type='submit'>Delete</button>";
    }
    $con->close();
?>
</form>

<?php
    if(isset($_POST['sel'])) {
        file_put_contents('lib/select',$_POST['sel']);
    }
?>
<form action='admin.php' method='post'>
<textarea name="sel" rows="10" style="resize:none; font-size:20px;">
    <?php
        echo file_get_contents('lib/select');
    ?>
</textarea><br>
<button type='submit'>Modify</button>
</form>