<?php include 'functions.php'; ?>
<?php include 'header.php'; ?>
    <div class="container">
      <div class="row">
      <div class="col-md-12">
        <div id="accordion" role="tablist">
          <div class="card">
            <div class="card-header" role="tab" id="headingOne">
              <h5 class="mb-0">
                <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Upload file
                </a>
              </h5>
            </div>

            <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <form method="POST" action="get.php" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="exampleFormControlFile1">Select a file php</label>
                    <input type="file" name="filePhp" class="form-control-file" accept=".php, .txt">
                  </div>
                  <button type="submit" name="submitUpload" class="btn btn-outline-primary">Upload</button>
                    <br><span class="note">*The file has been write node</span>
                    <br><span class="note">*Extension allow upload .php, .txt</span>
                </form>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" role="tab" id="headingTwo">
              <h5 class="mb-0">
                <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Paste the route
                </a>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <form method="POST" action="cfd.php">
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Enter a route</label>
                    <textarea class="form-control" name="node" rows="5" placeholder="Example: 1->2 2->3 3->2 3->4 4->E"></textarea>
                  </div>
                  <button type="submit" name="submitRoute" class="btn btn-outline-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" role="tab" id="headingThree">
              <h5 class="mb-0">
                <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Paste the code
                </a>
              </h5>
            </div>
            <div id="collapseThree" class="collapse show" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                <form method="POST" action="get.php">
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Enter code</label>
                    <textarea class="form-control" name="code" rows="15" placeholder="Enter your code (Already formatted)"></textarea>
                  </div>
                  <button type="submit" name="submitCode" class="btn btn-outline-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-12">
        
<strong>Example node formated:</strong> <button class="btn btn-sm" onclick="CopyToClipboard('exampleCode');">Copy</button><br>
<pre class="prettyprint"><code class="language-php" id="exampleCode">  
    // no need to delete comment
    // vutrinh352@gmail.com  
    //1->2
    $userWallet = $this->getUserWallet($user_id);
    //2->3
    $walletCat = $this->getWalletCat();
    //3->4 || 3->5
    if(count($userWallet) == count($walletCat)) {
      //4->E
      return true;
    }
    //5->6
    $user_wallet = [];
    //6->7 || 6->9
    if(count($userWallet) > 0)
      //7->8 || 7->9
      foreach ($userWallet as $key => $value) {
        //8->7
        array_push($user_wallet, $value['wallet_id']);
      }
    //9->10 || 9->12
    foreach ($walletCat as $key => $value) {
      //10->11 || 10->9
      if(!in_array($value['id'], $user_wallet)) {
        //11->9
        $data = [
          'address' => '',
          'user_id' => $user_id,
          'wallet_id' => $value['id'],
          'money'   => 0.00
        ];
        $this->db->insert('wallet', $data);
      }
    }
    //12->E
    return true;</code></pre>

        </div>
    </div>
  </div>
<script type="text/javascript">
function doalert(checkboxElem) {
  if (checkboxElem.checked) {
    document.getElementById('divNameFile').style.display='';
  } else {
    document.getElementById('divNameFile').style.display='none';
  }
}


function CopyToClipboard(containerid) {
if (document.selection) { 
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("Copy"); 

} else if (window.getSelection) {
    var range = document.createRange();
     range.selectNode(document.getElementById(containerid));
     window.getSelection().addRange(range);
     document.execCommand("Copy");
}}
</script>
<?php include 'footer.php'; ?>