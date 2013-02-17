/*===============ページ構築後外部js取得用関数 UseFree */
/*==========================================================
 Win   n4 n6 n7 moz e4 e5 e6,
 Mac   n4 n6 n7 moz e4.5 e5,
 Linux n4 n6 n7 moz         
============================================================
 書式
 dynamicLoad('外部jsファイル名')
 使用例  
 dynamicLoad('http://game.gr.jp/test.js')

 通常は外部jsファイルはページ構築後にロードできませんが
 この関数は上記のブラウザで、たとえばPerlのrequireなど
 のように必要な時に呼び出して使えるようにするためのもの。
 これによって、必要な最小限のソースだけを必要な時に
 ロードして使えるようになります。

 更新履歴

 * 2002.7.17 Mozの仕様変更?へ対応
   http://bugzilla.mozilla.org/show_bug.cgi?id=26790
   ThanxはぎさんShimonoさん

 Support http://game.gr.jp/mag2p/2/loadjs/loadjs.htm
==========================================================*/

function dynamicLoad(jsFileName){

  //--for cache 
  var now = new Date();
  var getData = jsFileName+'&nc='+now.getTime();

  if( document.all ){ 

     if( navigator.userAgent.indexOf("Win")!=-1 ){

       //--for document.all && Win
       eval(document.all('dynld')).src = getData;

     } else if( navigator.userAgent.indexOf("Mac")!=-1 ){

       //--for document.all && Mac
       document.body.insertAdjacentHTML('BeforeEnd'
        ,'<scr'+'ipt src="'+getData+'"><scr'+'ipt/>');

     }

  } else if(document.getElementById){

    //--for w3c-dom  widthout document.all 
    var cnode =document.getElementById('dynld');
    var nnode = document.createElement('script');
    nnode.src = getData;
    nnode.id  = 'dynld';
    cnode.parentNode.replaceChild(nnode,cnode);

  } else {

    //--for nondhtml(n4...)
    if(document.images){
      var datasrc   = new Image();
      datasrc.src   = getData;
      location.href = datasrc.src;
    }

  }
}
document.write('<scr'+'ipt id="dynld"></scr'+'ipt>');

/*====================ページ構築後外部js取得用関数ここまで*/