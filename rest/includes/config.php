<?php

    function init_dp(){
        $link = mysqli_connect('localhost','root','');
        mysqli_select_db($link,'flutter_blog');
        return $link;
    }
?>