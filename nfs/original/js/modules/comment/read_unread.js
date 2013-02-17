function ajaxCommentReadProc(queryString, module, id, complete_msg){

    var read_unread_input_dom = "read_unread_input_" + module + "_" + id;
    $(read_unread_input_dom).innerHTML = 'loading..';
    var a = new Ajax.Request(
        "/direct/comment/read_unread/",
        {
            "method": "post",
            "parameters": queryString,
            onComplete: function(request) {
                eval("var json = " + request.responseText);
                var good_count = json.good_count ? json.good_count : 0;
                var bad_count = json.bad_count ? json.bad_count : 0;
                if (json.readed==true){
                    $(read_unread_input_dom).innerHTML = '[' + json.label + ']';
                }else{
                    $(read_unread_input_dom).innerHTML = '<a href="javascript:void(0);" onclick="read_unread(\'' + module + '\', ' + id + ',\''+complete_msg+'\');return false;">[' + json.label +']</a>';
                }
            }
        }
    );
}

function read_unread(module, id, complete_msg){
    var queryString = '?module=' + module + '&id=' + id + '&MODE=ADD';
    ajaxCommentReadProc(queryString, module, id, complete_msg);
    alert(complete_msg);
}
