//document.write("<script src=\"scripts/jquery.js\" type=\"text/javascript\" > </script>"); 
document.write("<script src=\"http://a4647.research.ltu.se/~satin/advuev/json2.js\" type=\"text/javascript\" > </script>"); 
document.write("<script type=\"text/javascript\" src=\"http://a4647.research.ltu.se/~satin/advuev/scripts/jit.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"http://a4647.research.ltu.se/~satin/advuev/scripts/jit-yc.js\"></script>");


var labelType, useGradients, nativeTextSupport, animate;
var retVal=[];
(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
    elem: false,
    write: function(text)
    {
        if (!this.elem) 
           this.elem = document.getElementById('log');
        this.elem.innerHTML = text;
        this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
    }
};



function CreateXMLHttpRequest() 
{
		if (typeof XMLHttpRequest != "undefined"){
           //All modern browsers (IE7+, Firefox, Chrome, Safari, and Opera) uses XMLHttpRequest object
			return new XMLHttpRequest();
		}
		else if (typeof ActiveXObject != "undefined") {
			// Internet Explorer (IE5 and IE6) uses an ActiveX object
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		else {
				throw new Error("XMLHttpRequestnot supported");
		}
}
		


function init(gfile){
         	
			 var data="file="+ gfile;
			 var txtFile =CreateXMLHttpRequest();
		
	
			 txtFile.open("POST", "http://a4647.research.ltu.se/advuev/php/gpsearch/test.php", true);
			 
			 txtFile.onreadystatechange = function() 
			 {
			 	if (txtFile.readyState == 4) { 
					if (txtFile.status == 200) { 						
						json= eval('('+ txtFile.responseText + ')');
							
  							// init ForceDirected
 						var fd = new $jit.ForceDirected({
    							//id of the visualization container
    							injectInto: 'infovis',
    							//Enable zooming and panning
   								 //by scrolling and DnD
    							Navigation: {
      									enable: true,
     								   //Enable panning events only if we're dragging the empty
      									//canvas (and not a node).
      									panning: 'avoid nodes',
     									zooming: 10 //zoom speed. higher is more sensible
    							},
   								 // Change node and edge styles such as
    								// color and width.
    							// These properties are also set per node
    							// with dollar prefixed data-properties in the
    							// JSON structure.
    							Node: {
      									overridable: true,
      
    							},
    							Edge: {
      									overridable: true,
      									color: '#F8E0EC',
      									lineWidth: 1.8
   								 },
    							//Native canvas text styling
    							Label: {
      									type: labelType, //Native or HTML
      									size: 10,
      									color:'#4B8A08',
      									style: 'bold'
    							},
    							//Add Tips
    							Tips: {
      										enable: true,
      										onShow: function(tip, node) {
        											//count connections
        										var count = 0;
        										node.eachAdjacency(function() { count++; });
        										//display node info in tooltip
        										tip.innerHTML = "<div class=\"tip-title\">" + node.name + "</div>"
         										 + "<div class=\"tip-text\"><b>connections:</b> " + count + "</div>";
      										}
    							},
    							// Add node events
    							Events: {
      										enable: true,
      											//Change cursor style when hovering a node
      										onMouseEnter: function() {
        												fd.canvas.getElement().style.cursor = 'move';
      										},
      										onMouseLeave: function() {
        												fd.canvas.getElement().style.cursor = '';
      										},
      										//Update node positions when dragged
      										onDragMove: function(node, eventInfo, e) {
          												var pos = eventInfo.getPos();
          												node.pos.setc(pos.x, pos.y);
          												fd.plot();
      										},
      										//Implement the same handler for touchscreens
      										onTouchMove: function(node, eventInfo, e) {
        												$jit.util.event.stop(e); //stop default touchmove event
        												this.onDragMove(node, eventInfo, e);
      										},
      										//Add also a click handler to nodes
      										onClick: function(node) {
       													 if(!node) return;
        												// Build the right column relations list.
        												// This is done by traversing the clicked node connections.
       													 var html = "<h4>" + node.name + "</h4><b> connections:</b><ul><li>",
            											 list = [];
        												 node.eachAdjacency(function(adj){
          												 		list.push(adj.nodeTo.name);
        												 });
        												//append connections information
        												$jit.id('inner-details').innerHTML = html + list.join("</li><li>") + "</li></ul>";
      										}
    							},
    							//Number of iterations for the FD algorithm
    							iterations: 200,
    							//Edge length
    							levelDistance: 130,
    							// Add text to the labels. This method is only triggered
    							// on label creation and only for DOM labels (not native canvas ones).
    							onCreateLabel: function(domElement, node){
      									domElement.innerHTML = node.name;
      									var style = domElement.style;
      									style.fontSize = "0.8em";
      									style.color = "#ddd";
    							},
    							// Change node styles when DOM labels are placed
   							    // or moved.
    							onPlaceLabel: function(domElement, node){
      									var style = domElement.style;
      									var left = parseInt(style.left);
      									var top = parseInt(style.top);
      									var w = domElement.offsetWidth;
      									style.left = (left - w / 2) + 'px';
      									style.top = (top + 10) + 'px';
      									style.display = '';
    							}
  					});
  					// load JSON data.
  					fd.loadJSON(json);
  					// compute positions incrementally and animate.
  					fd.computeIncremental({
    						iter: 40,
    						property: 'end',
    						onStep: function(perc){
      								Log.write(perc + '% loaded...');
    						},
    						onComplete: function(){
      								Log.write('');
     
      								fd.animate({
        									modes: ['linear'],
        									transition: $jit.Trans.Elastic.easeOut,
        									duration: 2500
      								});
    						}
  					});
  				// end
	
				}
			}
 		}
 			
		txtFile.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		txtFile.send(data);	
 }						


