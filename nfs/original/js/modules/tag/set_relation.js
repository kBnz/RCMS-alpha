function ajaxTagSetRelationProc(queryString, id){

    var tag_set_relation_input_dom = "tag_set_relation_input_" + id;
    $(tag_set_relation_input_dom).innerHTML = 'loading..';
    var a = new Ajax.Request(
        "/direct/tag/set_relation/",
        {
            "method": "post",
            "parameters": queryString,
            onComplete: function(request) {
                eval("var json = " + request.responseText);
                if (json.related==true){
                    $(tag_set_relation_input_dom).innerHTML = '<a href="javascript:void(0);" onclick="tag_set_relation(' + id + ',\'2\',\'' + json.msg + '\');return false;">[' + json.label +']</a>';
                }else{
                    $(tag_set_relation_input_dom).innerHTML = '<a href="javascript:void(0);" onclick="tag_set_relation(' + id + ',\'1\',\'' + json.msg + '\');return false;">[' + json.label +']</a>';
                }
            }
        }
    );
}

// kbn=1Åcí«â¡ kbn=2ÅcçÌèú
function tag_set_relation(id, kbn, complete_msg){
    var queryString = '?id=' + id + '&kbn=' + kbn;
    ajaxTagSetRelationProc(queryString, id);
    alert(complete_msg);
}
