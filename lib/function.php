<?php
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
    function form($val1,$val2,$submit,$hidden,$img) {
        echo ' <input type="hidden" value="'.$hidden.'" name="file">
                <label id="title" for="title">TITLE </label>
                <input class="txbox" type="text" name="title" value="'.$val1.'"><br><br>
                <span id="explain">EXPLAIN</span><br>
                <textarea name="explain" rows="10" style="resize:none; font-size:20px;">'.$val2.'</textarea>
                '.$img.'
                <input class="submit" type="submit" value="'.$submit.'">';
    }
    function button($link,$class,$val) {
        echo '<button onclick="location.href=\''.$link.'\'" class="'.$class.'">'.$val.'</button>';
    }
    function read() {
        $date = file_get_contents('lib/fileinfo');
        $lines = explode("\n", $date);
        $time = date("Y-m-d H:i:s", filemtime('data/'.$_GET['file']));
        $writer = 'Unknown';
        for ($j = 0;$j < count($lines);$j++) {
            if (mb_substr($lines[$j],0,19,'utf-8') == $time) {
                $long = mb_strlen($lines[$j],'utf-8');
                $writer =  mb_substr($lines[$j],19,$long,'utf-8');
            }
        }
        $imgdata = urldecode(file_get_contents('data/'.$_GET['file']));
        $imglines = explode("\n", $imgdata);
        $imgsrc;
        for ($i = 0;$i < count($imglines);$i++) {
            if ($i == count($imglines) - 1) {
                $imgsrc = $imglines[$i];
            }
        }
        $inputimg = null;
        if (mb_substr($imgsrc,0,5,'utf-8') == 'https'){
            $inputimg = '<br><img class="putimg" src="'.$imgsrc.'">';
        }
        echo '<div class="writer"><b>WRITER | </b>'.$writer.'</div><br>
            <div id="reti">
            <div><b>TITLE</b></div><div>'.htmlspecialchars(urldecode($_GET['file'])).'</div>
            </div><br>
            <div id="reex">'.nl2br(htmlspecialchars(urldecode(
            file_get_contents('data/'.$_GET['file'])))).'</div>'.$inputimg.'
            ';
    }
    function abc() {
        $data = scandir('data/');
        if (isset($_GET['sort'])) {
        for($i = 0;$i < count($data);$i++) {
            if($data[$i] != '.' && $data[$i] != '..') {
                $date = file_get_contents('lib/fileinfo');
                $lines = explode("\n", $date);
                $writer = 'Unknown';
                for ($j = 0;$j < count($lines);$j++) {
                    $time = date("Y-m-d H:i:s", filemtime('data/'.$data[$i]));
                    if (mb_substr($lines[$j],0,19,'utf-8') == $time) {
                        $long = mb_strlen($lines[$j],'utf-8');
                        $writer =  mb_substr($lines[$j],19,$long,'utf-8');
                    }
                }
                if (mb_strlen(urldecode($data[$i]),'utf-8') > 16) {
                    $rena = mb_substr(urldecode($data[$i]),0,16,'utf-8')."...";
                    echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.htmlspecialchars($rena).
                    '<br><span class="smwr"><b>BY | </b> '.$writer.'</span></button>';
                } else {
                    echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.
                    htmlspecialchars(urldecode($data[$i])).'<br><span class="smwr"><b>BY | </b> '.$writer.'</span></button>';
                }
            }
        }
    } else {
        $timesort = array();
        for ($i = 0;$i < count($data);$i++) {
            if($data[$i] != '.' && $data[$i] != '..') {
                $time = date("Y-m-d H:i:s", filemtime('data/'.$data[$i]));
                array_push($timesort,$time.$data[$i]);
            }
        }
        rsort($timesort);
        for ($i = 0;$i < count($timesort);$i++) {
            $date = file_get_contents('lib/fileinfo');
            $lines = explode("\n", $date);
            $writer = 'Unknown';
            $time = mb_substr($timesort[$i],0,19,'utf-8');
            for ($j = 0;$j < count($lines);$j++) {
                if (mb_substr($lines[$j],0,19,'utf-8') == $time) {
                    $long = mb_strlen($lines[$j],'utf-8');
                    $writer =  mb_substr($lines[$j],19,$long,'utf-8');
                }
            }
            $timelen = mb_strlen($timesort[$i],'utf-8');
            $timedata = mb_substr($timesort[$i],19,$timelen,'utf-8');
            if (mb_strlen(urldecode($timedata),'utf-8') > 16) {
                $rena = mb_substr(urldecode($timedata),0,16,'utf-8')."...";
                echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($timedata).'\'">'
                .htmlspecialchars($rena).'<br><span class="smwr"><b>BY | </b> '.$writer.'</button>';
            } else {
                echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($timedata).'\'">'.
                htmlspecialchars(urldecode($timedata)).'<br><span class="smwr"><b>BY | </b> '.$writer.'</button>';
            }
        }
    }
    }
    function direct($rename,$link,$sel) {
        if(!empty($_POST['title']) && !empty($_POST['explain']) && 
        trim($_POST['title']) != '' && trim($_POST['explain']) != '') {
            $rename;
            file_put_contents('data/'.$sel.urlencode($_POST['title']),urlencode($_POST['explain']));
            file_put_contents('lib/fileinfo',date("Y-m-d H:i:s").$_SESSION['activate']['id']."\n", FILE_APPEND);
            header('Location: index.php');
        } else {
            header('Location: index.php'.$link.'&error=0');
        }
    }
    function findsys() {
        if(isset($_GET['find']) && trim($_GET['find']) != '') {
            $data = scandir('data/');
            $on = 0;
            for ($i = 0; $i < count($data); $i++) {
                if($data[$i] != '.' && $data[$i] != '..') {
                    $find = $_GET['find'];
                    if (isset($_GET['sel'])) {
                        $find = mb_substr($_GET['find'],0,4,'utf-8');
                    }
                    if (strpos($data[$i],urlencode($find)) !== false) {
                        $on = 1;
                        if (mb_strlen(urldecode($data[$i]),'utf-8') > 16) {
                            $rena = mb_substr(urldecode($data[$i]),0,16,'utf-8')."...";
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.htmlspecialchars($rena).'</button>';
                        } else {
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($data[$i]).'\'">'.
                            htmlspecialchars(urldecode($data[$i])).'</button>';
                        }
                    }
                }
            }
            if ($on == 0) {
                echo "<div class='searchtext'>Nothing Found</div>"; 
            } 
        } else {
            echo "<div class='searchtext'>SEARCH IT...</div>";
        }
    }
    function trash() {
        $trash = 0;
        $data = scandir('trash/');
            echo '<form action="trdir.php" method="post">';
            for($i = 0;$i < count($data);$i++) {
                if ($data[$i] != '.' && $data[$i] != '..') {
                $trash = 1;
                echo '<input class="ckbox" type="checkbox" name="file[]" value='.$data[$i].'>'.urldecode($data[$i]).'<br>';
                }
            }
                if ($trash == 0) {
                echo '<div class="searchtext">Nothing Exists</div>';
                } else {
                    echo '<input type="submit" name="re" value="Recovery"> &nbsp; ';
                    if($_SESSION['activate']['id'] == 'admin') {
                        echo'<input type="submit" name="re" value="Remove">';
                    }
                }
        echo '</form>';
                    }
                    
    function searchform($mode,$submit) {                
        echo "
                <form class='searchform' action='index.php' method='get'>
                    <input type='hidden' name='mode' value=$mode>
                    <input id='scbox' type='text' name='find'>
                    <button id='searchbutton' type='submit'>$submit</button>
                </form>
            ";
    }
    function loreform($link,$null,$submit,$re) {
        echo "
            <form class='register' action=$link method='post'>
                <ul class='reli'>
                    $null
                    <li>ID<br><input type='text' name='id' pattern='^[a-z0-9]*$' minlength='4' maxlength='16' $re></li>
                    <li>PASSWORD<br><input type='password' name='password' pattern='^[a-zA-Z0-9]*$' minlength='4' maxlength='32' $re></li>
                    <li><button type='submit'>$submit</button></li>
                </ul>
            </form>
            ";
    }
    function wrselect() {
        $data = file_get_contents('lib/select');
        $seldata = explode("\n", $data);
        for($i = 0;$i < count($seldata);$i += 2) {
            echo '<option value="' . $seldata[$i] . '">' . $seldata[$i+1] . '</option>';                
        }
    }
    function user() {
         if(isset($_GET['find']) && trim($_GET['find']) != '') {
        $date = file_get_contents('lib/fileinfo');
        $lines = explode("\n", $date);
        $file = scandir('data/');
        for ($j = 0;$j < count($lines);$j++) {
            for ($k = 0;$k < count($file);$k++){
                if (mb_substr($lines[$j],0,19,'utf-8') == date("Y-m-d H:i:s", filemtime('data/'.$file[$k]))) {
                    $len = mb_strlen($lines[$j]);
                    $name = mb_substr($lines[$j],19,$len,'utf-8');
                    if ($_GET['find'] == $name && $file[$k] != '.' && $file[$k] != '..') {
                        if (mb_strlen(urldecode(http://jcaixoztyb.eu11.qoddiapp.com/),'utf-8') > 16) {
                            $rena = mb_substr(urldecode($file[$k]),0,16,'utf-8')."...";
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($file[$k]).'\'">'
                            .htmlspecialchars($rena).'</button>';
                        } else {
                            echo '<button class="box" onclick="location.href=\'index.php?mode=read&file='.urlencode($file[$k]).'\'">'.
                            htmlspecialchars(urldecode($file[$k])).'</button>';
                        }
                    }
                }
            }
        }
    } else {
        echo "<div class='searchtext'>SEARCH USER ID</div>";
    }
}
?>
