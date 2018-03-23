<?php include 'header.php'; ?>
<?php require 'functions.php'; ?>
    <div class="container">
      <div class="col-md-12">

<script src="js/go.js"></script>
<script src="js/cfd.js"></script>
<script src="js/CurvedLinkReshapingTool.js"></script>
<body onload="init()">
<div id="sample">
<ol class="breadcrumb">
  <li class="breadcrumb-item active">
    <h3>
      <i class="fa fa-braille" aria-hidden="true"></i> 
      Draw Control Flow Diagram
    </h3>
  </li>
</ol>


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
    <div class="col-md-4">
      <button id="SaveButton" onclick="save()" class="btn btn-primary btn-block">
        <i class="fa fa-floppy-o" aria-hidden="true"></i>
        Save diagram to json
      </button>
    </div>    
    <div class="col-md-4">
      <button onclick="load()" class="btn btn-primary btn-block">
        <i class="fa fa-refresh" aria-hidden="true"></i>
        Load json
      </button>
    </div>
    <div class="col-md-4">
      <button class="btn btn-block btn-primary" onclick="saveTextAsFile();"><i class="fa fa-cloud-download"></i>  Download JSON</button>
    </div>
  </div>
<hr>
<strong>Diagram Model saved in JSON format:</strong>
  <textarea class="form-control" id="mySavedModel" rows="10">
{ "class": "go.GraphLinksModel",
  "linkFromPortIdProperty": "fromPort",
  "linkToPortIdProperty": "toPort",
  "nodeDataArray": [
{"key":-1, "category":"Start", "loc":"0 0", "text":"Start"},
{"key":0, "category":"End", "loc":"0 100", "text":"End"}
 ],
  "linkDataArray": [

 ]}
  
  
  </textarea>
  <hr>

  <div class="row">
    <div class="col-md-3">
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
      <button id="blobButton" class="btn btn-block btn-outline-primary">
        <i class="fa fa-cloud-download" aria-hidden="true"></i>
        Download png
      </button>
    </div>
    <div class="col-md-3">
      <button id="svgButton" class="btn btn-block btn-outline-primary">
        <i class="fa fa-cloud-download" aria-hidden="true"></i>
        Download svg
      </button>
    </div>
  </div>

  <hr>

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
<?php include 'footer.php'; ?>

