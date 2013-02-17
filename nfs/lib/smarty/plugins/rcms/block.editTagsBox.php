<?php
function smarty_block_editTagsBox($params, $content, &$smarty) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    global $cn;
    global $module_installed;
    global $requestContext;

    if (!$module_installed['tag']) {
        return "";
    }

    $tr = RCMS_Translate::getInstance($requestContext['locale']);
    $docmeta = $params['docmeta'];
    $bag = $docmeta['bag'];
    $action = $docmeta['action'];
    $lang = $docmeta['lang'];
    $AddAuth = $action->getResourceAuth('/tag/item/', 'insert', $lang);

    // 全てのタグを取得
    $strSQL = "SELECT * FROM t_tag WHERE dflg = 0 ORDER BY tag_id ";
    $result = selectQuery($cn, $strSQL);
    $tag_list = array();
    while ($row = getRow($result)){
        $tag_bag = UpdateHistory::createBag($cn, 'tag', $row['tag_id'], $lang);
        $row = $tag_bag->getHistory('t_tag', $row,null,true);
        $tag_list[$row['tag_id']] = $row['tag_nm'];
    }

    $relational_tag_list = array();
    if ($action->moduleId()) {
    // このコンテンツに関連しているタグを取得
        $wk_list = RCMSRelation::selectAll($cn, $action->moduleId(), $bag->bagName(), array('tag'));
        foreach($wk_list as $r_tag_data){
            $relational_tag_list[] = $r_tag_data["tag_id"];
        }
    }

    $html.= '<div id="edit-publish" class="entry-option"><dl class="entry-option-open"><dt class="entry-option-title"><h4>'.$tr->translate('/modules/common/label/tag_input_area').'</h4><p class="switch">▼</p></dt><dd class="entry-option-content">';

    $inputted = '';
    $tagNames = '';
    if (count($tag_list>0)){

        foreach($tag_list as $tag_id => $tag_nm){
            if (in_array($tag_id, $relational_tag_list)){
                $tagIcon.='<span class="tag" id="tag_id_'.$tag_id . '" onclick="choiceTag(this,'.$tag_id.',\''.$tag_nm.'\');">'.$tag_nm.'</span>';
                $inputted.='<input type="hidden" class="relational_tag'.$tag_id.'" id="relational_tag'.$tag_id.'" name="tag_relation[]" value="tag:'.$tag_id.':'.$tag_nm.'" />';
                $tagNames.= "<span id=\"selected_tag{$tag_id}\" class=\"tag\">[{$tag_nm}]</span>";
            }else{
                $tagIcon.='<span class="not_tag" id="tag_id_'.$tag_id . '" onclick="choiceTag(this,'.$tag_id.',\''.$tag_nm.'\');">'.$tag_nm.'</span>';
            }
        }

    }

    // 選択済みのタグ名
    $html.= '<span class="tags_title">'.$tr->translate('/modules/tag/label/tag_you_selected').'</span><div class="clearFloat"><br /></div>';

    if (!$tagNames){
        $tagNames = '<span class="tag" id="selected_tag0">'.$tr->translate('/modules/tag/msg/please_set_new_tags').'</span>';
    }

    $html.= '<div class="tags_seleted" id="tags_seleted">'.$tagNames.'</div><div class="clearFloat"><br /></div>';

    $html.= '<span class="tags_title">'.$tr->translate('/modules/tag/label/tag_you_have_not_selected').'</span>';
    $html.= '<div class="clearFloat"><br /></div>';

    $html.= '<div class="tagsinput" id="tagsinput">';
    // 選択候補のタグ名
    $html.= $tagIcon;
    if ($AddAuth->isAble()) {
    // タグ追加権限あり
        $html.='<span class="add" id="btnAddTag" onclick="addTag();">'.$tr->translate('/modules/tag/label/add_tag').'</span>';
    }
    $html.= '</div>';

    // 追加済みタグ
    $html.= '<div id="tags_inputted">'.$inputted.'</div>';

    $html.= '<span class="hint">'.$tr->translate('/modules/tag/msg/about_tag_detail').'</span>';

    if ($AddAuth->isAble()) {
        if( admin_v2() ){
            $html.= '<script type="text/javascript" src="/js/jquery/jquery-ui-1.7.2.min.js"></script>';
        }
        $html.= '<script src="/js/jqueryAlerts/jquery.alerts.js" type="text/javascript"></script>';
        $html.= '<link href="/js/jqueryAlerts/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen,print" />';
    }

    // タグ選択処理
    $html.= '<script>';
    $html.= 'var inputarea = j$("#tagsinput");';
    $html.= 'var inputted = j$("#tags_inputted");';
    $html.= 'var selected = j$("#tags_seleted");';
    $html.= 'function choiceTag(obj, tag_id, tag_nm){';
    $html.= 'if (obj.className=="tag"){';
    $html.= 'obj.className="not_tag";';
    $html.= 'var pNodeId = "#relational_tag" + tag_id;';
    $html.= 'j$(pNodeId).remove();';
    $html.= 'var pNodeId = "#selected_tag" + tag_id;';
    $html.= 'j$(pNodeId).remove();';
    $html.= 'if (selected.html()==""){';
    $html.= 'selected.html("<span class=\"tag\" id=\"selected_tag0\">'.$tr->translate('/modules/tag/msg/please_set_new_tags').'</span>");';
    $html.= '}';
    $html.= '}else{';
    $html.= 'var pNodeId = "#selected_tag0";';
    $html.= 'j$(pNodeId).remove();';
    $html.= 'obj.className="tag";';
    $html.= 'inputted.html(inputted.html() + "<input type=\"hidden\" class=\"relational_tag" + tag_id + "\" id=\"relational_tag" + tag_id + "\" name=\"tag_relation[]\" value=\"tag:" + tag_id + ":" + tag_nm + "\" />");';
    $html.= 'selected.html(selected.html() + "<span class=\"tag\" id=\"selected_tag" + tag_id + "\">[" + tag_nm +"]</span>");';
    $html.= '}';
    $html.= '}';

    // タグ追加処理
    if ($AddAuth->isAble()) {
        $html.= 'function addTag(){';
        $html.= 'jPrompt(';
        $html.= '"",';
        $html.= '"",';
        $html.= '"'.$tr->translate('/modules/common/label/tag_input_area').'",';
        $html.= 'function(rtn){if(rtn){';
        $html.= 'var queryString = "?MODE=ADD&addtag=" + encodeURI(rtn);';
        $html.= 'var a = new Ajax.Request( ';
        $html.= '"/direct/tag/addtag/", ';
        $html.= '{';
        $html.= '"method": "post", ';
        $html.= '"parameters": queryString, ';
        $html.= 'onComplete: function(request) { ';
        $html.= 'eval("var json = " + request.responseText);';
        $html.= 'j$("#btnAddTag").remove();';
        $html.= 'inputarea.html(inputarea.html() + "<span class=\"tag\" onclick=\"choiceTag(this," + json.tag_id + ",\'" + json.addtag + "\');\">" + json.addtag + "</span>" );';
        $html.= 'inputarea.html(inputarea.html() + "<span class=\"add\" id=\"btnAddTag\" onclick=\"addTag();\">'.$tr->translate('/modules/tag/label/add_tag').'</span>");';
        $html.= 'inputted.html(inputted.html() + "<input type=\"hidden\" class=\"relational_tag" + json.tag_id + "\" id=\"relational_tag" + json.tag_id + "\" name=\"tag_relation[]\" value=\"tag:" + json.tag_id + ":" + json.addtag + "\" />");';
        $html.= 'selected.html(selected.html() + "<span class=\"tag\" id=\"selected_tag" + json.tag_id + "\">[" + json.addtag + "]</span>");';
        $html.= '}';
        $html.= '}';
        $html.= ');';
        $html.= '}}';
        $html.= ');';
        $html.= '}';
    }

    $html.= '</script></dd></dl></div>';

    return $html;
}
