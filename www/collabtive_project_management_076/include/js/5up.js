/**
 *
 * @access public
 * @return void
 **/
function finisher(){
blindtoggle('form_file');
toggleClass('addfile','addfile-active','addfile');
toggleClass('add_file_butn','butn_link_active','butn_link');
toggleClass('sm_files','smooth','nosmooth');
changeFileview($('fileviewtype').value);
$("statusrow").setAttribute("style","display:none;");
$("fileInfo1").innerHTML = "";
systemMsg("fileadded");
}
function checkCompat(){
try{
		formDataChk = new FormData();
	}
	catch(e)
	{
		formDataChk = false;
	}
	try{
		xhrChk = new XMLHttpRequest();
	}
	catch(e)
	{
		xhrChk = false;
	}

	if(xhrChk && formDataChk)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function getSelectedOptions(oList)
{
   var sdValues = [];
   for(var i = 1; i < oList.options.length; i++)
   {
      if(oList.options[i].selected == true)
      {
      sdValues.push(oList.options[i].value);
      }
   }
   return sdValues;
}
function html5up(theFiles,theInfoEl,theIndicator,theTarget)
{
	//File upload element
	this.theFiles = document.getElementById(theFiles);
	//HTML Element where the file info / list is displayed
	this.infoEl = document.getElementById(theInfoEl);
	//script where the upload is sent
	this.theTarget = theTarget;
	//HTML Element where the upload progress is displayed
	indicator = document.getElementById(theIndicator);
}

//gets information about the files selected in theFiles and prints them to an HTML element
html5up.prototype.fileInfo = function()
{
   var theFiles = this.theFiles.files;
   //String to be written to the HTML element where the fileinfo is displayed
   var outstr = "";

   outstr += "<ol style = 'margin-left:120px;'>";
   //loop through all the files from the file input field
   for(var i=0;i<theFiles.length;i++)
   {
	   if (theFiles[i]) {
	      //Determine the size of the file
		  var fileSize = 0;
	      if (theFiles[i].size > 1024 * 1024)
	      {
	        fileSize = (Math.round(theFiles[i].size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
	      }
		  else
	      {
		    fileSize = (Math.round(theFiles[i].size * 100 / 1024) / 100).toString() + 'KB';
		  }

		//Write a list item and the filename into the file info
		outstr += "<li>" + theFiles[i].name + "</li>";
	    }
    }
	outstr += "</ol>";
	//Write the file info to the HTML element
	this.infoEl.innerHTML = outstr;
}

//uploads files to the script specified in theTarget
html5up.prototype.upload = function() {
	var theFiles = this.theFiles.files;
	var myData = new FormData();
	var upfolder = $('upfolder').value;
	var sendtos = getSelectedOptions($('sendto'));
	myData.append("upfolder",upfolder);

	//Loop through the files and create an upload form object
	for(var i = 0;i<theFiles.length;i++)
	{
		myData.append("html5upFile"+i, theFiles[i]);
	}

	for(var i=0;i<sendtos.length;i++)
	{
		myData.append("sendto[]",sendtos[i]);
	}
	//console.log(sendtos);
	//Create new XMLHttpRequest
	var xhr = new XMLHttpRequest();

	//Add event listeners
 	xhr.upload.addEventListener("progress", this.progress, false);
    xhr.addEventListener("load", this.complete, false);
    xhr.addEventListener("error", this.failed, false);
    xhr.addEventListener("abort", this.canceled, false);

	//Open the connection
	xhr.open("POST",this.theTarget,true);
    //Send the upload form with the files
	xhr.send(myData);

}

//check if the browser can support 5up.js
html5up.prototype.checkBrowser = function()
{
	try{
		formDataChk = new FormData();
	}
	catch(e)
	{
		formDataChk = false;
	}
	try{
		xhrChk = new XMLHttpRequest();
	}
	catch(e)
	{
		xhrChk = false;
	}

	if(xhrChk && formDataChk)
	{
		return true;
	}
	else
	{
		return false;
	}


}

//Event handlers
//display progress. you can overwrite this on runtime to implement different display options
html5up.prototype.progress = function(evt) {
	if (evt.lengthComputable) {
        //Compute the progress percentage
		var percentComplete = Math.round(evt.loaded * 100 / evt.total);
        var total = percentComplete.toString();
        //Update the indicator Element with the percentage
		//indicator.innerHTML  =  total + "%";
        indicator.setAttribute("style","width:"+total + "%");
		//document.title = total + "%";
       }
       else {
         indicator.innerHTML  = "N/A";
       }
}

//this is fired when the UL is complete
html5up.prototype.complete = function(evt) {
		//console.log(evt.target.responseText);

        indicator.setAttribute("style","width:100%");
        window.setTimeout("finisher()",900);
        //document.title = "100%";
      }

//fired when there is an error
html5up.prototype.failed = function(evt) {
        //console.log(evt.target.responseText);
      }
//fired when the upload has been canceled / connection lost
html5up.prototype.canceled = function(evt) {
        alert("The upload has been canceled.");
      }

