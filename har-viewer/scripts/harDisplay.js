// For processing HAR file
var har = document.createElement("script");
har.src = "har.js";
har.setAttribute("id", "har");
har.setAttribute("async", "true");
document.documentElement.firstChild.appendChild(har);

function getHarFile(key, default_)
{
  if (default_==null) default_=""; 
  key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
  var qs = regex.exec(window.location.href);
  if(qs == null)
    return default_;
  else
    return qs[1] + ".har";
}

// Load HAR
$("#content").bind("onViewerPreInit", function(event) {
    var viewer = event.target.repObject;
    var file = getHarFile('file', 'false');
    
    console.log("loading file: " + file);
    viewer.loadHar("har_files/" + file);
});
