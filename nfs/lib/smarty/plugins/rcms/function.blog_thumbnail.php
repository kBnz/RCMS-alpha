<?php
function smarty_function_blog_thumbnail($params, &$smarty) {
    $id      = $params['id'];
    $blog_id      = $params['blog_id'];
    $default = $params['default'];
    
    $path = "";

    // サムネイルを探す
    if (isNumber($id) && isNumber($blog_id)) {
        $files = glob(BLOG_SAVE_DIR . "/thumbnails/{$blog_id}/{$id}.*");
        foreach ($files as $f) {
            $path = $f;
        }
    }

    // 代替パス
    if (!$path && $default) {
        if (is_public_file(ROOT_DIR, ROOT_DIR.'/html'. $default)) {
            $path = $default;
        }
    }

    return $path;
}
?>
