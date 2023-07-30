<style>
    * {
        background-color:black;
        color:white;
    }
</style>
<a href="index.php">MAIN</a><br><br>
<form action='admin.php' method='post'>
<?php
require('lib/function.php');
global $con;
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