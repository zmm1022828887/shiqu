<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
    .content{margin: 0 auto;width: 1030px;}
</style>
<div class="content">
    <canvas id="Mycanvas" width="1030" height="640"></canvas>
    <script>
        var text = "开场白......... |Mr.Righ ：有请主持人邱启明。（邱启明老师上场，观众开始欢呼.....）|邱启明：电视机前的朋友大家晚上好，欢迎收看本期的春纪《我们约会吧》，首先感谢我们湖南卫视的忠实粉丝们，| 姑娘们辛苦了，接下来让我们一起来看一下即将上场的男嘉宾他的爱情最主张，他不要什么 .........|上场男嘉宾：我不要比较胖的女生（咚咚......灭了三分之一）；我不要异地恋（咚咚......灭了五分之一 ）；我不要学|历比我高的女生（咚咚.....又灭了三分之一 ）；|邱启明：掌声欢迎男嘉宾登场。（男嘉宾开始登场，背景音乐响起，男嘉宾和邱启明老师握手，老师说了一声，你好，|欢迎你）。|上场男嘉宾：启明老师好，在场的所有女嘉宾好，我叫诸葛明，来自于湖南，我的交友宣言是：不以结婚为目的的谈恋|爱就是耍流氓，谢谢大家！|邱启明：好，让我们有请Mr.Right帮我们匹配出前30位女生。（匹配30位女生中..........）掌声欢迎这30位姑娘登场。|（30位女生登场......） 欢迎诸葛明同学（女生开始都亮灯）。|邱启明：来，找出你心仪的那一位女生，（男嘉宾选中了一个女生，滴.......），来，心仪的女生已经产生，让我们来|看一下理论上最匹配的女生。|Mr.Right：和男嘉宾最匹配的女生是***号***。|邱启明： 来自****的****，看看你俩的匹配分值80分，外形80分，兴趣爱好76分......，各位女嘉宾，请你们动动|手做出选择,,,,,,,,(咚咚咚 .......），来，一号女嘉宾灭灯理由.........|一号女嘉宾：男嘉宾，你好，你的驼背现象是不是有点严重，你的腰一直往前顶，感觉像一个小于号。|二号女嘉宾：男嘉宾，你好，感觉你是不是有多动症，感觉你的手一直在摇撼。（回一句：是代码敲多了，其实是紧张|了.....）|三号女嘉宾：男嘉宾，你好，由于我是想找一个有安全感的人，所以不能接受一个比我还瘦的人，不好意思，我只好灭灯了。|四号女嘉宾：男嘉宾，你好...........|咚咚 咚咚................最后全部灭了................ |上场男嘉宾：谢谢大家（伤感，成为了第二个宁财神了，无奈的离场）......";
        var arr = text.split("|");
        var line = 0;
        var timer = 0;
        var i = 0;
        var newText = "";
        function Typing(id) {
            var canvas = document.getElementById(id);
            if (canvas == null) {
                return false;
            }
            scrollit(id);
        }
        function scrollit(id) {
            newText = arr[line].slice(0, i++);
            var canvas = document.getElementById("Mycanvas");
            var context = canvas.getContext("2d");
            context.clearRect(0, 20 + line * 30, 600, 20 + 30 * (line + 1));
            context.font = "14px Verdana";
            context.textBaseline = "hanging";
            if (i > arr[line].length) {
                newText = arr[line].slice(0, arr[line].length);
                context.fillText(newText, 300, 20 + 30 * line);
                // 换行
                i = 0;
                line++;
                if (line < arr.length) {
                    clearTimeout(timer);
                    scrollit(id);
                }
            } else {
                context.fillText(newText, 300, 20 + 30 * line);
                timer = setTimeout(scrollit, 200);
            }

            context.beginPath();
            context.lineWidth="6";
            context.arc(100, 100, 15, 0, 2 * Math.PI);
            context.stroke();
            context.beginPath();
            context.moveTo(100, 115);
            context.lineTo(100, 190);
            context.lineTo(100, 190);
            context.stroke();
            context.beginPath();
            context.moveTo(100, 115);
            context.lineTo(70, 145);
            context.lineTo(50, 180);
            context.stroke();
            context.beginPath();
            context.moveTo(100, 115);
            context.lineTo(130, 145);
            context.lineTo(150, 180);
            context.stroke();
            context.beginPath();
            context.moveTo(100, 190);
            context.lineTo(70, 280);
            context.stroke();
            context.beginPath();
            context.moveTo(100, 190);
            context.lineTo(130, 280);
            context.stroke();
        }
        window.onload = function() {
            Typing("Mycanvas");
        }
    </script>
</div>