<?php include 'header.php'; ?>
<?php require 'functions.php'; ?>
<?php

  if(isset($_POST['node'])) {
    removeAllCookie();
    setcookie('node', formatNode($_POST['node'],true,false), time() + (86400 * 30), "/");
    setcookie('record', true, time() + (86400 * 30), "/");
    header("Refresh:0");
    exit();
  }
  if(isset($_GET['typeFormat'])) {
    setcookie('typeFormat', $_GET['typeFormat'], time() + (86400 * 30), "/"); 
    header("Location: /cfd.php");
  }
  $typeFormat = isset($_COOKIE['typeFormat']) ? $_COOKIE['typeFormat'] : 1;

  if(isset($_GET['delete'])) {
    removeAllCookie();
    header("Location: /");
    exit();
  }
  if(isset($_GET['delAb'])) {
    $abnormal = '||'.$_GET['delAb'];
    $newCookie = str_replace($abnormal, '', $_COOKIE['abnormal']);
    setcookie('abnormal', $newCookie, time() + (86400 * 30), "/"); 
    header("Location: cfd.php");
    exit();
  }  
  if(isset($_GET['delAllAb'])) {
    setcookie('abnormal', '', time() - 1, "/"); 
    header("Location: cfd.php");
    exit();
  }
  if(isset($_POST['addAbnormal'])) {
    if(!isset($_COOKIE['abnormal']))
      setcookie('abnormal', ' ', time() + (86400 * 30), "/"); 
    $path1 = $_POST['inputPath1'];
    $path2 = $_POST['inputPath2'];
    if(count($path1) > 0 && count($path2) > 0) {
      $cookie = null;
      foreach ($path1 as $key => $value) {
        $pt1 = formatNode($path1[$key],true,true);
        $pt2 = formatNode($path2[$key],true,true);
        if(checkNodeInLine($pt1) == true && checkNodeInLine($pt1) == true && $pt1 != $pt2)
         $cookie .= '||'.$pt1.' '.$pt2;
      }
     setcookie('abnormal', $_COOKIE['abnormal'].$cookie, time() + (86400 * 30), "/"); 
    }
     header("Location: cfd.php");
    exit();
  }
  if(isset($_GET['deleteJson'])) {
    setcookie('jsonData', '', time() - 1, "/"); 
    header("Location: cfd.php");
    exit();
  }
  if(isset($_GET['file'])) {
    removeAllCookie();
  }
  if(isset($_GET['node'])) {
    removeAllCookie();
    setcookie('node', formatNode(base64_url_decode($_GET['node']),true,false), time() + (86400 * 30), "/");
    setcookie('record', true, time() + (86400 * 30), "/");
    header("Location: cfd.php");
    exit();
  }

 if(isset($_GET['file']) && file_exists('upload/'.$_GET['file']) || isset($_COOKIE['node'])) {
  setRecord();
    // read file
  $txtRoute = isset($_GET['file']) ? formatNode (preg_replace("/ {2,}/", " ", getNodeInFile('upload/'.$_GET['file'])),true,false) : $_COOKIE['node'];
  $explodeRoute = explode(' ', $txtRoute);
  setcookie('node', formatNode($txtRoute,true,false), time() + (86400 * 30), "/");
  if(isset($_GET['file']))  {
    $dataCodeInFile = getDataCodeFormFile($_GET['file']);
  }

  $arrLinkImpossible = getAbnormal();
  sort($arrLinkImpossible);
  $checkErr = checkFormatAllRoute($txtRoute);

  if(count($checkErr) == 0) {
    $nodeDataArray = getNode($explodeRoute);
    $lastNode = getLastNode($nodeDataArray);
    $minWay = getMinWay(formatNode($txtRoute,true,false));
    $nodeData = convertData($nodeDataArray,$lastNode,$minWay,$explodeRoute,$typeFormat);
    $nodeLink = convertLink($explodeRoute);
    $allPath = getAllRoute($explodeRoute);
    $allPathImpossible = getPathImpossible($allPath, $arrLinkImpossible);
    $allPath = array_diff($allPath, $allPathImpossible);
    $allPath = array_values($allPath);
    $allPathImpossible = array_values($allPathImpossible);
  }
  ?>

<script src="js/go.js"></script>
<script src="js/cfd.js"></script>
<script src="js/CurvedLinkReshapingTool.js"></script>
<body onload="init()">
  <div class="container">
    <div class="row">
    <div class="col-md-12">
      
  <?php if(count($checkErr) > 0) : ?>
        <p class="text-center">
          <h4>Error list</h4>
        </p>
    <ul class="list-group">
      <?php foreach ($checkErr as $key => $value) { ?>
        <li class="list-group-item"><?php echo $value; ?></li>
      <?php } ?>
    </ul>
        <p class="text-center">
          <h4>Edit node</h4>
        </p>
        <form method="POST">
          <p>
            <textarea class="form-control" name="node" rows="5" placeholder="Example: 1->2 2->3 3->2 3->4 4->E"><?php echo $txtRoute; ?></textarea>
          </p>
          <button type="submit" class="btn btn-block btn-primary">Try it !</button>
        </form>
    <hr>
    <a href="?delete" class="btn btn-block btn-outline-primary">Delete cookie</a>
    <?php
      include 'footer.php';
      exit();
    ?>
    <?php endif; ?>


      <div id="sample">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">
            <h3>
              <i class="fa fa-braille" aria-hidden="true"></i> 
              Diagram CFD (Format: <?php echo $typeFormat.' '; echo ($typeFormat == 1 ? 'Tree' : 'Straight') ?>)
              <a href="?delete" class="btn btn-outline-primary btn-sm">Delete cookie</a>
            </h3>
            <a href="?typeFormat=<?php echo ($typeFormat == 1 ? '2' : '1'); ?>">Change to format <?php echo ($typeFormat == 1 ? '2' : '1'); ?></a>
          </li>
        </ol>

        <div id="accordion" role="tablist" aria-multiselectable="true">
          <div class="row">
            <div class="col-md-3">
              <button data-toggle="collapse" data-parent="#accordion" href="#cAllRoute" aria-expanded="true" aria-controls="cAllRoute" class="btn btn-outline-primary btn-block">
                <i class="fa fa-exchange" aria-hidden="true"></i>
                Show path normal <span class="badge badge-default"><?php echo count($allPath); ?></span>
              </button>
            </div>
            <div class="col-md-3">
              <button data-toggle="collapse" data-parent="#accordion" href="#abnormal" aria-expanded="true" aria-controls="abnormal" class="btn btn-outline-primary btn-block">
                <i class="fa fa-exchange" aria-hidden="true"></i>
                Show path abnormal <span class="badge badge-default"><?php echo count($allPathImpossible); ?></span>
              </button>
            </div>
            <div class="col-md-3">
              <button class="btn btn-outline-primary btn-block" data-toggle="collapse" data-parent="#accordion" href="#cDataAb" aria-expanded="false" aria-controls="cDataAb" >
                <i class="fa fa-exchange" aria-hidden="true"></i>
                Show data abnormal <span class="badge badge-default"><?php echo count($arrLinkImpossible); ?></span>
              </button>
            </div>
            <div class="col-md-3">
              <button class="btn btn-outline-primary btn-block" data-toggle="collapse" data-parent="#accordion" href="#cDataRoute" aria-expanded="false" aria-controls="cDataRoute" >
                <i class="fa fa-exchange" aria-hidden="true"></i>
                Show data node <span class="badge badge-default"><?php echo count($explodeRoute); ?></span>
              </button>
            </div>
          </div>
          <div class="card">
            <div id="cDataAb" class="collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="card card-block">
                <h3>Add data abnormal</h3> 

                  <form method="POST">
                    <div id="inputAbnormal">
                        
                    </div>

                    <div style="margin-top: 5px;">
                      <button type="submit" name="addAbnormal" class="btn btn-primary btn-sm">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i> Submit
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-primary" onclick="addInput();">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add input
                      </button>
                    </div>
                  </form>
                  <hr>
                <h3>Data abnormal </h3>  

                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Path1</th>
                      <th>Path2</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($arrLinkImpossible) > 0) foreach ($arrLinkImpossible as $key => $value) { ?>
                    <tr>
                      <th scope="row"><?php echo $key+1; ?></th>
                      <td><?php echo $value[0]; ?></td>
                      <td><?php echo $value[1]; ?></td>
                      <td><a class="btn btn-sm btn-primary" href="?delAb=<?php echo $value[0].' '.$value[1]; ?>"><i class="fa fa-trash"></i> Delete</a></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <a href="?delAllAb" class="btn btn-outline-primary btn-sm">Delete all</a>
              </div>
            </div>
          </div>
          <div class="card">
            <div id="cAllRoute" class="collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="card card-block">
                <h3>All path normal</h3> 
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Path (Click path to copy)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($allPath) > 0) foreach ($allPath as $key => $value) { ?>
                    <tr>
                      <th scope="row"><div id="node-ab<?php echo $key+1; ?>"><?php echo $key+1; ?></div></th>
                      <td>
                        <a href="javascript:void(0);" onclick="copyPath('ab<?php echo $key+1; ?>');"> 
                          <div id="path-ab<?php echo $key+1; ?>">S-<?php echo $value; ?></div>
                        </a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <button class="btn btn-outline-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                Export to textarea
              </button>
              <div class="collapse" id="collapseExample1">
                <div class="card card-block">
                  <textarea class="form-control" rows="10" id="all-path-ab"><?php if(count($allPath) > 0) foreach ($allPath as $key => $value) { ?>S-<?php echo $value.PHP_EOL; ?><?php } ?></textarea>
                  <button class="btn btn-block btn-primary btn-sm" onclick="copyText('all-path-ab');">Copy to clipboard</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div id="abnormal" class="collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="card card-block">
                <h3>All path abnormal</h3>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Path (Click path to copy)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($allPathImpossible) > 0) foreach ($allPathImpossible as $key => $value) { ?>
                    <tr>
                      <th scope="row"><div id="node-<?php echo $key+1; ?>"><?php echo $key+1; ?></div></th>
                      <td>
                        <a href="javascript:void(0);" onclick="copyPath(<?php echo $key+1; ?>);"> 
                          <div id="path-<?php echo $key+1; ?>">S-<?php echo $value; ?></div>
                        </a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <button class="btn btn-outline-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Export to textarea
              </button>
              <div class="collapse" id="collapseExample">
                <div class="card card-block">
                  <textarea class="form-control" rows="10" id="all-path"><?php if(count($allPathImpossible) > 0) foreach ($allPathImpossible as $key => $value) { ?>S-<?php echo $value.PHP_EOL; ?><?php } ?></textarea>
                  <button class="btn btn-block btn-primary btn-sm" onclick="copyText('all-path');">Copy to clipboard</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div id="cDataRoute" class="collapse" role="tabpanel" aria-labelledby="cDataRoute">
              <div class="card card-block">
                <h3>Data node</h3>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Node</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($explodeRoute) > 0) foreach ($explodeRoute as $key => $value) { ?>
                    <tr>
                      <th scope="row"><?php echo $key+1; ?></th>
                      <td><?php echo $value; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <button class="btn btn-outline-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseExample33" aria-expanded="false" aria-controls="collapseExample1">
                Export to textarea
              </button>
              <div class="collapse" id="collapseExample33">
                <div class="card card-block">
                  <textarea class="form-control" rows="10" id="all-path-ac"><?php if(count($explodeRoute) > 0) foreach ($explodeRoute as $key => $value) { ?><?php echo $value.PHP_EOL; ?><?php } ?></textarea>
                  <button class="btn btn-block btn-primary btn-sm" onclick="copyText('all-path-ac');">Copy to clipboard</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
           <div class="col-lg-12">
              <div style="width:100%; white-space:nowrap;" id="div-diagram">
                 <button class="btn btn-block btn-primary btn-sm" onclick="fullScreen();">
                 <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                 <strong>Full Screen</strong>
                 </button>
                 <span style="display: inline-block; vertical-align: top; width:10%; height: 620px;">
                    <strong>Overview</strong>
                    <div id="myOverviewDiv" style="border: solid 3px #ddd; border-radius: 3px; height: 400px"></div>
                    <strong>Menu</strong>
                    <div id="myPaletteDiv" style="border: solid 3px #ddd; border-radius: 3px; height: 195px"></div>
                 </span>
                 <span style="display: inline-block; vertical-align: top; width:90%">
                    <strong>Diagram</strong>
                    <div style="position: relative;"  >
                       <div id="myDiagramDiv" style="border: solid 3px #ddd; border-radius: 3px; height: 620px;"></div>
                       <div id="contextMenu">
                          <ul>
                             <li id="cut" onclick="cxcommand(event)"><a href="#" target="_self">Cut</a></li>
                             <li id="copy" onclick="cxcommand(event)"><a href="#" target="_self">Copy</a></li>
                             <li id="paste" onclick="cxcommand(event)"><a href="#" target="_self">Paste</a></li>
                             <li id="delete" onclick="cxcommand(event)"><a href="#" target="_self">Delete</a></li>
                          </ul>
                       </div>
                    </div>
                 </span>
                 <div class="row">
                    <div class="col-md-6 offset-md-3">
                       <hr>
                       <div class="btn-group d-flex" role="group">
                          <button id="Redo" type="button" onclick="myDiagram.commandHandler.redo()" class="btn  btn-outline-primary btn-sm w-100">Redo</button>
                          <button id="Undo" type="button" onclick="myDiagram.commandHandler.undo()" class="btn btn-outline-primary btn-sm w-100">Undo</button>
                          <button id="SelectAll" type="button" onclick="myDiagram.commandHandler.selectAll()" class="btn btn-outline-primary btn-sm w-100">Select All</button>
                          <button id="Copy" type="button" onclick="myDiagram.commandHandler.copySelection()" class="btn btn-outline-primary btn-sm w-100">Copy</button>
                          <button id="Paste" type="button" onclick="myDiagram.commandHandler.pasteSelection()" class="btn btn-outline-primary btn-sm w-100">Paste</button>
                          <button id="Delete" type="button" onclick="myDiagram.commandHandler.deleteSelection()" class="btn btn-outline-primary btn-sm w-100">Delete</button>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <button id="SaveButton" onclick="save()" class="btn btn-primary btn-block">
              <i class="fa fa-floppy-o" aria-hidden="true"></i>
              Save diagram to JSON
            </button>
          </div>    
          <div class="col-md-6">
            <button onclick="load()" class="btn btn-primary btn-block">
              <i class="fa fa-refresh" aria-hidden="true"></i>
              Load JSON
            </button>
          </div>
        </div>
        <hr>
        <strong>Diagram Model saved in JSON format:</strong>
          
        <textarea class="form-control" id="mySavedModel" rows="10"><?php if(!isset($_COOKIE['jsonData'])) { ?>{ "class": "go.GraphLinksModel",
          "linkFromPortIdProperty": "fromPort",
          "linkToPortIdProperty": "toPort",
          "nodeDataArray": [
        <?php echo $nodeData; ?>
         ],
          "linkDataArray": [
        <?php echo $nodeLink; ?>
         ]}<?php } else { ?><?php echo base64_decode($_COOKIE['jsonData']); ?><?php } ?></textarea>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <button class="btn btn-block btn-primary disabled" onclick="SaveJsonButton();" id="SaveJsonButton"><i class="fa fa-floppy-o"></i> Save JSON to Cookie</button>
          </div>
          <div class="col-md-4">
            <a href="?deleteJson" class="btn btn-block btn-primary<?php echo (!isset($_COOKIE['jsonData']) ? ' disabled' : ''); ?>"><i class="fa fa-trash"></i>  Delete JSON on Cookie</a>
          </div>
          <div class="col-md-4">
            <button class="btn btn-block btn-primary" onclick="saveTextAsFile();"><i class="fa fa-cloud-download"></i>  Download JSON</button>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-2">
            <button onclick="makeImage();" class="btn btn-block btn-outline-primary">
              <i class="fa fa-picture-o" aria-hidden="true"></i>
              Make image
            </button>
          </div>
          <div class="col-md-2">
            <button onclick="makeSvg();" class="btn btn-block btn-outline-primary">
              <i class="fa fa-picture-o" aria-hidden="true"></i>
              Make svg
            </button>
          </div>
          <div class="col-md-2">
            <button onclick="openSVG();" class="btn btn-block btn-outline-primary">
              <i class="fa fa-window-maximize" aria-hidden="true"></i>
              Open svg
            </button>
          </div>
          <div class="col-md-2">
            <button onclick="showUrl();" class="btn btn-block btn-outline-primary">
              <i class="fa fa-link" aria-hidden="true"></i>
              Make url
            </button>
          </div>
          <div class="col-md-2">
            <button id="blobButton" class="btn btn-block btn-outline-primary">
              <i class="fa fa-cloud-download" aria-hidden="true"></i>
              Download png
            </button>
          </div>
          <div class="col-md-2">
            <button id="svgButton" class="btn btn-block btn-outline-primary">
              <i class="fa fa-cloud-download" aria-hidden="true"></i>
              Download svg
            </button>
          </div>
        </div>
        <hr>
        <div class="row" style="display: none;" id="url">
          <div class="col-md-12">
            <div class="input-group form-group has-success">
              <input type="text" class="form-control form-control-success"  value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?node=<?php echo base64_url_encode($txtRoute); ?>" id="urlCfd">
              <span class="input-group-btn">
                <button class="btn btn-success" onclick="copyText()" type="button">Copy!</button>
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-block">

                <h4 class="card-title">Result Image</h4>
                <div id="showImg">Not yet !</div>
              </div>
            </div>
          </div>
        </div>
  <?php } else { ?>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h3>File not exist or cannot find node format !</h3>
          </div>
        </div>
      </div>
  <?php } ?>
    </div>
    </div>

  </div>
  </div>
  <script type="text/javascript">
    var nextIdInput = 0;
    addInput(nextIdInput);
  </script>
</body>
<?php include 'footer.php'; ?>

