<?php
require "goodlife.php";

$session = array(
 "sess_id" => ($_SESSION["session_id"]),
 "sess_code" => $_SESSION["session_code"],
);

$session = ($session) ? $session : "";

//print_r($session);

$check_session = GoodLife::check_session();

if (!$check_session) {
 header("Location: index.php");
}


if (isset($_POST["logout"]) === TRUE) {
 GoodLife::clear_session_logout();
}

if (isset($_POST["firstcomaker"]) === TRUE) {
 $firstcoid = $_POST["firstcoid"];

 return GoodLife::first_comaker($firstcoid);
}

if (isset($_POST["secondcomaker"]) === TRUE) {
 $secondcoid = $_POST["secondcoid"];

 return GoodLife::second_comaker($secondcoid);
}

if (isset($_POST["presc_info"]) === TRUE) {
 return GoodLife::prescribe_info($session["sess_id"]);
}

if (isset($_POST["compute"]) === TRUE) {

 $data = array(
  "terms" => $_POST["terms"],
  "amount" => $_POST["amount"],
  "purpose" => $_POST["purpose"],
  "comaker_first" => $_POST["comaker_first"],
  "comaker_second" => $_POST["comaker_second"]
 );

 return GoodLife::compute($data);
}

if (isset($_POST["checkcomaker"]) === TRUE) {
 $fcomaker = $_POST["fcomaker"];
 $scomaker = $_POST["scomaker"];

 return GoodLife::check_comaker($fcomaker, $scomaker);
}

if (isset($_POST["save"]) === TRUE) {

 $data = array(
  "id" => $_POST["id"],
  "amount" => $_POST["amount"],
  "terms" => $_POST["terms"],
  "purpose" => $_POST["purpose"],
  "fcm_id" => $_POST["fcm_id"],
  "scm_id" => $_POST["scm_id"],
  "fcm_name" => $_POST["fcm_name"],
  "scm_name" => $_POST["scm_name"],
  "s_amount" => $_POST["s_amount"]
 );

 return GoodLife::save($data);
}

?>

<html>

<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" type="text/css" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
 <link rel="stylesheet" type="text/css" href="bootstrap-3.3.7-dist/css/bootstrap-theme.css">
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
   font-size: 25px;
   color: white;
   height: 150px;
   padding-top: 20px;
   padding-left: 10px;
  }

  .header-title {
   font-size: 70px;
  }

  .section-content {
   height: 390px;
   margin-top: 20px;
   margin-left: 20px;
   margin-right: 20px;
  }

  .title {}

  .section-info {}

  div.logo img {
   width: 140px;
  }

  .p-pla {
   background-color: green;
   margin-right: 50px;
   padding-left: 20px;
  }

  .section-footer {
   background-color: #5cb85c;
   font-size: 20px;
   height: 80px;
   color: white;
  }

  .input-text {
   font-size: 30px;
   height: 60px;
  }

  .label {
   font-size: 20px;
   color: black;
  }

  .select {
   font-size: 30px;
   height: 60px;
  }

  .button {
   font-size: 30px;
   height: 60px;
  }

  .button-logout {
   font-size: 30px;
   height: 60px;
  }

  .button-compute {
   font-size: 20px;
   height: 60px;
  }

  .content {
   font-size: 30px;
  }

  .title {
   font-size: 30px;
  }

  .error-info {
   font-size: 30px;
   position: fixed;
   z-index: 1;
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
  <!-- Modal -->
  <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
     <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLongTitle"></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true">&times;</span>
      </button>
     </div>
     <div class="modal-body">
      <h5>Save this data?</h5>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" @click="save()">Save changes</button>
     </div>
    </div>
   </div>
  </div>


  <div class="loading-screen" v-show="loading">
   <img src="loading.gif">
  </div>

  <div class="row section-header">
   <div class="col-lg-3">
    <div class="logo">
     <img src="logo.jpg">
    </div>
   </div>
   <div class="col-lg-6 header-title">
    Goodlife E-Loan
    <transition name="fade">
     <div class="error-info" v-show="error_display">
      <div class="alert alert-danger">{{ error_name }}</div>
     </div>
    </transition>
   </div>
   <div class="col-lg-3">
    ID: <?php echo $_SESSION["session_id"]; ?>
    <div class="p-pla">
     <p>Available Loanable Amount: <strong>{{ PLA }}</strong></p>
    </div>
   </div>
  </div>

  <div class="section-content">

   <div class="row">
    <div class="col-lg-7">
     <div class="row">
      <div class="col-lg-12">
       <div v-bind:class="{'row form-group' : true, 'has-error': error_input.amount}">
        <div class="col-lg-3">
         <label class="control-label label" for="amount">Enter amount</label>
        </div>
        <div class="col-lg-7">
         <input type="text" step="1" min="0" @keydown="filterKey" @input="filterInput" id="amount" v-model="amount" class="form-control input-text" name="amount">
        </div>
       </div>
       <div v-bind:class="{'row form-group' : true, 'has-error': error_input.terms}">
        <div class="col-lg-3">
         <label class="control-label label" for="terms">Select Terms</label>
        </div>
        <div class="col-lg-7">
         <div class="row">
          <div class="col-lg-4">
           <select name="terms" v-model="terms" id="terms" class="form-control select" id="terms">
            <option v-for="(value, index) in select_terms">{{ value }}</option>
           </select>
          </div>
         </div>
        </div>
        <div class="col-lg-2">
         <button type="submit" class="btn btn-success button-compute" name="compute" @click="compute_loan()">Compute</button>
        </div>
       </div>
      </div>
     </div>

     <div v-bind:class="{'row form-group' : true, 'has-error': error_input.purpose}">
      <div class="col-lg-3">
       <label class="control-label label" for="purpose">Enter Purpose</label>
      </div>
      <div class="col-lg-7">
       <input type="text" id="purpose" class="form-control input-text" v-model="purpose" name="purpose">
      </div>
     </div>

     <div v-bind:class="{'row form-group' : true, 'has-error': error_input.fcm_comaker}">
      <div class=" col-lg-3">
       <label class="control-label label" for="comaker-1">Enter co-maker(1)</label>
      </div>
      <div class="col-lg-4">
       <input type="text" id="comaker-1" step="1" min="0" @keydown="filterKey" @input="fcm_filterInput" class="form-control input-text" v-model="firstcomaker">
      </div>
      <div class="col-lg-1">
       <button type="button" class="btn btn-success button" name="firstcomaker" @click="first_comaker()">Ok</button>
      </div>
      <div class="col-lg-3">
       <label class="control-label label">{{ comaker_first || ''}}</label>
      </div>
     </div>
     <div v-bind:class="{'row form-group' : true, 'has-error': error_input.scm_comaker}">
      <div class="col-lg-3">
       <label class="control-label label" for="comaker-2">Enter co-maker(2)</label>
      </div>
      <div class="col-lg-4">
       <input type="text" id="comaker-2" step="1" min="0" @keydown="filterKey" @input="scm_filterInput" class="form-control input-text" v-model="secondcomaker">
      </div>
      <div class="col-lg-1">
       <button type="button" class="btn btn-success button" name="secondcomaker" @click="second_comaker()">Ok</button>
      </div>
      <div class="col-lg-3">
       <label class="control-label label">{{ comaker_second || ''}}</label>
      </div>
     </div>
     <div class="row form-group">

     </div>
    </div>

    <div class="col-lg-5">
     <div class="section-info">
      <div class="title">
       <strong>
        Loan Summary
       </strong>
      </div>
      <div class="content">
       <p>Amount: <strong>{{ compute.lnAmount }} </strong> </p>
       <p>Terms: <strong>{{ compute.terms }} </strong></p>
       <p>Loanable Interest: <strong>{{ compute.interest }}</strong></p>
       <p>Loanable Monthly Amortization: <strong>{{ compute.monthlypayment }}</strong></p>
       <p>Loanable Total: <strong>{{ compute.totalloanamount }}</strong></p>
      </div>
      <div class="col-lg-12">
       <button type="button" class="btn btn-primary button" @click="test_save()">Save</button>
       <button type="button" class="btn btn-default button-logout" @click="logout" name="logout">Cancel</button>
      </div>
     </div>
    </div>
   </div>

  </div>
  <div class="clear-fix"></div>
  <div class="row section-footer">
   <div class="col-lg-12">
    <p class="text-center">Note: Please enter the amount, select your terms and then click compute
    </div>
   </div>
  </div>
  <script src="vue.min.js"></script>
  <script src="vue-resource.min.js"></script>
  <script src="axios.min.js"></script>
  <script src="jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  <script>
   new Vue({
    data() {
     return {
      loading: false,
      select_terms: 24,
      terms: "",
      amount: "",
      purpose: "",
      firstcomaker: "",
      secondcomaker: "",
      comaker_first: "",
      comaker_second: "",
      fcmker_vtoggle: true,
      scmker_vtoggle: true,
      PLA: "",
      comaker_5yrs: "",
      error_name: "",
      error_count: [],
      error_display: false,
      error: "",
      compute: {
       lnAmount: 0,
       terms: 0,
       percent: 0.1,
       interest: 0,
       monthlypayment: 0,
       totalloanamount: 0
      },
      error_input: {
       amount: false,
       terms: false,
       purpose: false,
       fcm_comaker: false,
       scm_comaker: false
      }
     }
    },
    methods: {
     checkcomaker() {
      var vm = this;
      var formData = new FormData();
      var result;

      formData.append("fcomaker", this.firstcomaker);
      formData.append("scomaker", this.secondcomaker);
      formData.append("checkcomaker", true);

      axios({
       url: "loan.php",
       method: "post",
       data: formData
      }).then(function(response) {
       vm.comaker_5yrs = response.data;
       // if (vm.comaker_5yrs === "error1") {
       //  vm.error_count.push("Must be 5 years employed first co-maker. Choose other co-maker");
       // }
       // else if (vm.comaker_5yrs === "error2") {
       //  vm.error_count.push("Must be 5 years employed second co-maker. Choose other co-maker");
       // }
       // else if (vm.comaker_5yrs === "error3") {
       //  vm.error_count.push("Must be 5 years employed first co-maker and second co-maker. Choose other co-maker");
       // }
      });

      return result = vm.comaker_5yrs;
     },
     test_save() {
      var status = this.compute_loan();

      if (status === true) {
       $('#exampleModalLong').modal('show');
      }
     },
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

                 this.amount = this.amount.replace(/[^0-9]+/g, '');

                 if (!this.amount.replace(/[^0-9]+/g, '')) {
                  this.error_name = "Invalid input. Please enter numbers";
                  this.error_display = true;
                  this.error_input.amount = true;

                  setTimeout(function() {
                   vm.error_input.amount = false;
                   vm.error_display = false;
                  }, 3000);
                 }


                },

                fcm_filterInput(e) {
                 var vm = this;

                 this.firstcomaker = this.firstcomaker.replace(/[^0-9]+/g, '');

                 if (!this.firstcomaker.replace(/[^0-9]+/g, '')) {
                  this.error_name = "Invalid input. Please enter numbers";
                  this.error_display = true;
                  this.error_input.fcm_comaker = true;

                  setTimeout(function() {
                   vm.error_input.fcm_comaker = false;
                   vm.error_display = false;
                  }, 3000);
                 }
                },

                scm_filterInput(e) {
                 var vm = this;

                 this.secondcomaker = this.secondcomaker.replace(/[^0-9]+/g, '');

                 if (!this.secondcomaker.replace(/[^0-9]+/g, '')) {
                  this.error_name = "Invalid input. Please enter numbers";
                  this.error_display = true;
                  this.error_input.scm_comaker = true;

                  setTimeout(function() {
                   vm.error_input.scm_comaker = false;
                   vm.error_display = false;
                  }, 3000);
                 }
                },

                save() {
                 vm = this;

                 this.compute_loan();

                 this.first_comaker();
                 this.second_comaker();

                 if (this.firstcomaker === this.secondcomaker) {
                  this.error_name = "Error";
                  this.error_display = true;

                  setTimeout(function() {
                   vm.error_display = false;
                  }, 3000);
                 } else {
                  vm.success = "";
                  var formData = new FormData();

                  formData.append("fcomaker", this.firstcomaker);
                  formData.append("scomaker", this.secondcomaker);
                  formData.append("checkcomaker", true);

                  axios({
                   url: "loan.php",
                   method: "post",
                   data: formData
                  }).then(function(response) {
                   vm.loading = true;

                   if (response.data === "invalid comaker 1" || response.data === "invalid comaker 1" || response.data === "invalid comaker 1 and 2") {
                    vm.loading = false;
                    this.error_name = "Error";
                    this.error_display = true;

                    setTimeout(function() {
                     vm.error_display = false;
                    }, 3000);
                   } else {
                    vm.loading = false;

                    var formData = new FormData();

                    var id = <?php echo $_SESSION["session_id"]; ?>;

                    if (!vm.comaker_first || !vm.comaker_second) {
                     vm.first_comaker();
                     vm.second_comaker();
                    }

                    formData.append("id", id);
                    formData.append("amount", vm.amount);
                    formData.append("terms", vm.terms);
                    formData.append("purpose", vm.purpose);
                    formData.append("fcm_id", vm.firstcomaker);
                    formData.append("scm_id", vm.secondcomaker);
                    formData.append("fcm_name", vm.comaker_first);
                    formData.append("scm_name", vm.comaker_second);
                    formData.append("s_amount", vm.PLA);
                    formData.append("save", true);

                    axios({
                     url: "loan.php",
                     method: "post",
                     data: formData
                    }).then(function(response) {
                     vm.loading = false;

                     window.location.href = "thankyou.php";
                    });
                   }
                  });

                 }
                },

                compute_loan() {
                 var vm = this;
                 var error = false;
                 var error_name = "";

                 if (!this.amount) {
                  vm.error_count.push("Please input the amount fields");
                  error = true;
                 }

                 if (!this.terms) {
                  vm.error_count.push("Please input the terms fields");
                  error = true;
                 }

                 if (!this.purpose) {
                  vm.error_count.push("Please input purpose fields");
                  error = true;
                 }

                 if (!this.firstcomaker) {
                  vm.error_count.push("Please input firstcomaker fields");
                  error = true;
                 }

                 if (!this.secondcomaker) {
                  vm.error_count.push("Please input secondcomaker fields");
                  error = true;
                 }

                 if (this.firstcomaker && this.secondcomaker) {
                  if (this.firstcomaker === this.secondcomaker) {
                   vm.error_count.push("Invalid same comaker");
                   error = true;
                  }
                 }

                 for (var i = vm.error_count.length - 1; i >= 0; i--) {
                  error_name = error_name+" "+vm.error_count[i];
                 }

                 this.error_name = error_name;
                 this.error_display = true;
                 this.error_input.scm_comaker = true;

                 setTimeout(function() {
                  vm.error_input.scm_comaker = false;
                  vm.error_display = false;
                 }, 3000);

                 // result = this.checkcomaker();
                 // console.log(result);

                 // console.log(vm.error_count);
                 // console.log(vm.comaker_5yrs);

                 vm.error_count = [];

                },
                logout() {
                 return window.location.href = "index.php";
                },
                first_comaker() {
                 var vm = this;

                 this.fcmker_vtoggle = !this.fcmker_vtoggle;
                 var formData = new FormData();

                 formData.append("firstcoid", this.firstcomaker);
                 formData.append("firstcomaker", true);

                 axios({
                  url: "loan.php",
                  method: "post",
                  data: formData
                 }).then(function(response) {
                  vm.comaker_first = response.data.fullname;

                        // console.log(response.data);
                       });

                },
                second_comaker() {
                 var vm = this;
                 this.scmker_vtoggle = !this.scmker_vtoggle;
                 var formData = new FormData();

                 formData.append("secondcoid", this.secondcomaker);
                 formData.append("secondcomaker", true);

                 axios({
                  url: "loan.php",
                  method: "post",
                  data: formData,
                 }).then(function(response) {
                  vm.comaker_second = response.data.fullname;

                        // console.log(response.data);
                       });
                },

                presc_amount() {
                 var vm = this;

                 var formData = new FormData();

                 formData.append("presc_info", true);

                 axios({
                  url: "loan.php",
                  method: "post",
                  data: formData
                 }).then(function(response) {

                  vm.PLA = response.data.amount;
                 });
                }
               },
               mounted() {
                this.presc_amount();

                this.amount = 1500;
                this.terms = 24;
                this.purpose = "wala";
                this.firstcomaker = 5106940;
                this.secondcomaker = 5107985;

               }
              }).$mount("#app");
             </script>
            </body>

            </html>