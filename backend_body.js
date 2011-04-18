$(document).ready(function() {
    $.insert(WB_URL+'/include/jquery/jquery-ui-min.js');
    $.insert(WB_URL+'/modules/foldergallery/scripts/jcrob/js/jquery.Jcrop.min.js');
    $.insert(WB_URL+'/modules/foldergallery/admin/scripts/uploadify/swfobject.js');
    $.insert(WB_URL+'/modules/foldergallery/admin/scripts/uploadify/jquery.uploadify.v2.1.4.min.js');
});

$(document).ready(function(){ 
    $(function() {
        $("#dragableTable ul").sortable({
            opacity: 0.6,
            cursor: 'move',
            update: function() {
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings&parent_id='+the_parent_id;
                $.post(WB_URL+"/modules/foldergallery/admin/scripts/reorderDND.php", order, function(theResponse){
                    $("#dragableResult").html(theResponse);
                });
            }
        });
    });
}); 
 
$(document).ready(function(){ 
    $(function() {
        $("#dragableCategorie ul").sortable({
            opacity: 0.6,
            cursor: 'move',
            update: function() {
                var order = $(this).sortable("serialize") + '&action=updateRecordsListings&parent_id='+the_parent_id;
                $.post(WB_URL+"/modules/foldergallery/admin/scripts/reorderCNC.php", order, function(theResponse){
                    $("#dragableResult").html(theResponse);
                });
            }
        });
    });
});


// Intialize jCrop only if needed (means settingsRatio is defined
if(!(typeof(settingsRatio) == 'undefined')) {
    // Remember to invoke within jQuery(window).load(...)
    // If you don't, Jcrop may not initialize properly
    $(window).load(function(){
	
        $('#cropbox').Jcrop({
            onChange: showPreview,
            onSelect: updateCoords,
            aspectRatio: settingsRatio
        });

    });

    function showPreview(coords)
    {
        var imgWidth = $("#cropbox").width();
        var scale = relWidth / imgWidth;
	
        if  (settingsRatio > 1) {
            var rx = thumbSize / coords.w;
            var ry = thumbSize / settingsRatio / coords.h;
        }
        else {
            var rx = thumbSize * settingsRatio / coords.w;
            var ry = thumbSize / coords.h;
        }
	
        $('#preview').css({
            width: Math.round(rx * relWidth / scale) + 'px',
            height: Math.round(ry * relHeight / scale) + 'px',
            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
            marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });

    };


    function updateCoords(c)
    {
        var imgWidth = $("#cropbox").width();
        var scale = relWidth / imgWidth;

        $('#x1').val(c.x * scale);
        $('#y1').val(c.y * scale);
        $('#x2').val(c.x2 * scale);
        $('#y2').val(c.y2 * scale);
    };

    function checkCoords()
    {
        if (parseInt($('#y2').val())) return true;
        alert('Please select a crop region then press submit.');
        return false;
    };

}

// Function to toggle active/inavtive of a categorie in the overview
function toggle_active_inactive(id) {
    var img = $("#i" + id);
    if( img.attr("src") == fg_url+"/images/active1.gif") {
        action = "disable";
        var src = fg_url+"/images/active0.gif";
    } else {
        action = "enable";
        var src = fg_url+"/images/active1.gif";
    }
    $.ajax({
        url: fg_url+"/admin/scripts/cat_switch_active_inactive.php",
        type: "POST",
        data: 'cat_id='+id.substr(1)+'&action='+action,
        dataType: 'json',
        success: function(data) {
            if(data.success == "true") {
                img.attr("src", src);
                img.attr("title", data.message);
            } else {
                alert(data.message);
            }
        },
        complete: function() {}        
    });
}
// End of jCrop


/* This is only needed for the modify_cat view */
if(!(typeof(upSettings) == 'undefined')) {
    $(document).ready(function() {
        $('#file_upload').uploadify({
            'uploader'          : WB_URL+'/modules/foldergallery/admin/scripts/uploadify/uploadify.swf',
            'script'            : WB_URL+'/modules/foldergallery/admin/scripts/upload.php',
            'checkScript'       : WB_URL+'/modules/foldergallery/admin/scripts/upload_check.php',
            'cancelImg'         : WB_URL+'/modules/foldergallery/admin/scripts/uploadify/cancel.png',
            'folder'            : upSettings.folder,
            'scriptData'        : {
                'secCheck': upSettings.data
                },
            'auto'              : true,
            'multi'             : true,
            'simUploadLimit'    : 1,
            'buttonText'        : 'Browse',
            'width'             : 250,
            'removeCompleted'   : true,
            'queueID'           : 'FG_queue',
            onComplete          : function(event, ID, fileObj, response, data) {
                $(response).appendTo($('#FG_table'));
            },
            'onCheck'           : function(event,data,key) {
                $('#file_upload' + key).find('.percentage').text(' - Exists');
            }
        })
    });
}

$("#loadPreset").change(function () {
    var value = $(this).val();
    $.getJSON(
        WB_URL + '/modules/foldergallery/admin/scripts/getThumbPreset.php',
        'preset=' + value,
        function(data) {
            $("#size_x").attr("value", data.image_x);
            $("#size_y").attr("value", data.image_y);
            if(typeof(data.image_ratio) =='undefined' || data.image_ratio == 'false') {
                $('#thumb_cut').attr('checked', true);
            } else {
                $('#thumb_keep').attr('checked', true);
            }
            if(typeof(data.image_background_color) == 'undefined') {
                $('#background_color').attr("value", "#FFFFFF");
            } else {
                $('#background_color').attr("value", data.image_background_color);
            }
            var ratio = Math.round((data.image_x/data.image_y)*100)/100;
            setRatio(ratio);
            // Delete unused elements:
            delete(data.image_x); delete(data.image_y); delete(data.image_ratio); delete(data.description); delete(data.image_background_color);
            var out = '';
            for(i in data) {
                out = out + i + '=' + data[i] + '\n';
            }
            $("#thumb_advanced").val(out);
        }
    );
});

$("#size_x").blur(function() {
    var value = $(this).val();
    var ratio = $("input[name='thumb_ratio']:checked").val();
    if(ratio == 'undefined' || ratio == 'free') {
     // Do nothing
    } else {
        $("#size_y").attr("value", Math.round(value/ratio));
    }
});

$("#size_y").blur(function() {
    var value = $(this).val();
    var ratio = $("input[name='thumb_ratio']:checked").val();
    if(ratio == 'undefined' || ratio == 'free') {
     // Do nothing
    } else {
        $("#size_x").attr("value", Math.round(value*ratio));
    }
});

$(document).ready(function() {
    if($('#size_x') != 'undefined') {
        var ratio = Math.round(($('#size_x').val()/$('#size_y').val())*100)/100;
        setRatio(ratio);

    }
});

function setRatio(ratio) {
    switch (ratio) {
        case 1:
            $("input[name='thumb_ratio'][value='1']").attr('checked', true);
            break;
        case 1.34:
            $("input[name='thumb_ratio'][value='1.34']").attr('checked', true);
            break;
        case 0.75:
            $("input[name='thumb_ratio'][value='0.75']").attr('checked', true);
            break;
        case 1.79:
            $("input[name='thumb_ratio'][value='1.79']").attr('checked', true);
            break;
        case 0.56:
            $("input[name='thumb_ratio'][value='0.56']").attr('checked', true);
            break;
        default:
           $("input[name='thumb_ratio'][value='free']").attr('checked', true);
    }
}
