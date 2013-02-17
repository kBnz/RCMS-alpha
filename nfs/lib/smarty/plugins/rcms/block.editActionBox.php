<?php
function smarty_block_editActionBox($params, $content, &$smarty) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    $docmeta = $params['docmeta'];
    $bag = $docmeta['bag'];
    $action = $docmeta['action'];
    $lang = $docmeta['lang'];

    $idPrefix = isset($params['idPrefix']) ? $params['idPrefix'] : 'editActionBox_';
    $id_delete = $idPrefix . '_delete';

    $canPreview = method_exists($action, 'saveDraft');
    $myHandler = false;

    // Insert,Update,Deleteの各タイミングで、各モジュールが独自でHandleさせたいJavascriptのFunction名
    $onInsertHandler = $params['onInsertHandler'];
    $onUpdateHandler = $params['onUpdateHandler'];
    $onDeleteHandler = $params['onDeleteHandler'];

    // 更新処理時、各モジュール独自のボタンをAssignする
    $CustomButton = $smarty->get_template_vars('_editActionBoxOnUpdateCustomButton_');
    $CustomButton = ($CustomButton) ? join("\n", $CustomButton) : "";

    global $requestContext;
    $tr = RCMS_Translate::getInstance($requestContext['locale']);

    $html = '';
    $add_html = '';

    if ($action->moduleId()) {
        $bag = $action->getBag();
        // 既存
        if ($docmeta['is_waiting']) {
        // これは承認待ちドキュメント
            $auth = $action->getResourceAuth($bag->getTarget(), 'update', $lang);
            var_dump("step1".$auth->isAble());
            if ($auth->isAble()) {
                if ($auth->isUnlimited()) {
                // 許可する権限がある
                    $html .= "<li id=\"edit_action_accept_li\"><button class=\"rcms_btn primary large\" type=\"submit\" onclick=\"this.form.MODE.value='ACCEPT';\"><span class=\"icon-check icon-white\"></span>" . $tr->translate('/label/accept') . "</button></li>";
                    if ($onUpdateHandler){
                    // Handleするfuncitonの指定がある場合
                        $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"{$onUpdateHandler}\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                    }else{
                        $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"this.form._doc_waiting.value='1';this.form.MODE.value='UPDATE';\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                        $add_html .= "<input type=\"hidden\" name=\"_doc_waiting\" value=\"\">";
                    }
                    $html .= "<li id=\"edit_action_reject_li\"><button class=\"rcms_btn warning large\" type=\"submit\" onclick=\"this.form.MODE.value='REJECT';\"><span class=\"icon-reject icon-white\"></span>" . $tr->translate('/label/reject') . "</button></li>";
                    if ($canPreview) {
                        $html .= "<li id=\"edit_action_preview_li\"><button class=\"rcms_btn large\" type=\"button\" onclick=\"goPreview(this.form)\">" . $tr->translate('/label/preview_btn') . "</button></li>";
                    }
                }
                elseif ($docmeta['own']) {
                // 自分が作成したので再修正できる

                    if ($onUpdateHandler){
                    // Handleするfuncitonの指定がある場合
                        $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"{$onUpdateHandler}\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                    }else{
                        $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"this.form.MODE.value='UPDATE';\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                    }
                    if ($canPreview) {
                        $html .= "<li id=\"edit_action_preview_li\"><button class=\"rcms_btn large\" type=\"button\" onclick=\"goPreview(this.form)\">" . $tr->translate('/label/preview_btn') . "</button></li>";
                    }
                    if ($CustomButton){
                    // 各モジュールごとに必要なボタンがある場合
                        $html .= "<li id=\"edit_action_custom_li\">{$CustomButton}</li>";
                    }
                }
                else {
                    //$html .= $tr->translate('/msg/not_allowed_to_data_operate'); // 権限は何もない
                }
            }
            else {
                if ($docmeta['own']) {
                // 自分が作成したので再修正できる
                    $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"this.form.MODE.value='UPDATE';\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                    if ($canPreview) {
                        $html .= "<li id=\"edit_action_preview_li\"><button class=\"rcms_btn large\" type=\"button\" onclick=\"goPreview(this.form)\">" . $tr->translate('/label/preview_btn') . "</button></li>";
                    }
                }
                else {
                    //$html .= $tr->translate('/msg/not_allowed_to_data_operate'); // 権限は何もない
                }
            }
        }
        else {
        // これは通常ドキュメント
            if ($docmeta['has_waiting']) {
            // 承認待ちドキュメントがある場合は何もできない
                $html .= $tr->translate('/msg/approval_waiting'); // 承認待ちのドキュメントがあるので、更新できません。
                if($_REQUEST["iframe_mode"] == 1){
                    $html .= '<p class="link"><a href="' . $docmeta['waiting_link'] . '&iframe_mode=1">' . $tr->translate('/msg/display_data_approval_waiting') . "</a><p>";
                }else if($_REQUEST["popup_mode"] == 1){
                    $html .= '<p class="link"><a href="' . $docmeta['waiting_link'] . '&popup_mode=1">' . $tr->translate('/msg/display_data_approval_waiting') . "</a><p>";
                }else{
                    $html .= '<p class="link"><a href="' . $docmeta['waiting_link'] . '">' . $tr->translate('/msg/display_data_approval_waiting') . "</a><p>";
                }
            }
            else {
            // 通常ドキュメントに対する更新処理
                $auth = $action->getResourceAuth($bag->getTarget(), 'update', $lang);
                var_dump($auth->isAble());
                if ($auth->isAble()) {

                    if ($onUpdateHandler){
                    // Handleするfuncitonの指定がある場合
                        $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"button\" onclick=\"{$onUpdateHandler}\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                    }else{
                        $html .= "<li id=\"edit_action_update_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"this.form.MODE.value='UPDATE';\"><span class=\"icon-refresh icon-white\"></span>" . $tr->translate('/label/update_btn') . "</button></li>";
                    }
                    if ($canPreview) {
                        $html .= "<li id=\"edit_action_preview_li\"><button class=\"rcms_btn large\" type=\"button\" onclick=\"goPreview(this.form)\">" . $tr->translate('/label/preview_btn') . "</button></li>";
                    }
                    if ($CustomButton){
                    // 各モジュールごとに必要なボタンがある場合
                        $html .= "<li id=\"edit_action_custom_li\">{$CustomButton}</li>";
                    }
                }
                $auth = $action->getResourceAuth($bag->getTarget(), 'delete', $lang);
                if ($auth->isAble()) {
                // 削除権限がある
                    if ($onDeleteHandler){
                    // Handleするfuncitonの指定がある場合
                        $html .= "<li id=\"edit_action_delete_li\"><button class=\"rcms_btn danger large\" type=\"button\" onclick=\"{$onDeleteHandler}\"><span class=\"icon-trash icon-white\"></span>" . $tr->translate('/label/delete_btn') . "</button></li>";
                    }else{
                        $html .= "<li id=\"edit_action_delete_li\"><button class=\"rcms_btn danger large\" type=\"button\" onclick=\"confirmDelete(this.form)\"><span class=\"icon-trash icon-white\"></span>" . $tr->translate('/label/delete_btn') . "</button></li>";
                        $myHandler = true;
                    }
                }
            }
        }
    }
    else {
    // 新規
        $auth = $action->getResourceAuth($action->getActionConfig('PACKAGE_TARGET'), 'insert', $lang);
        if ($auth->isAble()) {
            if ($onInsertHandler){
            // Handleするfuncitonの指定がある場合
                $html .= "<li id=\"edit_action_insert_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"{$onInsertHandler}\"><span class=\"icon-add icon-white\"></span>" . $tr->translate('/label/add_btn') . "</button></li>";
            }else{
                $html .= "<li id=\"edit_action_insert_li\"><button class=\"rcms_btn success large\" type=\"submit\" onclick=\"this.form.MODE.value='INSERT'\"><span class=\"icon-add icon-white\"></span>" . $tr->translate('/label/add_btn') . "</button></li>";
            }
            if ($canPreview) {
                $html .= "<li id=\"edit_action_preview_li\"><button class=\"rcms_btn large\" type=\"button\" onclick=\"goPreview(this.form)\">" . $tr->translate('/label/preview_btn') . "</button></li>";
            }
        }
    }

    $script = "";

    if ($canPreview) {
        $previewURL = ROOT_SSL_URL."/management/{$action->mt}/{$action->ct}/?preview=t";

        // Spawがfrom.onsubmitイベントを必要としていますが、form.submit()ではform.onsubmitイベントが発生しません。
        // form.onsubmitイベントを発生させるために一時的にsubmitボタンを生成します。

        $script .= <<< _SCRIPT_

function goPreview(f) {
    var new_win = window.open("about:blank","_PREVIEW_","menubar=no,toolbar=no,location=no,resizable=yes,scrollbars=yes");
    new_win.focus();

    var action = f.action;
    var target = f.target;
    f.action = "{$previewURL}";
    f.target = "_PREVIEW_";

    var btn = document.createElement('input');
    btn.type = 'submit';
    btn.style.display = 'none';
    f.appendChild(btn);
    btn.click(); // raise onsubmit
    //f.removeChild(btn);  // ieでダメ
    btn.parentNode.removeChild(btn);

    f.action = action;
    f.target = target;
}

_SCRIPT_;
    }

    if ($myHandler) {
        // 削除メッセージ
        $msg = $params['confirmMessage'];
        if (!$msg) {
            $msg = $tr->translate('/msg/confirm_del');
        }
        $script .= <<< _SCRIPT_

function confirmDelete(f){
    if (confirm('{$msg}')) {
        f.MODE.value='DELETE';
        f.submit();
    }
}

_SCRIPT_;
    }

    if ($script) {
        $script = "\n" . '<script type="text/javascript">' . $script . "</script>\n";
    }

    if($html){
        return '<div class="buttonbox"><ul id="buttonbox_ul">' . $html . '</ul></div>' . $add_html . $script . '<div class="clear"></div>';
    }else{
        return '<div class="buttonbox">' . $tr->translate('/msg/not_allowed_to_data_operate') . '</div><div class="clear"></div>';
    }
}
