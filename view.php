<!-- DO NOT CHANGE THIS FILE -->
<?php 
   //If "result_status" is not set, most likely the user has opened the
   // view.php file directly. We don't want to allow this.
   if (!isset($result))
   {
        header("Location: index.php");
        
   }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Currency converter">
      <meta name="author" content="">
      <title>Exchange rate calculator</title>
      <!-- Bootstrap core CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   <body>
      <div class="container">
         <div class="row">
            <div class="col">
               <form method="GET">
                  <h2>Please, convert</h2>
                  <div class="input-group row">
                     <label for="date" class="col-sm-2 col-form-label">Date:</label>
                     <input type="date" name="date" id="date" class="form-control col-sm-10" autofocus>
                  </div>
                  <div class="input-group row">
                     <label for="amount" class="col-sm-2 col-form-label">Amount:</label>
                     <input type="text" name="amount" id="amount" class="form-control col-sm-10" placeholder="Amount">
                  </div>
                  <div class="input-group row">
                     <label for="currency" class="col-sm-2 col-form-label">Currency:</label>
                     <select class="form-control col-sm-10" id="currency" name="currency">
                        <option class="form-control" selected="selected" value="">Currency</option>
                        <?php
                           foreach ($currencies as $value => $name) {
                               echo '<option value="'.$value.'">' .$name. '</option>'."\r\n";
                           }
                           ?>
                     </select>
                  </div>
                  <div class="input-group row">
                     <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Calculate</button>
                     </div>
                  </div>
               </form>
               <?php if ($result == "OK") { ?>
               <div class="alert alert-success">
                  <h3>
                     <?php echo htmlspecialchars($_GET['amount'])?> <?php echo htmlspecialchars($_GET['currency'])?>
                     = 
                     <?php echo htmlspecialchars($result_message)?> EUR
                  </h3>
                  <p>
                     Currencies fluctuate every day. The rate shown is effective on <?php echo $date?>.
                  </p>
               </div>
               <?php } elseif ($result == "ERROR") {?>
               <div class="alert alert-warning">
                  <h3>Problem detected!</h3>
                  <p><?php echo htmlspecialchars($result_message)?></p>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
      <!-- /container -->
      <!-- Bootstrap Bundle with Popper -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
   </body>
</html>