<?php
require "goodlife.php";

$session_id = (isset($_SESSION["session_id"])) ? $_SESSION["session_id"] : "";
$session_code = (isset($_SESSION["session_code"])) ? $_SESSION["session_code"] : "";

$session = array(
    "sess_id" =>  $session_id,
    "sess_code" => $session_code,
);


if (isset($_POST["verify"]) === TRUE) {
    $employee_id = $_POST["employee_id"];

    return GoodLife::verify($employee_id);
}

if (isset($_POST["login"]) === TRUE) {
    $employee_id = $_POST["employee_id"];

    return GoodLife::login($employee_id);
}

if (isset($_POST["update_mnumber"])) {

    $mobile_number = $_POST["mobile_number"];
    $idnumber = $_POST["idnumber"];

    return GoodLife::update_mobilenum($mobile_number, $idnumber);
}

$check_session = GoodLife::check_session();

if ($check_session) {
    header("Location: loan.php");
}

GoodLife::clear_session();

?>

<!doctype html>
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
        }

        .section-content {
            height: 415px;
        }

        .form-group {}

        .input-text {
            height: 80px;
            font-size: 80px;
        }

        .info p {
            font-size: 50px;
        }

        .section-login {
            font-size: 40px;
        }

        .section-info {
            font-size: 50px;
        }

        .section-info.btn {
            font-size: 40px;
        }

        .button {
            height: 70px;
            width: 150px;
            font-size: 40px;
        }

        div.logo img {
            margin-top: 14px;
            width: 140px;
            margin-left: 20px;
        }

        .btn-ctp {
            width: 320px;
            font-size: 40px;
        }

        .btn-ucn {
            width: 400px;
            font-size: 30px;
        }

        .btn-ok {
            margin-top: 20px;
        }

        .header-title {
            font-size: 100px;
        }

        .error-info {
            font-size: 30px;
            position: fixed;
            z-index: 1;
            top: 50px;
            left: 400px;
        }

        .loading-screen {
            position: fixed;
            z-index: 1;
            font-size: 80px;
            top: 170px;
            left: 500px;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="loading-screen" v-show="loading">
        </div>
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
            <div class="section-content">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="section-login">
                            <div v-bind:class="{ 'form-group': true, 'has-error': isvalid }">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="employee_id" class="control-label">Please enter your ID Number</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="text" type="number" step="1" min="0" @keydown="filterKey" @input="filterInput" name="employee_id" class="input-text form-control form-control-lg" v-model="employee_id">
                                        <div v-show="verfyToggle">
                                            <div class="col-lg-6">
                                                <button type="button" class="button btn btn-success btn-ok" name="verify" @click="verify()">OK</button>
                                            </div>
                                        </div>
                                        <transition name="fade">
                                            <div class="col-lg-6 error-info" v-show="error_display">
                                                <div class="alert alert-danger">{{ error_name }}</div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="section-info">
                            <div v-show="!verfyToggle">
                                <p>{{ status }}</p>
                                <p>{{ id }}</p>
                                <p>{{ fullname }}</p>
                                <div v-show="mobileno">
                                    <div v-show="edit">
                                        <p>{{ mobileno }} <button type="button" class="btn btn-danger button" @click="edit_mnumber()">Update</button></p>
                                    </div>
                                    <div v-show="!edit">
                                        <input type="text" v-model="updatemnumber">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-show="id">

                            <input type="hidden" name="employee_id" v-model="employee_id">

                            <div v-show="edit">
                                <button type="button" class="button btn btn-success btn-ctp" name="login" @click="login()">Click to Proceed</button>
                                <button type="button" class="button btn btn-default" name="verify" @click="cancel()">Cancel</button>
                            </div>
                            <div v-show="!edit">
                                <button type="button" class="button btn btn-info btn-ucn" @click="edit_mnumber_confirm()">Update Cellphone Number</button>
                                <button type="button" class="button btn btn-default" @click="edit_mnumber_cancel()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear-fix"></div>
    <div class="row section-footer">
        <div class="col-lg-12">
            <p class="text-center">Note: Please enter your id number</p>
        </div>
    </div>
    </div>

    <script src="vue.min.js"></script>
    <script src="vue-resource.min.js"></script>
    <script src="axios.min.js"></script>
    <script>
        new Vue({
            data() {
                return {
                    isvalid: false,
                    employee_id: "",
                    id: "",
                    fullname: "",
                    status: "",
                    mobileno: "",
                    verfyToggle: true,
                    edit: true,
                    updatemnumber: "",
                    error_name: "",
                    error_display: false,
                    loading: false,
                    error: {
                        name: ["empty fields. please enter numbers", "invalid input. please enter numbers", "id not found"],
                    }
                }
            },
            mounted() {
                //alert("hello world");
            },
            methods: {
                filterKey(e) {
                    const key = e.key;

                    // If is '.' key, stop it
                    if (key === '.')
                        return e.preventDefault();

                    // OPTIONAL
                    // If is 'e' key, stop it
                    if (key === 'e')
                        return e.preventDefault();
                },

                // This can also prevent copy + paste invalid character
                filterInput(e) {
                    var vm = this;
                    this.employee_id = this.employee_id.replace(/[^0-9]+/g, '');

                    if (!this.employee_id.replace(/[^0-9]+/g, '')) {
                        this.error_name = this.error.name[1];
                        this.error_display = true;
                        this.isvalid = true;

                        setTimeout(function() {
                            vm.isvalid = false;
                            vm.error_display = !vm.error_display;
                        }, 3000);
                    }
                },
                edit_mnumber_cancel() {
                    this.edit = !this.edit;
                    this.updatemnumber = "";
                },
                edit_mnumber_confirm() {
                    this.edit = !this.edit;

                    var vm = this;
                    var formData = new FormData();

                    var id = this.id;
                    id = id.split(" ");
                    id = id[1];

                    formData.append("mobile_number", this.updatemnumber);
                    formData.append('update_mnumber', true);
                    formData.append('idnumber', id);

                    var result = axios({
                        url: "index.php",
                        method: "post",
                        data: formData,
                        config: {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    }).then(function(response) {
                        vm.loading = true;

                        if (response.data) {
                            if (response.statusText === "OK" && response.status === 200 && response.data.status === "success") {
                                vm.loading = false;

                                console.log("update successfully");
                            };
                        }
                    });

                    this.verify();
                },
                edit_mnumber() {
                    this.edit = !this.edit;

                    this.updatemnumber = this.mobileno.split(" ");
                    this.updatemnumber = this.updatemnumber[1];
                },
                login() {
                    var vm = this;

                    var formData = new FormData();
                    formData.append("employee_id", this.employee_id);
                    formData.append('login', true);

                    var result = axios({
                        url: "index.php",
                        method: "post",
                        data: formData,
                        config: {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    }).then(function(response) {
                        vm.loading = true;

                        if (response.data === "success") {
                            vm.loading = false;

                            window.location.href = "loan.php";
                        }
                    });

                },

                verify() {
                    var vm = this;

                    if (!this.employee_id) {
                        this.error_display = true;
                        this.error_name = this.error.name[0];
                        vm.isvalid = true;

                        setTimeout(function() {
                            vm.isvalid = false;
                            vm.error_display = !vm.error_display;
                        }, 3000);

                    } else {

                        this.status = "";

                        var formData = new FormData();
                        formData.append("employee_id", this.employee_id);
                        formData.append("verify", true);

                        axios({
                            url: "index.php",
                            method: "post",
                            data: formData,
                            config: {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        }).then(function(response) {
                            vm.loading = true;

                            if (response.data) {
                                if (response.statusText === "OK" && response.status === 200 && response.data.status === "success") {
                                    vm.loading = false;

                                    vm.id = "ID: " + response.data.emp_id;
                                    vm.fullname = "Name: " + response.data.fullname;
                                    vm.mobileno = "Mobile: " + response.data.mobileno;

                                    vm.verfyToggle = false;
                                } else {
                                    vm.status = response.data.status;

                                    if (vm.status === "error") {
                                        vm.error_display = true;
                                        vm.error_name = vm.error.name[2];

                                        setTimeout(function() {
                                            vm.error_display = !vm.error_display;
                                        }, 3000);
                                    }
                                }
                            } else {
                                vm.loading = true;
                            }
                        });

                        this.emp_id = vm.id;
                        this.fullname = vm.fullname;
                        this.status = vm.status;
                        this.mobileno = vm.mobileno;
                        this.verfyToggle = vm.verfyToggle;
                    }

                },
                cancel() {
                    this.clear();

                    this.verfyToggle = true;
                },
                clear() {
                    this.employee_id = "";
                    this.id = "";
                    this.fullname = "";
                    this.status = "";
                    this.mobileno = "";

                },
            }
        }).$mount("#app");
    </script>
</body>

</html>