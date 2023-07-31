<script>
    function buttonx(link) {
        var but = document.querySelectorAll("#but");
        for(let i = 0;i < but.length;i++) {
            but[i].disabled = true;
        }
        location.href=link;
    }
    function submitx() {
        var but = document.querySelector(".submit");
        var form = document.querySelector(".wrform");
        but.disabled = true;
        form.submit();
    }
</script>
<?php
    //세션
    session_start();
    //이미지업로드 소스
    require 'vendor/autoload.php';

    use Aws\S3\S3Client;
    use Aws\S3\Exception\S3Exception;

    $bucketName = 'codingdiary';
    $accessKeyId = 'AKIAXPHWSBHLWATBGOMJ';
    $secretAccessKey = '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';
    $region = 'eu-west-2';

    $awClient = new S3Client([
        'version' => 'latest',
        'region' => $region,
        'credentials' => [
            'key' => $accessKeyId,
            'secret' => $secretAccessKey,
        ],
    ]);
    //aws s3
    //sql 연결
    global $con;
        $con = mysqli_init();
        $con->ssl_set(NULL, NULL, "ssl/cacert.pem", NULL, NULL);
        $con->real_connect('gcp.connect.psdb.cloud', 't6iojlguel9xxxqhf4vb', 
        'pscale_pw_W9alE0Qda7YzLuuyDJxL2HiOdnHVppey6OycKPTZrFY', 'codi');
    //
    function form($val1,$val2,$submit,$hidden) {
        echo ' <input type="hidden" value="'.$hidden.'" name="file">
                <label id="title" for="title">TITLE </label>
                <input class="txbox" type="text" name="title" value="'.$val1.'"><br><br>
                <span id="explain">EXPLAIN</span><br>
                <textarea class="exbox" name="explain" rows="10" style="resize:none; font-size:20px;">'.$val2.'</textarea>
                <label for="file">
                    <div id="upload">File Upload</div>
                </label>
                <input id="file" type="file" name="file" accept=".png, .jpg, .jpeg, .gif">
                <button class="submit" type="submit" onclick="javascript:submitx()">'.$submit.'</button>
                ';
    }
    function button($link,$class,$val) {
        echo '<button id="but" onclick="javascript:buttonx(\''.$link.'\')" class="'.$class.'">'.$val.'</button>';
    }         
    function searchform($mode,$tab) {                
        echo "
                <form class='searchform' action='' method='get'>
                    <input type='hidden' name='mode' value=".$mode.">
                    <select name='kind'>
                        <option value='title'>Title</option>'
                        <option value='explain'>Explain</option>'
                        <option value='user'>User</option>'
                    </select>
                    <input id='scbox' type='text' name='find' required>
                    ".$tab."
                    <button id='searchbutton' type='submit'>Search</button>
                </form>
            ";
    }
    function loreform($link,$null,$submit,$re) {
        echo "
            <form class='register' action=$link method='post'>
                <ul class='reli'>
                    $null
                    <li>ID<br><input class='rein' type='text' name='id' pattern='^[a-z0-9]*$' minlength='4' maxlength='16' $re></li>
                    <li>PASSWORD<br><input class='rein' type='password' name='password' pattern='^[a-zA-Z0-9]*$' minlength='4' maxlength='32' $re></li>
                    <li><button type='submit'>$submit</button></li>
                </ul>
            </form>
            ";
    }
    function wrselect() {
        $data = file_get_contents('lib/select');
        $seldata = explode("\n", $data);
        for($i = 0;$i < count($seldata);$i += 2) {
            echo '<option value="'.substr($seldata[$i],0,4).'">' . $seldata[$i+1] . '</option>';                
        }
    }
    function insertsql($file) {
            global $con;
            mysqli_query($con,"insert into file (id, writer, title, exp, tab, link, time) 
            values ('".$_SESSION['activate']['id']."','".$_SESSION['activate']['name']."','"
            .mysqli_real_escape_string($con,$_POST['title'])."','"
            .mysqli_real_escape_string($con,str_replace('	','____________',str_replace(' ','___',$_POST['explain'])))."','"
            .$_POST['sel']."','".$file."',DATE_ADD(NOW(), INTERVAL 9 HOUR) )");
            header('Location: index.php');
        } 
    function sortsql() {
        global $con;
        $data = mysqli_query($con,"select * from file");
        $writer = "Unknown";
        $filear = array();
        while ($detail = mysqli_fetch_assoc($data)) {
            $filear[] = $detail;
        };
        rsort($filear);
        for ($i = 0;$i < count($filear);$i++){
            if (isset($filear[$i])) {
                $writer = $filear[$i]["writer"];
                    if (mb_strlen($filear[$i]["tab"].$filear[$i]["title"],'utf-8') > 16) {
                        $rena = mb_substr($filear[$i]["tab"].$filear[$i]["title"],0,16,'utf-8')."...";
                        echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.$filear[$i]["num"].'\'">'.htmlspecialchars($rena).
                        '<br><span class="smwr"><b>BY | </b> '.$writer.'</span></button>';
                    } else {
                        echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.$filear[$i]["num"].'\'">'.
                        htmlspecialchars($filear[$i]["tab"].$filear[$i]["title"]).'<br><span class="smwr"><b>BY | </b> '.$writer.'</span></button>';
                    }
            }
        }
    }
    function readsql($num) {
        global $con;
        $data = mysqli_fetch_assoc(mysqli_query($con,"select * from file where num = ".$num));
        $img = null;
        if($data["link"] != null) {
            $img = '<br><img class="putimg" src="'.$data["link"].'">';
        }
        echo '<div class="writer"><b>WRITER | </b>'.$data["writer"].'</div><br>
        <div id="reti">
        <div><b>TITLE</b></div><div>'.htmlspecialchars($data["title"]).'</div>
        </div><br>
        <div id="reex">'.str_replace("___",htmlspecialchars_decode("&nbsp;"),nl2br(htmlspecialchars($data["exp"]))).'</div>
        <div class="date"><b>DATE</b> : '.$data["time"].'</div>'.
        $img;
    }
    function updatesql($file,$link) {
        global $con;
        $lfile = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $lfile = "link = '".$file."' , ";
        }
        if(!empty($_POST['title']) && !empty($_POST['explain']) && 
        trim($_POST['title']) != '' && trim($_POST['explain']) != '') {
            mysqli_query($con,"update file set 
            title = '".mysqli_real_escape_string($con,$_POST['title'])."' ,
            exp = '".mysqli_real_escape_string($con,str_replace('	','____________',str_replace(' ','___',$_POST['explain'])))."' ,  
            tab = '".$_POST['sel']."' ,
            ".$lfile." 
            time = DATE_ADD(NOW(), INTERVAL 9 HOUR)
            where num = ".$link."");
            header('Location: index.php');
        } else {
            header('Location: index.php?mode=update&file='.$link.'&error=0');
        }
    }
    function trashsql() {
        $trash = 0;
        global $con;
        $data = mysqli_query($con,"select * from trash");
        echo '<form action="trdir.php" method="post">';
        while ($detail = mysqli_fetch_assoc($data)) {
            $trash = 1;
            echo '<button type="button" onclick="location.href=\'recovery.php?file='.$detail["num"].'\'">re</button>
            <input class="ckbox" type="checkbox" name="file[]" value='.$detail["num"].'>&nbsp'
            .$detail["writer"].'&nbsp;&nbsp;&nbsp;&nbsp;'.$detail["title"].'<br>';
        };
        if ($trash == 0) {
            echo '<div class="searchtext">Nothing Exists</div>';
        } else {
            if($_SESSION['activate']['id'] == 'admin') {
                echo'<input type="submit" value="Remove">';
            }
        }
        echo '</form>';
    }
    function searchsql() {
        $sw = 0;
        global $con;
        $kind = null;
        $tab = null;
        $find = null;
        if ($_GET['kind'] != null) {
            $find = "'%".$_GET['find']."%'";
            if ($_GET['kind'] == "title") {
                $kind = "title like ";
            } elseif ($_GET['kind'] == "explain") {
                $kind = "exp like ";
            } elseif ($_GET['kind'] == "user") {
                $kind = "writer like ";
            }
        }   
        if (isset($_GET['tab']) && $_GET['tab'] != null) {
            if ($_GET['kind'] != null) {
                $tab = "and tab like '%".$_GET['tab']."%'";
            } else {
                $tab = "tab like '%".$_GET['tab']."%'";
            }
        }
        $data = mysqli_query($con,"select * from file where ".$kind.$find.$tab);
        $writer = "Unknown";
        $filear = array();
        while ($detail = mysqli_fetch_assoc($data)) {
            $sw = 1;
            $filear[] = $detail;
        };
        rsort($filear);
        for ($i = 0;$i < count($filear);$i++){
            if (isset($filear[$i])) {
                $writer = $filear[$i]["writer"];
                    if (mb_strlen($filear[$i]["tab"].$filear[$i]["title"],'utf-8') > 16) {
                        $rena = mb_substr($filear[$i]["tab"].$filear[$i]["title"],0,16,'utf-8')."...";
                        echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.$filear[$i]["num"].'\'">'.htmlspecialchars($rena).
                        '<br><span class="smwr"><b>BY | </b> '.$writer.'</span></button>';
                    } else {
                        echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.$filear[$i]["num"].'\'">'.
                        htmlspecialchars($filear[$i]["tab"].$filear[$i]["title"]).'<br><span class="smwr"><b>BY | </b> '.$writer.'</span></button>';
                    }
            }
        }
        if ($sw == 0) {
            echo "<div class='searchtext'>Nothing Found</div>"; 
        } 
    } 
?>