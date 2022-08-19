<?php session_start(); ?>
<?php /*  姓名: 張甄真
    學號: 110816002
    刻出MicroSoft XP小算盤介面，並模擬小算盤的顏色及按鈕擺放位置等，提供使用者基本的整數加減乘除
    運算功能並輸出運算結果，且根據此小算盤沒有四則運算原則，故操作為兩兩數值直接運算。也提供C、CE、
    Del的按鍵功能(C清除全部運算、CE清除當次輸入、Del清除最後一個輸入的數字)，且在尚未輸入數字前按
    下任何功能鍵會提醒使用者輸入錯誤。
    此程式可支援加減乘除小數點運算，包括直接輸入包含小數點的數值做運算及運算結果可為小數等，能正常顯示
    以及搭配功能鍵使用。
    自評：
    1.支援整數加減乘除計算 (+80%)
    2.支援小數 (+20%)
    故自評為100分*/ ?>
<? header('Content-Type:text/html;charset=big5'); ?>
<?php
    function tonumber($Cnuminf,$counterinf){
        $tempinf=0;
        $GLOBALS["smallnum"]=false;
        $val=0;
        for($i=0;$i<$counterinf;$i++){ //判斷是否有小數點
            if($Cnuminf[$i]==11){
                $val=$i;
                $GLOBALS["smallnum"]=true; //若有，設為true
            }
        }
        //沒有小數點則做一般加總運算
        if($GLOBALS["smallnum"]==false){
            for($i=0;$i<$counterinf;$i++){
                $tempinf+=$Cnuminf[$i]*pow(10,$counterinf-$i-1);
            }
        }
        if($GLOBALS["smallnum"]==true){
            $Ctemp=0;
            for($i=0;$i<$val;$i++){ //非小數部分
                $tempinf+=$Cnuminf[$i]*pow(10,$val-$i-1);
            }
            for($i=$val+1;$i<$counterinf;$i++){ //小數部分
                $Ctemp+=1;
                $tempinf+=$Cnuminf[$i]/pow(10,$Ctemp);
                
            }
        }
        return $tempinf;
    }
    function out($x,$cop,$opinf){
        $tempino=$x;
        if($cop==1){
            $firstnum=$tempino;
            $_SESSION["sefirstnum"]=$firstnum;
        }
        if($cop>1){
            $secondnum=$tempino;
            $_SESSION["sesecondnum"]=$secondnum;
            $firstnum=$_SESSION["sefirstnum"];
            //運算
            if($opinf=="+"){
                $outc=$firstnum+$secondnum;
                $firstnum=$outc;
                $_SESSION["sefirstnum"]=$firstnum;
            }
            if($opinf=="-"){
                $outc=$firstnum-$secondnum;
                $firstnum=$outc;
                $_SESSION["sefirstnum"]=$firstnum;
            }
            if($opinf=="*"){
                $outc=$firstnum*$secondnum;
                $firstnum=$outc;
                $_SESSION["sefirstnum"]=$firstnum;
            }
            if($opinf=="/"){
                if($secondnum==0){ //分母為0則輸出error提醒
                    unset($_SESSION["secounter"]);
                    unset($_SESSION["secounterop"]);
                    unset($_SESSION["seop"]);
                    unset($_SESSION["seanswer"]);
                    unset($_SESSION["seCnum"]);
                    unset($_SESSION["sefirstnum"]);
                    unset($_SESSION["sesecondnum"]);
                    $outc="error";
                }
                else{
                    $outc=$firstnum/$secondnum;
                    $firstnum=$outc;
                    $_SESSION["sefirstnum"]=$firstnum;
                }

            }
        }
        return $outc;
    }

    global $smallnum; //是否有小數點運算
    $del=false; //Del按鍵需要重新存值
    //執行前需從SESSION取值，每一步操作需存回SESSION
    $del=$_SESSION["sedel"];
    $counter=$_SESSION["secounter"];
    $counterop=$_SESSION["secounterop"];
    $op=$_SESSION["seop"];
    $firstnum=$_SESSION["sefirstnum"];
    $secondnum=$_SESSION["sesecondnum"];
    $answer=$_SESSION["seanswer"];
    $Cnum=$_SESSION["seCnum"];
    $btnop=$_POST["op"];
    $btnn=$_POST["n"];
    if($counter==0){
        $answer="0.";
    }
    if(!is_null($btnn)){ //輸入為數字時存入陣列
        if($counter==0){
            $answer="";
        }
        $answer=$answer.$btnn;
        $_SESSION["seanswer"]=$answer;
        if($_SESSION["sedel"]==false){
            $Cnum[]=$btnn;
        }
        if($_SESSION["sedel"]==true){
            $Cnum[$counter]=$btnn;
            $del=false;
            $_SESSION["sedel"]=$del;
        }
        $_SESSION["seCnum"]=$Cnum;
        $counter+=1; //如果是數字才加1，且Cnum陣列從索引值0開始存放
        $_SESSION["secounter"]=$counter;
    }
    if(!is_null($btnop) && $btnop=="."){ //小數點.
        if($counter==0){ //錯誤判斷，第一個輸入不為小數點
            $answer="error";
        }
        else{
            $answer=$answer.".";
            $_SESSION["seanswer"]=$answer;
            $Cnum[$counter]=11; //若輸入小數點，則存一個標示在陣列裡讓函數判斷
            $_SESSION["seCnum"]=$Cnum;
            $counter+=1;
            $_SESSION["secounter"]=$counter;
        }
    }
    
    if(!is_null($btnop) && $btnop=="="){ //=
        if($counterop==0){
            $answer="error";
            unset($_SESSION["seanswer"]);
        }
        else{
            $counterop+=1;
            $_SESSION["secounterop"]=$counterop;
            $temp=tonumber($Cnum,$counter);
            $outcome=out($temp,$counterop,$op);
            $answer=$outcome;

            unset($_SESSION["secounter"]);
            unset($_SESSION["secounterop"]);
            unset($_SESSION["seop"]);
            unset($_SESSION["seanswer"]);
            unset($_SESSION["seCnum"]);
            unset($_SESSION["sefirstnum"]);
            unset($_SESSION["sesecondnum"]);
        }
    }

    if(!is_null($btnop) && $btnop=="+"){ //+
        if($counter==0){ //錯誤判斷，第一個輸入不為+
            $answer="error";
        }
        else{
            unset($_SESSION["seanswer"]);
            $answer="+";
            $counterop+=1;
            $_SESSION["secounterop"]=$counterop;
            $temp=tonumber($Cnum,$counter);
            out($temp,$counterop,$op);
            $op="+";
            $_SESSION["seop"]=$op;
            unset($_SESSION["seCnum"]);
            unset($_SESSION["secounter"]);
        }
    }
    
    if(!is_null($btnop) && $btnop=="-"){ //-
        unset($_SESSION["seanswer"]);
        $answer="-";
        $counterop+=1;
        $_SESSION["secounterop"]=$counterop;
        $temp=tonumber($Cnum,$counter);
            
        out($temp,$counterop,$op);
        $op="-";
        $_SESSION["seop"]=$op;
        unset($_SESSION["seCnum"]);
        unset($_SESSION["secounter"]);
        
    }
    if(!is_null($btnop) && $btnop=="*"){ //*
        if($counter==0){ //錯誤判斷，第一個輸入不為*
            $answer="error";
        }
        else{
            unset($_SESSION["seanswer"]);
            $answer="*";
            $counterop+=1;
            $_SESSION["secounterop"]=$counterop;
            $temp=tonumber($Cnum,$counter);
            out($temp,$counterop,$op);
            $op="*";
            $_SESSION["seop"]=$op;
            unset($_SESSION["seCnum"]);
            unset($_SESSION["secounter"]);
        }
    }
    if(!is_null($btnop) && $btnop=="/"){ //÷
        if($counter==0){ //錯誤判斷，第一個輸入不為÷
            $answer="error";
        }
        else{
            unset($_SESSION["seanswer"]);
            $answer="/";
            $counterop+=1;
            $_SESSION["secounterop"]=$counterop;
            $temp=tonumber($Cnum,$counter);
            out($temp,$counterop,$op);
            $op="/";
            $_SESSION["seop"]=$op;
            unset($_SESSION["seCnum"]);
            unset($_SESSION["secounter"]);
        }
    }

    if(!is_null($btnop) && $btnop=="C"){//清除所有計算結果
        unset($_SESSION["secounter"]);
        unset($_SESSION["secounterop"]);
        unset($_SESSION["seop"]);
        unset($_SESSION["seanswer"]);
        unset($_SESSION["seCnum"]);
        unset($_SESSION["sefirstnum"]);
        unset($_SESSION["sesecondnum"]);
        $answer="0.";
    }
    if(!is_null($btnop) && $btnop==="CE"){ //去掉當前輸入CE
        if($counter==0){ //如果未做任何輸入，表示不需要CE
            $answer="error";
        }
        else{
            $answer="";
            $_SESSION["seanswer"]=$answer;
            $counter=0;
            $_SESSION["secounter"]=$counter;
            unset($_SESSION["seCnum"]);
        }
        

    }
    if(!is_null($btnop) && $btnop==="Del"){ //去掉一個Del
        if($counter==0){ //如果未做任何輸入，表示不需要Del
            $answer="error";
        }
        else{ //重新組合顯示刪掉最後輸入的數字，並讓Cnum[刪除值的索引值]存的值為0
            $del=true;
            $_SESSION["sedel"]=$del;
            $answer="";
            for($i=0;$i<$counter-1;$i++){
                if($Cnum[$i]===11){ //判斷是否為小數點的情況
                    $answer=$answer.".";
                }
                else{
                    $answer=$answer.$Cnum[$i];
                }
            }
            $_SESSION["seanswer"]=$answer;
            $Cnum[$counter-1]="";
            $_SESSION["seCnum"]=$Cnum;
            $counter-=1;
            $_SESSION["secounter"]=$counter;
        }
    }
?>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>calculator</title>
    <style>
        table,input{
            width:50px;
            height:50px;
            font-size: 15px;
            background-color:rgb(245,245,245);
            
        }
        #outcomelab{
            width:200px;
            height:60px;
            font-size: 20px;
            border-width:2px;
            border-style: solid;
            text-align:right;
        }
        table{
            margin:auto;
            border-width:2px;
            border-style: solid;
        }
    </style>
</head>
<body>
    <form method=post action="cal-110816002.php">
        <table>
            <tr>
                <td colspan="4" id="outcomelab"><?php echo $answer; ?></td>
            </tr>
            <tr>
                <td><INPUT type=submit name="op" value="CE" ></td>
                <td><INPUT type=submit name="op" value="C" ></td>
                <td><INPUT type=submit name="op" value="Del" ></td>
                <td><INPUT type=submit name="op" value="/" ></td>
            </tr>
            <tr>
                <td><INPUT type=submit name="n" value="9" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="8" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="7" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="op" value="*" ></td>
            </tr>
            <tr>
                <td><INPUT type=submit name="n" value="6" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="5" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="4" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="op" value="-" ></td>
            </tr>
            <tr>
                <td><INPUT type=submit name="n" value="3" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="2" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="1" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="op" value="+" ></td>
            </tr>
            <tr>
                <td><INPUT type=submit name="x" value=" " style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="n" value="0" style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="op" value="." style="background-color:rgb(255,252,250);"></td>
                <td><INPUT type=submit name="op" value="=" style=" background-color:rgb(122,184,204);"></td>
            </tr>
        </table>
    </form>    
</body>
</html>