  function init() {
      if (window.goSamples) goSamples();
      var $ = go.GraphObject.make;

      myDiagram =
          $(go.Diagram, "myDiagramDiv", {
              initialContentAlignment: go.Spot.Center,
              "toolManager.mouseWheelBehavior": go.ToolManager.WheelZoom,
              "clickCreatingTool.archetypeNodeData": {
                  text: "new node"
              },
              "linkReshapingTool": new CurvedLinkReshapingTool(),
              "undoManager.isEnabled": true
          });
      myDiagram.grid.visible = true;
      myDiagram.toolManager.draggingTool.isGridSnapEnabled = true;
      myDiagram.toolManager.resizingTool.isGridSnapEnabled = true;
      myDiagram.undoManager.isEnabled = true;
      myDiagram.allowDrop = true;
      myDiagram.addDiagramListener("Modified", function(e) {
          var button = document.getElementById("SaveButton");
          if (button) button.disabled = !myDiagram.isModified;
          var idx = document.title.indexOf("*");
          if (myDiagram.isModified) {
              if (idx < 0) document.title += "*";
          } else {
              if (idx >= 0) document.title = document.title.substr(0, idx);
          }
      });
      var myOverview = $(go.Overview, "myOverviewDiv", {
          observed: myDiagram
      });
      var cxElement = document.getElementById("contextMenu");
      var myContextMenu = $(go.HTMLInfo, {
          show: showContextMenu,
          mainElement: cxElement
      });



      myDiagram.nodeTemplate =
          $(go.Node, "Spot", {
                  locationSpot: go.Spot.Center,
                  contextMenu: myContextMenu
              },
              new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
              $(go.Panel, "Auto", {
                      name: "PANEL"
                  },
                  $(go.Shape, "Circle", {
                          desiredSize: new go.Size(50, 50),
                          fill: "#CFE2F3",
                          portId: "",
                          cursor: "pointer",
                          fromLinkable: true,
                          fromLinkableSelfNode: true,
                          fromLinkableDuplicates: true,
                          toLinkable: true,
                          toLinkableSelfNode: true,
                          toLinkableDuplicates: true
                      },
                      new go.Binding("figure"),
                      new go.Binding("fill")),
                  $(go.TextBlock, {
                          font: "bold 12pt Arial",
                          margin: 10,
                          maxSize: new go.Size(160, NaN),
                          wrap: go.TextBlock.WrapFit,
                          editable: true
                      },
                      new go.Binding("text").makeTwoWay())
              )
          );

      function addNodeAndLink(e, obj) {
          var adornment = obj.part;
          var diagram = e.diagram;
          diagram.startTransaction("Add State");
          var fromNode = adornment.adornedPart;
          var fromData = fromNode.data;
          var toData = {
              text: "new"
          };
          var p = fromNode.location.copy();
          p.x += 200;
          toData.loc = go.Point.stringify(p);
          var model = diagram.model;
          model.addNodeData(toData);
          var linkdata = {
              from: model.getKeyForNodeData(fromData),
              to: model.getKeyForNodeData(toData),
              text: " " // 
          };
          model.addLinkData(linkdata);
          var newnode = diagram.findNodeForData(toData);
          diagram.select(newnode);
          diagram.commitTransaction("Add State");
          diagram.scrollToRect(newnode.actualBounds);
      }
      myPalette =
          $(go.Palette, "myPaletteDiv", {
              maxSelectionCount: 1,
              nodeTemplateMap: myDiagram.nodeTemplateMap,
              linkTemplate: $(go.Link, {
                      locationSpot: go.Spot.Center,
                      selectionAdornmentTemplate: $(go.Adornment, "Link", {
                              locationSpot: go.Spot.Center
                          },
                          $(go.Shape, {
                              isPanelMain: true,
                              fill: null,
                              stroke: "deepskyblue",
                              strokeWidth: 0
                          }),
                          $(go.Shape, // the arrowhead
                              {
                                  toArrow: "Standard",
                                  stroke: null
                              })
                      )
                  }, {
                      routing: go.Link.AvoidsNodes,
                      curve: go.Link.JumpOver,
                      corner: 5,
                      toShortLength: 4,
                  },
                  new go.Binding("points"),
                  $(go.Shape, {
                      isPanelMain: true,
                      strokeWidth: 2
                  }),
                  $(go.Shape, {
                      toArrow: "Standard",
                      stroke: null
                  })
              ),
              model: new go.GraphLinksModel(
                  [{
                          text: "Node"
                      },
                      {
                          text: "Comment",
                          figure: "RoundedRectangle",
                          fill: 'transparent'
                      },


                  ])
          });
      myDiagram.linkTemplate =
          $(go.Link, {
                  curve: go.Link.Bezier,
                  relinkableFrom: true,
                  relinkableTo: true,
                  reshapable: true,
              },
              new go.Binding("curviness", "curviness").makeTwoWay(),
              $(go.Shape),
              $(go.Shape, {
                  toArrow: "Standard",
                  fill: null
              }),
              $(go.Panel, "Auto",
                  new go.Binding("visible", "isSelected").ofObject(),
                  $(go.Shape, "RoundedRectangle", {
                      fill: "#777",
                      stroke: null
                  }),
                  $(go.TextBlock, "", {
                          textAlign: "center",
                          font: "12pt arial",
                          stroke: "black",
                          margin: 1,
                          minSize: new go.Size(20, NaN),
                          editable: true
                      },
                      new go.Binding("text").makeTwoWay())

              ),
              $(go.TextBlock, "", {
                      textAlign: "center",
                      font: "11pt arial",
                      stroke: "black",
                      margin: 4,
                      minSize: new go.Size(20, NaN),
                      segmentOffset: new go.Point(0, -15),
                      editable: true
                  },
                  new go.Binding("text").makeTwoWay())

          );

      myDiagram.contextMenu = myContextMenu;

      // We don't want the div acting as a context menu to have a (browser) context menu!
      cxElement.addEventListener("contextmenu", function(e) {
          e.preventDefault();
          return false;
      }, false);

      function showContextMenu(obj, diagram, tool) {
          // Show only the relevant buttons given the current state.
          var cmd = diagram.commandHandler;
          document.getElementById("cut").style.display = cmd.canCutSelection() ? "block" : "none";
          document.getElementById("copy").style.display = cmd.canCopySelection() ? "block" : "none";
          document.getElementById("paste").style.display = cmd.canPasteSelection() ? "block" : "none";
          document.getElementById("delete").style.display = cmd.canDeleteSelection() ? "block" : "none";

          // Now show the whole context menu element
          cxElement.style.display = "block";
          // we don't bother overriding positionContextMenu, we just do it here:
          var mousePt = diagram.lastInput.viewPoint;
          cxElement.style.left = mousePt.x + "px";
          cxElement.style.top = mousePt.y + "px";
      }
      document.getElementById("blobButton").addEventListener("click", makeBlob);
      document.getElementById("svgButton").addEventListener("click", makeAndDownSvg);


      load();
      myDiagram.addDiagramListener("ChangedSelection", function(e) {
          enableAll();
      });
      myDiagram.addDiagramListener("ClipboardChanged", function(e) {
          enableAll();
      });
      myDiagram.addModelChangedListener(function(e) {
          if (e.isTransactionFinished) enableAll();
      });
      setTimeout(enableAll, 1);
  }

  function save() {
      document.getElementById("mySavedModel").value = myDiagram.model.toJson();
      document.getElementById("SaveJsonButton").classList.remove("disabled");
  }

  function load() {
      myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
  }

  function enable(name, ok) {
      var button = document.getElementById(name);
      if (button) button.disabled = !ok;
  }

  function myCallbackSvg(blob) {

	var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

      var url = window.URL.createObjectURL(blob);
      var filename = [year, month, day].join('-') + '.svg';

      var a = document.createElement("a");
      a.style = "display: none";
      a.href = url;
      a.download = filename;

      // IE 11
      if (window.navigator.msSaveBlob !== undefined) {
          window.navigator.msSaveBlob(blob, filename);
          return;
      }

      document.body.appendChild(a);
      requestAnimationFrame(function() {
          a.click();
          window.URL.revokeObjectURL(url);
          document.body.removeChild(a);
      });
  }

  function myCallback(blob) {

	var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

      var url = window.URL.createObjectURL(blob);
      var filename = [year, month, day].join('-') + '.png';

      var a = document.createElement("a");
      a.style = "display: none";
      a.href = url;
      a.download = filename;

      // IE 11
      if (window.navigator.msSaveBlob !== undefined) {
          window.navigator.msSaveBlob(blob, filename);
          return;
      }

      document.body.appendChild(a);
      requestAnimationFrame(function() {
          a.click();
          window.URL.revokeObjectURL(url);
          document.body.removeChild(a);
      });
  }

  function enableAll() {
      var cmdhnd = myDiagram.commandHandler;
      enable("SelectAll", cmdhnd.canSelectAll());
      enable("Cut", cmdhnd.canCutSelection());
      enable("Copy", cmdhnd.canCopySelection());
      enable("Paste", cmdhnd.canPasteSelection());
      enable("Delete", cmdhnd.canDeleteSelection());
      enable("Group", cmdhnd.canGroupSelection());
      enable("Ungroup", cmdhnd.canUngroupSelection());
      enable("Undo", cmdhnd.canUndo());
      enable("Redo", cmdhnd.canRedo());
  }

  // This is the general menu command handler, parameterized by the name of the command.
  function cxcommand(event, val) {
      if (val === undefined) val = event.currentTarget.id;
      var diagram = myDiagram;
      switch (val) {
          case "cut":
              diagram.commandHandler.cutSelection();
              break;
          case "copy":
              diagram.commandHandler.copySelection();
              break;
          case "paste":
              diagram.commandHandler.pasteSelection(diagram.lastInput.documentPoint);
              break;
          case "delete":
              diagram.commandHandler.deleteSelection();
              break;
          case "color":
              {
                  var color = window.getComputedStyle(document.elementFromPoint(event.clientX, event.clientY).parentElement)['background-color'];
                  changeColor(diagram, color);
                  break;
              }
      }
      diagram.currentTool.stopTool();
  }

  function openSVG() {
      var newWindow = window.open("", "newWindow");
      if (!newWindow) return;
      var newDocument = newWindow.document;
      var svg = myDiagram.makeSvg({
          document: newDocument,
          scale: 1
      });
      newDocument.body.appendChild(svg);
  }

  function copyText(id = 'urlCfd') {
      var copyText = document.getElementById(id);
      copyText.select();
      document.execCommand("Copy");
  }

  function makeImage() {
      document.getElementById("showImg").innerHTML = "";
      document.getElementById("showImg").appendChild(myDiagram.makeImage({
          scale: 1,
          background: "white"
      }));
  }

  function makeSvg() {
      document.getElementById("showImg").innerHTML = "";
      document.getElementById("showImg").appendChild(myDiagram.makeSvg({
          scale: 1,
          background: "white"
      }));
  }

  function makeAndDownSvg() {
      var svg = myDiagram.makeSvg({
          scale: 1,
          background: "white"
      });
      var svgstr = new XMLSerializer().serializeToString(svg);
      var blob = new Blob([svgstr], {
          type: "image/svg+xml"
      });
      myCallbackSvg(blob);
  }

  function showUrl() {
      var x = document.getElementById("url");
      if (x.style.display === "none") {
          x.style.display = "block";
      } else {
          x.style.display = "none";
      }
  }

  function makeBlob() {
      var blob = myDiagram.makeImageData({
          scale: 1,
          background: "white",
          returnType: "blob",
          callback: myCallback
      });
  }

  function fullScreen() {
      var element = document.getElementById("div-diagram");
      element.classList.toggle("fullscreen");
      document.getElementById("myDiagramDiv").style.width = "100%";
  }

  function SaveJsonButton() {
      var jsonData = document.getElementById("mySavedModel").value;
      jsonData = Base64.encode(jsonData);
      setCookie('jsonData', jsonData, 3);
      alert('Cookie is saved !');
  }

  function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      var expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }


  var Base64 = {
      _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
      encode: function(input) {
          var output = "";
          var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
          var i = 0;

          input = Base64._utf8_encode(input);

          while (i < input.length) {

              chr1 = input.charCodeAt(i++);
              chr2 = input.charCodeAt(i++);
              chr3 = input.charCodeAt(i++);

              enc1 = chr1 >> 2;
              enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
              enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
              enc4 = chr3 & 63;

              if (isNaN(chr2)) {
                  enc3 = enc4 = 64;
              } else if (isNaN(chr3)) {
                  enc4 = 64;
              }

              output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

          }
          return output;
      },


      decode: function(input) {
          var output = "";
          var chr1, chr2, chr3;
          var enc1, enc2, enc3, enc4;
          var i = 0;

          input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

          while (i < input.length) {

              enc1 = this._keyStr.indexOf(input.charAt(i++));
              enc2 = this._keyStr.indexOf(input.charAt(i++));
              enc3 = this._keyStr.indexOf(input.charAt(i++));
              enc4 = this._keyStr.indexOf(input.charAt(i++));

              chr1 = (enc1 << 2) | (enc2 >> 4);
              chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
              chr3 = ((enc3 & 3) << 6) | enc4;

              output = output + String.fromCharCode(chr1);

              if (enc3 != 64) {
                  output = output + String.fromCharCode(chr2);
              }
              if (enc4 != 64) {
                  output = output + String.fromCharCode(chr3);
              }

          }

          output = Base64._utf8_decode(output);

          return output;

      },

      _utf8_encode: function(string) {
          string = string.replace(/\r\n/g, "\n");
          var utftext = "";

          for (var n = 0; n < string.length; n++) {

              var c = string.charCodeAt(n);

              if (c < 128) {
                  utftext += String.fromCharCode(c);
              } else if ((c > 127) && (c < 2048)) {
                  utftext += String.fromCharCode((c >> 6) | 192);
                  utftext += String.fromCharCode((c & 63) | 128);
              } else {
                  utftext += String.fromCharCode((c >> 12) | 224);
                  utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                  utftext += String.fromCharCode((c & 63) | 128);
              }

          }

          return utftext;
      },

      _utf8_decode: function(utftext) {
          var string = "";
          var i = 0;
          var c = c1 = c2 = 0;

          while (i < utftext.length) {

              c = utftext.charCodeAt(i);

              if (c < 128) {
                  string += String.fromCharCode(c);
                  i++;
              } else if ((c > 191) && (c < 224)) {
                  c2 = utftext.charCodeAt(i + 1);
                  string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                  i += 2;
              } else {
                  c2 = utftext.charCodeAt(i + 1);
                  c3 = utftext.charCodeAt(i + 2);
                  string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                  i += 3;
              }

          }

          return string;
      }

  }

  function copyPath(id) {
      let textarea = document.createElement('textarea');
      textarea.id = 't';
      textarea.style.height = 0;
      document.body.appendChild(textarea);
      textarea.value = document.getElementById('path-' + id).innerText;
      let selector = document.querySelector('#t');
      selector.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      document.getElementById("node-" + id).style.textDecoration = "underline overline";
  }

  function addInput() {
      var element = document.getElementById('inputAbnormal');
      var html = document.createElement("p");
      nextIdInput++;
      html.innerHTML = '<div class="form-inline" id="input' + nextIdInput + '"><h4><span class="badge badge-pill badge-primary">' + nextIdInput + '</span></h4>&nbsp;<div class="form-group"><input type="text" class="form-control" name="inputPath1[]" id="inputPath1" placeholder="Path 1"></div>&nbsp;<div class="form-group"><input type="text" class="form-control" name="inputPath2[]" id="inputPath2" placeholder="Path 2"></div>&nbsp;<span class="fa-stack fa-lg" onclick="delInput(' + nextIdInput + ');"><i class="fa fa-square-o fa-stack-2x" ></i><i class="fa fa-lg fa-times fa-stack-1x text-danger"></i></i></span></div>';
      element.appendChild(html);
  }

  function delInput(id) {
      console.log('ik');
      var element = document.getElementById("input" + id);
      element.outerHTML = "";
      delete element;
  }
 function saveTextAsFile() {
      var textToWrite = document.getElementById('mySavedModel').value;
      var textFileAsBlob = new Blob([textToWrite], {type:'text/plain'});
      var date = new Date();
      var fileNameToSaveAs = date.getFullYear() + '-' + date.getMonth() + '-' + date.getUTCDate() + '.txt';

      var downloadLink = document.createElement("a");
      downloadLink.download = fileNameToSaveAs;
      downloadLink.innerHTML = "Download File";
      if (window.webkitURL != null)
      {
          downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
      }
      else
      {
          downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
          downloadLink.onclick = destroyClickedElement;
          downloadLink.style.display = "none";
          document.body.appendChild(downloadLink);
      }
      downloadLink.click();
}
    