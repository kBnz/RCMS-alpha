function ajaxCommentFootProc(queryString, module, id,point_str,complete_msg){

    var footprint_input_dom = "footprint_input_" + module + "_" + id;
    $(footprint_input_dom).innerHTML = 'loading..';
    var a = new Ajax.Request( 
        "/direct/comment/comment_footprint/", 
        { 
            "method": "post", 
            "parameters": queryString, 
            onComplete: function(request) { 
                eval("var json = " + request.responseText);
                var good_count = json.good_count ? json.good_count : 0;
                var bad_count = json.bad_count ? json.bad_count : 0;
                if (json.footprinted==true){
                    $(footprint_input_dom).innerHTML = '<img src="/images/modules/comment/comment_good.gif" alt="Good!">' + good_count + point_str + ' <img src="/images/modules/comment/comment_bad.gif" alt="Bad!">' + bad_count + point_str; 
                }else{
                    $(footprint_input_dom).innerHTML = '<a href="javascript:void(0);" onclick="footPrint(\'' + module + '\', ' + id + ',\'good\',\''+point_str+'\',\''+complete_msg+'\');return false;"><img src="/images/modules/comment/comment_good.gif" alt="Good!"></a>' + good_count + point_str + '<a href="javascript:void(0);" onclick="footPrint(\'' + module + '\', ' + id + ',\'bad\',\''+point_str+'\',\''+complete_msg+'\');return false;"><img src="/images/modules/comment/comment_bad.gif"  alt="Bad!"></a>' + bad_count + point_str; 
                }
            } 
        } 
    ); 
}

function footPrint(module, id, kbn,point_str,complete_msg){
    var queryString = '?module=' + module + '&id=' + id + '&kbn=' + kbn + '&MODE=ADD';
    ajaxCommentFootProc(queryString, module, id,point_str,complete_msg);
    alert(complete_msg);
}
