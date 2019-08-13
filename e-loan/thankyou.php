<?php
require "goodlife.php";


$check_session = GoodLife::check_session();

if (!$check_session) {
    header("Location: index.php");
}

?>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <style>
        .fade-enter-active,
        .fade-leave-active {
            transition: opacity .5s;
        }

        .fade-enter,
        .fade-leave-to

        /* .fade-leave-active below version 2.1.8 */
            {
            opacity: 0;
        }

        .section-header {
            background-color: #5cb85c;
            color: white;
            height: 140px;
        }

        .section-footer {
            background-color: #5cb85c;
            font-size: 50px;
            color: white;
            height: 100px;
        }

        .section-content {
            margin-top: 70px;
            height: 330px;
        }

        div.logo img {
            margin-top: 14px;
            width: 140px;
            margin-left: 20px;
        }

        .header-title {
            font-size: 100px;
        }

        .container p {
            font-size: 80px;
        }

        .button {
            height: 100px;
            width: 200px;
            font-size: 70px;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="row section-header">
            <div class="col-lg-3">
                <div class="logo">
                   
                </div>
            </div>
            <div class="col-lg-7">
                <p class="header-title">E-Loan</p>
            </div>
        </div>
        <div class="container">
            <div class="row section-content">
                <div class="col-lg-12">
                    <center>
                        <p>Thank you come again</p>
                        <button type="button" class="btn btn-success button" @click="okay()">OK</button>
                    </center>
                </div>
            </div>
        </div>
        <div class="section-footer">

        </div>
    </div>
</body>
<script src="vue.min.js"></script>
<script>
    // setTimeout(function() {
    //     window.location.href = "index.php";
    // }, 5000);

    new Vue({
        data() {
            return {

            }
        },
        methods: {
            okay() {
                window.location.href = "index.php";
            }
        },
        mounted() {
            setTimeout(function() {
                window.location.href = "index.php";
            }, 5000);
        }
    }).$mount("#app");
</script>

</html>