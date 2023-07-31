<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">
    <meta name="HandheldFriendly" content="true" />
    <title>Coding Diary</title>
    <?php
        require('lib/function.php');
    ?>
    <link rel="stylesheet" href="lib/style.css" type="text/css">
<script>
    function submitForm() {
        document.getElementById("selform").submit();
    }
</script>
</head>
<body>
    <a href="index.php" class="title">Coding Diary</a>
    <?php
            if (!isset($_GET['mode'])) {
                if(isset($_SESSION['activate'])) {
                button('index.php?mode=trash','trbut','T');
                }
            } else {
                if ($_GET['mode'] == 'trash') {
                    if(isset($_SESSION['activate'])) {
                        if ($_SESSION['activate']['name'] == 'admin' && $_SESSION['activate']['id'] == 'admin') {
                            button('admin.php','trbut','A');
                        }
                    }
                }
            }
            if(isset($_SESSION['activate'])) {
                echo "<button class='user' onclick=\"location.href='index.php?tab=&mode=search&kind=user&find=".$_SESSION['activate']['id']."'\">
                    <span><b> USER</b></span><span>".$_SESSION['activate']['name']."</span>
                </button>";
            }
    ?>
    <br>
    <?php
        if (isset($_GET['mode'])) {
            if ($_GET['mode'] == 'write') {
                button("index.php","tpbut","Cancel");
            } else if ($_GET['mode'] == 'read') {
                global $con;
                button("javascript:history.back()","tpbut","Back"); echo ' &nbsp ';
                if(isset($_SESSION['activate'])) {
                $id = mysqli_fetch_assoc(mysqli_query($con,"select id from file where num = ".$_GET["file"]));
                if ($id['id'] == $_SESSION['activate']['id'] || $_SESSION['activate']['id'] == 'admin') {
                button("dedir.php?file=".$_GET['file']."","tpbut","Delete"); echo ' &nbsp ';
                button("index.php?mode=update&file=".$_GET['file']."","tpbut","Modify");
                }
                }
            } else if ($_GET['mode'] == 'update') {
                button("javascript:history.back()","tpbut","Back");
            } else if ($_GET['mode'] == 'search') {
                $kind = null;
                $find = null;
                $tab = null;
                $none = "None";
                if (isset($_GET['kind'])){
                    $kind = $_GET["kind"];
                    $find = $_GET["find"];
                }
                if (isset($_GET['tab'])) {
                    $tab = $_GET['tab'];
                    $data = file_get_contents('lib/select');
                    $seldata = explode("\n", $data);
                    for($i = 0;$i < count($seldata);$i += 2) {
                        if (preg_replace('/[^a-zA-Z]/', '', $_GET['tab']) == preg_replace('/[^a-zA-Z]/', '', $seldata[$i])) {
                            $none = $seldata[$i+1];
                        }
                    }
                } 
                button("javascript:history.back()","tpbut","Back"); echo ' &nbsp ';
                echo '<form id="selform" action="index.php" method="get">
                <input type="hidden" name="mode" value="search">
                <input type="hidden" name="kind" value="'.$kind.'">
                <input type="hidden" name="find" value="'.$find.'">
                <select class="sel" name="tab" onchange="submitForm();">
                <option selected disabled>'.$none.'</option>';
                wrselect();
                echo '</select></form>';
                searchform('search',"<input type='hidden' name='tab' value=".$tab.">");
            } else if ($_GET['mode'] == 'trash') {
                button("index.php","tpbut","Back");
            } else if ($_GET['mode'] == 'register') {
                button("index.php","tpbut","Back"); echo ' &nbsp ';
                button("index.php?mode=login","tpbut","Login");
            } else if ($_GET['mode'] == 'login') {
                button("index.php","tpbut","Back"); echo ' &nbsp ';
                button("index.php?mode=register","tpbut","Register");
            } 
        } else {
            if(isset($_SESSION['activate'])) {
                button("index.php?mode=write","tpbut","Write"); echo ' &nbsp ';
                button("outdir.php","tpbut","Logout");
            } else {
                button("index.php?mode=login","tpbut","Login");
            }
            echo '<form id="selform" action="index.php" method="get">
                <input type="hidden" name="mode" value="search">
                <input type="hidden" name="kind" value="">
                <input type="hidden" name="find" value="">
                <select class="sel" name="tab" onchange="submitForm();">
                <option selected disabled>None</option>';
            wrselect();
            echo '</select></form>';
            searchform('search',null);
        }
    ?>
    <fieldset style=" 
    <?php
    if (!isset($_GET['mode'])) {
        echo "text-align:center;";
    } else if ($_GET['mode'] == 'search' || $_GET['mode'] == 'user') {
        echo "text-align:center;";
    }
    ?>">
        <legend style="text-align: left;font-weight : bold; font-size: 25px; border: 2px solid black; padding:3px 5px 3px 5px; margin-top:3px; border-radius:5px;">
            <?php
                if(isset($_GET['mode'])) {
                    echo strtoupper($_GET['mode']);
                } else {
                    echo "LIST";
                }
            ?>
        </legend>
            <?php
                if(isset($_GET['error'])) {
                    if($_GET['error'] == 0){
                        echo "<div id='warning'>Forbidden : Invalid content</div><br>";
                    } else if ($_GET['error'] == 1) {
                    echo "<div id='warning'>Duplicate Exist</div><br>";
                    }
                } else {
                    null;
                }
            ?>
            <?php
                if(isset($_GET['mode'])) {
                    if($_GET['mode'] == 'write') {
                        if(isset($_SESSION['activate'])) {
                            $title = null;
                            $exp = null;
                            if(isset($_GET["trash"])) {
                                global $con;
                                $data = mysqli_fetch_assoc(mysqli_query($con,"select * from trash where num = ".$_GET["trash"]));
                                $title = urldecode($data["title"]);
                                $exp = str_replace('___',' ',urldecode($data["exp"]));
                            }
                            echo '<form class="wrform" action="redir.php" method="post" enctype="multipart/form-data"> 
                            <select name="sel">
                            <option value="[--]">None</option>';
                            wrselect();
                            echo '</select><br>';
                            form($title,$exp,"Post",null,);
                            echo '</form>';
                        }
                    } else if ($_GET['mode'] == 'read') {
                        readsql($_GET['file']);
                    } else if ($_GET['mode'] == 'update') {
                            global $con;
                            if(isset($_SESSION['activate'])) {
                                $file = mysqli_fetch_assoc(mysqli_query($con,"select * from file where num = ".$_GET["file"]));
                                if ($file["id"] == $_SESSION['activate']['id'] || $_SESSION['activate']['id'] == 'admin') {
                                    $val = "[--]";
                                    $txt = "None";
                                    $data = file_get_contents('lib/select');
                                    $seldata = explode("\n", $data);
                                    for($i = 0;$i < count($seldata);$i += 2) {
                                        if(substr($file['tab'],0,4) == substr($seldata[$i],0,4)) {
                                            $val = substr($seldata[$i],0,4);
                                            $txt = $seldata[$i+1] ;
                                        }           
                                    }
                                    echo '<form class="wrform" action="updir.php" method="post" enctype="multipart/form-data"> 
                                    <select name="sel">
                                    <option value="'.$val.'">'.$txt.'</option>';
                                    wrselect();
                                    echo '</select><br>';
                                    form($file["title"],str_replace('___',' ',$file["exp"]),"Modify",$_GET['file']);
                                    echo '</form>';
                                }
                            }
                    } else if ($_GET['mode'] == 'search') {
                        searchsql();
                    } else if ($_GET['mode'] == 'trash') {
                        if(isset($_SESSION['activate'])) {
                            trashsql();
                        }
                    } else if ($_GET['mode'] == 'register') {
                        loreform('indir.php',"<li>NICKNAME<br><input class='rein' type='text' name='nickname' 
                        pattern='^[a-zA-Zㄱ-힣0-9]*$' maxlength='16' minlength='2' required>
                        </li>",'REGISTER','required');
                    } else if ($_GET['mode'] == 'login') {
                        loreform('lodir.php',null,'LOGIN','required');
                    } 
                } else {
                    sortsql();
                }
            ?>
    </fieldset>
</body>
</html>