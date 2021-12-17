<?php
$msg = 'سوال خود را بپرس!';
$question = '';
$fa_name = 'حافظ';
$en_name = 'hafez';
$asami = file_get_contents('people.json');
$asami_decoded = json_decode($asami,true);
$message= file_get_contents('messages.txt');
$message_array = explode(PHP_EOL,$message);
$l= '';
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $en_name = $_POST["person"];
    $fa_name = $asami_decoded[$en_name];
    $question = $_POST["question"];
    if (substr_compare($question,"آیا" ,0,strlen("آیا"))===0 && (substr_compare($question, "؟", -strlen("؟")) ===0|| substr_compare($question, "?", -strlen("?")) === 0) )
    {
        $msg=$message_array[(intval(hash('crc32b',$en_name.$question),10) % 16)];
        $l = "پرسش: ";
    }
    else {
        $question = "";
        $l = '';
        $msg= "سوال درستی پرسیده نشده!";
    }
}
else {
    $msg = "سوال خود را بپرس!";
    $question = '';
    $en_name = array_rand($asami_decoded);
    $fa_name = $asami_decoded[$en_name];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>

<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="l"><?php
         echo $l
         ?></span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container" >
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
         
                foreach($asami_decoded as $esm => $esm_farsi){
                    if ($esm == $en_name)
                    {
                       echo '<option  value='."$esm".' selected> '."$esm_farsi".'</option>';
                    }
                    else
                    {
                        echo '<option value='."$esm".'> '."$esm_farsi".'</option>';
                    }
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>