<?php
class Smarty_Resource_Email extends Smarty_Resource_Custom {
 // prepared fetch() statement
 protected $fetch;
 // prepared fetchTimestamp() statement
 protected $mtime;

 /**
  * Fetch a template and its modification time from database
  *
  * @param string $name template name
  * @param string $source template source
  * @param integer $mtime template modification timestamp (epoch)
  * @return void
  */

 protected function fetch($name, &$source, &$mtime) {
  	$tpl_field=before('/',$name);
		$tpl_name=after('/',$name);

    // do database call here to fetch your template,
    // populating $tpl_source

    $row=DB::row("select `tpl_$tpl_field`,`last_update`
                   from `email_templates`
                  where `tpl_name`=:tpl_name AND `lang`=:language",array(':tpl_name'=>$tpl_name,':language'=>lang::$language));
		if (count($row)==0) {
    	$row=DB::row("select `tpl_$tpl_field`,`last_update`
                   from `email_templates`
                  where `tpl_name`=:tpl_name AND `lang`=:language",array(':tpl_name'=>$tpl_name,':language'=>lang::$default_lang));
		}

     if ($row) {
         $source = $row["tpl_$tpl_field"];
//         $row['last_update']='2015-10-16 00:00:00';
//         $mtime = strtotime($row['last_update']);
         $mtime = $row['last_update'];
     } else {
         $source = null;
         $mtime = null;
     }
}

}
?>