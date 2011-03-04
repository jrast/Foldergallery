    //a big thank to JavaScript Kit (http://www.javascriptkit.com)
    //for a valid implementation of external css sheet
    function loadjscssfile(filename, filetype){
        if (filetype=="js"){ //if filename is a external JavaScript file
            var fileref=document.createElement('script')
            fileref.setAttribute("type","text/javascript");
            fileref.setAttribute("src", filename);
        }
        else if (filetype=="css"){ //if filename is an external CSS file
            var fileref=document.createElement("link")
            fileref.setAttribute("rel", "stylesheet");
            fileref.setAttribute("type", "text/css");
            fileref.setAttribute("href", filename);
        }
        if (typeof fileref!="undefined") {
            document.getElementsByTagName("head")[0].appendChild(fileref);
        }
    }


    var filesadded="" //list of files already added

    function checkloadjscssfile(filename, filetype){
        if (filesadded.indexOf("["+filename+"]")==-1){
            loadjscssfile(filename, filetype)
            filesadded+="["+filename+"]" //add to list of files already added, in the form of "[filename1],[filename2],etc"
        }
        else {
            alert("file already added!");
        }
    }