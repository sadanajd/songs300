<?php
/**
 *  Allomani Audio & Video (Songs) v3.0
 * 
 * @package Allomani.Songs
 * @version 3.0
 * @copyright (c) 2006-2018 Allomani , All rights reserved.
 * @author Ali Allomani <info@allomani.com>
 * @link http://allomani.com
 * @license GNU General Public License version 3.0 (GPLv3)
 * 
 */

require("global.php");

require(CWD . "/includes/framework_start.php");   
//---------------------------------------------------------


 $id=(int) $id;
 $cat = (int) $cat;
 
          
  compile_hook('news_start');

if ($id){
    compile_hook('news_inside_start');
              $qr = db_query("select * from songs_news where id='$id'");
     if(db_num($qr)){
              $data = db_fetch($qr);
  
   if($data['cat']){$cat_name = db_qr_fetch("select name from songs_news_cats where id='$data[cat]'");}
  print "<span class='path'><img src=\"images/arrw.gif\">&nbsp;<a class='path_link' href=\"".$links['news']."\" title=\"$phrases[the_news]\">$phrases[the_news]</a> ".iif($cat_name['name']," $phrases[path_sep] <a class='path_link' href=\"".str_replace("{cat}",$data['cat'],$links['browse_news'])."\" title=\"$cat_name[name]\">$cat_name[name]</a>")." $phrases[path_sep] $data[title]<br><br></span>";
  
      open_table($data['title']);
     run_template('browse_news_inside');
     close_table();
     }else{
     open_table();
     print "<center>$phrases[err_wrong_url]</center>";
     close_table();
             }
   compile_hook('news_inside_end');
   
     //------ Comments -------------------
if($settings['enable_news_comments']){
    open_table($phrases['members_comments']);
    get_comments_box('news',$id);
    close_table();
}


        }else{
            
  if($cat){$cat_name = db_qr_fetch("select name from songs_news_cats where id='$cat'");}
  print "<span class='path'><img src=\"images/arrw.gif\">&nbsp;<a class='path_link' href=\"".$links['news']."\" title=\"$phrases[the_news]\">$phrases[the_news]</a> ".iif($cat_name['name']," $phrases[path_sep] <a class='path_link' href=\"".str_replace("{cat}",$cat,$links['browse_news'])."\" title=\"$cat_name[name]\">$cat_name[name]</a>")."<br><br></span>";
  

//---------- cats ------------//
$no_cats = false;
if(!$cat){
 $qr = db_query("select * from  songs_news_cats order by ord asc");
 if(db_num($qr)){

templates_cache(array('news_cats_header','news_cats_sep','news_cats','news_cats_footer'));
  
 run_template("news_cats_header");    

   $c = 0 ;
 
 while($data = db_fetch($qr)){
     
 if ($c==$settings['songs_cells']) {
run_template("news_cats_sep");  
$c = 0 ;
}
    ++$c ;
    
    
run_template('news_cats');
         }
run_template('news_cats_footer');


 }else{
     $no_cats = true;
 }
}else{
     $no_cats = true; 
}
//----------------------------//


  

     //----------------- start pages system ----------------------
 $start=(int) $start;
   $page_string= str_replace('{cat}',$cat,$links['browse_news_w_pages']);
   $news_perpage = intval($settings['news_perpage']);
   //----------------------------------------------------------
   
   
$qr = db_query("select * from songs_news where cat='$cat' order by id DESC limit $start,$news_perpage");


  if(db_num($qr)){
 
   //-------------------
   $page_result = db_qr_fetch("select count(*) as count from songs_news where cat='$cat'");  
   //--------------------------------------------------------------
   
 
     
  templates_cache(array('browse_news_header','news_cats_sep','browse_news','browse_news_footer'));
  
  
  run_template("browse_news_header");
      
    
 $c=0;
while ($data = db_fetch($qr)){
  
if ($c==$settings['songs_cells']) {
run_template("browse_news_sep");  
$c = 0 ;
}
    ++$c ;
    
    
   run_template('browse_news');
 }
 
   run_template('browse_news_footer');
  
  compile_hook('news_outside_before_pages');
//-------------------- pages system ------------------------
print_pages_links($start,$page_result['count'],$news_perpage,$page_string);
//------------ end pages system -------------

 
     }else{
         if($no_cats){
             open_table();
             print "<center>$phrases[no_news]</center>" ;
               close_table(); 
         }
   }
          


compile_hook('news_outside_end');
 }
   compile_hook('news_end');
   
//---------------------------------------------
require(CWD . "/includes/framework_end.php");                   
?>
