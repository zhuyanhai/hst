<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, target-densitydpi=device-dpi" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="full-screen" content="true">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-fullscreen" content="true">
    <meta name="360-fullscreen" content="true">
    <title>海上通</title>
    <style>
        html,body{
            background: #B5E7FF;
            overflow:hidden;
            margin:0;
            padding:0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .mainBox{
            position:relative;
            overflow:hidden;
            margin:0 auto;
            padding:0;
            _zoom:1;
            max-width: 1024px;
        }
        .btn{
            background:url(/img/anniu.png) no-repeat 100% 100%;
            background-size: 100% 100%;
            display: block;
            width: 7.4rem;
            height: 1.9rem;
            margin:0 auto;
        }
        .btnbox{
            margin: 0;
            padding: 0;
            margin-top: 2.2rem;
        }

        .txt{
            opacity:0;
        }

        .anim-opacity2{
            animation: 2s opacity2 1s 1 alternate forwards;
            -webkit-animation: 2s opacity2 1s 1 alternate forwards;
            -moz-animation: 2s opacity2 1s 1 alternate forwards;
        }
        @keyframes opacity2{
            0%{opacity:0}
            50%{opacity:.8;}
            100%{opacity:1;}
        }
        @-webkit-keyframes opacity2{
            0%{opacity:0}
            50%{opacity:.8;}
            100%{opacity:1;}
        }
        @-moz-keyframes opacity2{
            0%{opacity:0}
            50%{opacity:.8;}
            100%{opacity:1;}
        }

        .topup{
            position: absolute;right:-20rem;top:0rem;width:9rem
        }

        .anim-feiru1{
            animation: 1s feiru1 0s 1 alternate forwards;
            -webkit-animation: 1s feiru1 0s 1 alternate forwards;
            -moz-animation: 1s feiru1 0s 1 alternate forwards;
        }
        @keyframes feiru1{
            0%{right:-20rem}
            100%{right:0rem;}
        }
        @-webkit-keyframes feiru1{
            0%{right:-20rem}
            100%{right:0rem;}
        }
        @-moz-keyframes feiru1{
            0%{right:-20rem}
            100%{right:0rem;}
        }

        .topdown{
            position: absolute;left:-20rem;bottom:0rem;width:90%
        }

        .anim-feiru2{
            animation: 1s feiru2 0s 1 alternate forwards;
            -webkit-animation: 1s feiru2 0s 1 alternate forwards;
            -moz-animation: 1s feiru2 0s 1 alternate forwards;
        }
        @keyframes feiru2{
            0%{left:-20rem}
            100%{left:0rem;}
        }
        @-webkit-keyframes feiru2{
            0%{left:-20rem}
            100%{left:0rem;}
        }
        @-moz-keyframes feiru2{
            0%{left:-20rem}
            100%{left:0rem;}
        }

        @media screen and (min-width: 320px) {
            .txt{
                width: 16rem;height: 9rem;position: absolute;text-align: center;left:0;right:0;top: 30%;bottom: 0;margin: 0 auto;
            }
            .xinhao{
                position: absolute;right:-8rem;top:1rem;width:100%;
            }
            .logo{
                position: absolute;left:0.5rem;top:0.5rem;width:5rem
            }
            .leida{
                position: absolute;left:-2rem;top:43%;width:4rem
            }
            .chuan{
                position: absolute;left:0rem;bottom:-1rem;width:100%
            }
            .ftxt{
                font-size:1.7rem;padding:0;margin:0;
            }
            .stxt{
                font-size:0.7rem;padding:0;margin:0;margin-top:0.5rem;color:#5F6669;
            }
            .btnbox{
                margin-top: 2rem;
            }
        }

        @media screen and (min-height: 568px) {
            .txt{
                top: 28%;
            }
            .ftxt{
                font-size:1.8rem;
            }
            .stxt{
                font-size:0.75rem;
            }
            .leida{
                left:-2.5rem;top:42%;
            }
            .chuan{
                position: absolute;left:0rem;bottom:0rem;width:100%
            }
        }

        @media screen and (min-width: 360px) {
            .txt{
                top: 28%;
            }
            .ftxt{
                font-size:1.8rem;
            }
            .stxt{
                font-size:0.75rem;
            }
            .leida{
                position: absolute;left:-2rem;top:43%;width:4rem;
            }
            .chuan{
                position: absolute;left:0rem;bottom:0rem;width:100%
            }
        }

        @media screen and (min-width: 640px) {
            .txt{
                width: 18rem;height: 9rem;position: absolute;text-align: center;left:0;right:0;top: 28%;bottom: 0;margin: 0 auto;
            }
            .xinhao{
                position: absolute;right:-11rem;top:0rem;width:100%;
            }
            .topup{
                width:9.3rem
            }
            .logo{
                position: absolute;left:0.5rem;top:0.5rem;width:5rem
            }
            .leida{
                position: absolute;left:-2rem;top:41%;width:4rem
            }
            .chuan{
                position: absolute;left:0rem;bottom:-1rem;width:100%
            }
            .ftxt{
                font-size:1.8rem;padding:0;margin:0;
            }
            .stxt{
                font-size:0.75rem;padding:0;margin:0;margin-top:0.5rem;
            }
        }

        @media screen and (min-width: 960px) {
            .xinhao{
                position: absolute;right:-14rem;top:0rem;width:100%;
            }
            .topup{
                width:12rem
            }
            .logo{
                position: absolute;left:0.5rem;top:0.5rem;width:7rem
            }
        }

        @media screen and (min-width: 1024px) {
            .leida{
                position: absolute;left:-3rem;top:40%;width:6rem
            }
            .ftxt{
                font-size:2rem;padding:0;margin:0;
            }
            .stxt{
                font-size:0.9rem;padding:0;margin:0;margin-top:0.5rem;
            }
        }
    </style>
    <script>
        function isPC()
        {
            var userAgentInfo = navigator.userAgent;
            var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
            var flag = true;
            for (var v = 0; v < Agents.length; v++) {
                if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }
            }
            return flag;
        }
        function screenSize(isMonitorEvent)
        {
            if (window.outerWidth <= 640) {
                document.getElementsByTagName('html')[0].style.fontSize = window.outerWidth * 125 / 320 + "%";
            } else {
                document.getElementsByTagName('html')[0].style.fontSize = 640*125/320+"%";
            }
            if (isMonitorEvent === undefined) {
                window.onresize = function()
                {
                    screenSize('no');
                };
            }
        }
    </script>
</head>
<body>
<div id="mainBoxId" class="mainBox">
    <img src="/img/xinhao.png" alt="" class="xinhao" />
    <img src="/img/topup.png" alt="" class="topup anim-feiru1" />
    <img src="/img/logo.png" alt="" class="logo" />
    <img src="/img/leida.png" alt="" class="leida" />
    <img id="chuanId" src="/img/chuan.png" alt="" class="chuan" />
    <img id="topdownId" src="/img/topdown.png" alt="" class="topdown anim-feiru2" />
    <div class="txt anim-opacity2">
        <p class="ftxt">随时出海&nbsp;&nbsp;随时上网</p>
        <p class="stxt">让每一位海上人都能看新闻、聊微信</p>
        <p class="btnbox"><a class="btn"></a></p>
    </div>
</div>
<script>
    document.getElementById('mainBoxId').style.height = window.outerHeight+'px';
    var h = document.body.clientHeight;
    var w = document.body.clientWidth;
    console.log(h,w);
    if (isPC() && (h < w || (w/h < 1 && w/h > 0.8))) {
        document.getElementById('chuanId').style.bottom = -5+'rem';
        document.getElementById('topdownId').style.width = '67%';
    }
    screenSize();
</script>
</body>
</html>
