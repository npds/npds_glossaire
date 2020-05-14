<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module npds_glossaire v 3.0 pour revolution 16                       */
/* by team jpb/phr 2017                                                 */
/*                                                                      */
/* From Glossaire version 1.3 pour myPHPNuke 1.8                        */
/* Copyright © 2001, Pascal Le Boustouller                              */
/* Tribal-dolphin 2008                                                  */
/************************************************************************/

// cartouche de sécurité ==> requis !!
if (!strstr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

$f_meta_nom ='npds_glossaire';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

include ("modules/$ModPath/glossaire.conf.php");
include_once("modules/$ModPath/lang/glossaire-$language.php");

   GraphicAdmin($hlpfile);
   echo '
   <div id="adm_men">
   <h2><img src="modules/npds_glossaire/npds_glossaire.png" alt="icon_npds_glossaire" style="max-width:180px; max-height=180px;"><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart=admin/glossadmin">'.glo_translate("Glossaire").'</a></h2><hr />';

function admin_glo() {
   global $ModPath, $ModStart, $ok_submit, $activ_rech, $nb_affichage, $css, $NPDS_Prefix;
   $TableRep = sql_query("SELECT * FROM ".$NPDS_Prefix."td_glossaire WHERE affiche='0'");
   $avalider = sql_num_rows($TableRep);
   echo '<p class="lead"><a class="" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_list">'.glo_translate("Liste des définitions").'</a></p>';
if($avalider!=0) {
//Liste des demandes à valider
   echo '
   <p class="lead"><a data-toggle="collapse" href="#valglo" aria-expanded="true" aria-controls="valglo"><i data-toggle="tooltip" data-placement="top" title="'.glo_translate("Cliquer pour cacher ou déployer").'" class="toggle-icon fa fa-lg fa-caret-down"></i></a> '.glo_translate("Validation ou suppression des demandes").'<span class="badge badge-danger float-right">'.$avalider.'</span></p>
   <div id="valglo" class="collapse" role="tabpanel" aria-labelledby="">
      <table class="table table-hover table-sm">
         <thead>
            <tr>
               <th class="n-t-col-xs-2">ID</th>
               <th>Cat</th>
               <th>'.glo_translate("Terme").'</th>
               <th>'.glo_translate("Définition").'</th>
               <th class="n-t-col-xs-2">&nbsp;</th>
            </tr>
         </thead>
         <tbody>';
   while (list($id_terme,$gcat,$lettre,$terme,$terme_def) = sql_fetch_row($TableRep)) {
      echo '
            <tr>
               <td>'.$id_terme.'</td><td>'.$gcat.'</td>
               <td>'.$terme.'</td><td>'.$terme_def.'</td>
               <td class="text-right">
                  <span class="mx-1"><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_add&amp;id='.$id_terme.'" title="'.glo_translate("Valider").'" data-toggle="tooltip"><i class="far fa-check-square fa-lg" aria-hidden="true"></i></a></span>
                  <span class="mx-1"><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_supp&amp;id='.$id_terme.'"><i class="fas fa-trash fa-lg text-danger" title="'.glo_translate("Supprimer").'" data-toggle="tooltip"></i></a></span>
               </td>
            </tr>';
   }
   echo '
         </tbody>
      </table>
   </div>';}
//Soumettre une définition
   echo '
   <p class="lead"><a data-toggle="collapse" href="#soudef" aria-expanded="true" aria-controls="soudef"><i data-toggle="tooltip" data-placement="top" title="'.glo_translate("Cliquer pour cacher ou déployer").'" class="toggle-icon fa fa-lg fa-caret-down"></i></a> '.glo_translate("Soumettre une définition").'</p>
   <div id="soudef" class="collapse" role="tabpanel" aria-labelledby="">
      <form action="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'" method="post" name="adminForm">
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="terme">'.glo_translate("Terme").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="terme" name="terme" size="45" maxsize="100" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="gcategory">'.glo_translate("Catégorie").'</label>
            <div class="col-sm-4">
               <input class="form-control" type="text" id="gcategory" name="gcategory" size="25" maxlength="30" />
            </div>
            <div class="col-sm-4">
               <select class="custom-select" name="sgcategory">';
      $result = sql_query("SELECT DISTINCT gcat FROM ".$NPDS_Prefix."td_glossaire ORDER BY gcat");
      while (list($dcategory) = sql_fetch_row($result)) {
         $dcategory=stripslashes($dcategory);
         echo '
                  <option '.$sel.' value="'.$dcategory.'">'.$dcategory.'</option>';
      }
      echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-3" for="content">'.glo_translate("Définition").'</label>
            <div class="col-sm-8">
               <textarea class="form-control tin" rows="10" id="content" name="content"></textarea>';
         echo aff_editeur('content', '');
      echo '
            </div>
         </div>
         <div class="form-group row">
            <label class="col-form-label col-sm-3" for="xurl">'.glo_translate("Site internet").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="xurl" name="xurl" size="45" maxsize="255" />
               <span class="form-text text-muted small">exemple : http://npds.org</span>
            </div>
         </div>
         <input type="hidden" name="subop" value="admin_term" />
         <input class="btn btn-outline-primary btn-sm" type="submit" value="'.glo_translate("Valider").'" />
      </form>
   </div>';
//Configuration
      echo '
      <p class="lead"><a data-toggle="collapse" href="#confu" aria-expanded="true" aria-controls="confu"><i data-toggle="tooltip" data-placement="top" title="'.glo_translate("Cliquer pour cacher ou déployer").'" class="toggle-icon fa fa-lg fa-caret-down"></i></a> '.glo_translate("Configuration").'</p>
      <div id="confu" class="collapse" role="tabpanel" aria-labelledby="">
      <form action="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_settings" method="post">
         <div class="form-group row">
            <label class="col-form-label col-sm-3" for="nbaff_new">'.glo_translate("Définitions par page").'</label>
            <div class="col-sm-2">
               <input class="form-control" type="text" id="nbaff_new" name="nbaff_new" value="'.$nb_affichage.'" size="45" maxsize="3" />
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-3">'.glo_translate("Autorise la soumission").'</label>
            <div class="col-sm-9">
               <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="oksubmit_new_y" name="oksubmit_new" value="true"'.($ok_submit?' checked="checked"':'').' />
                  <label class="custom-control-label" for="oksubmit_new_y">'.glo_translate("Oui").'</label>
               </div>
               <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="oksubmit_new_n" name="oksubmit_new" value="false"'.(!$ok_submit?' checked="checked"':'').' />
                  <label class="custom-control-label" for="oksubmit_new_n">'.glo_translate("Non").'</label>
               </div>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-3">'.glo_translate("Autorise la recherche").'</label>
            <div class="col-sm-9">
               <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="activrech_new_y" name="activrech_new" value="true"'.($activ_rech?' checked="checked"':'').' />
                  <label class="custom-control-label" for="activrech_new_y">'.glo_translate("Oui").'</label>
               </div>
               <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="activrech_new_n" name="activrech_new" value="false"'.(!$activ_rech?' checked="checked"':'').' />
                  <label class="custom-control-label" for="activrech_new_n">'.glo_translate("Non").'
               </div>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-3 mr-sm-auto">
               <input class="btn btn-primary btn-sm" type="submit" value="'.glo_translate("Valider").'" />
            </div>
         </div>
         </form>
      </div>
      </div>';
}

// administration de la liste complète
function admin_list() {
   global $ModPath, $ModStart, $NPDS_Prefix;
   echo '
   <h4 class="lead">'.glo_translate("Liste des définitions dans la base de données").'</h4>
   <table class="table table-sm " data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th data-halign="center" data-sortable="true" class="n-t-col-xs-2">Cat</th>
            <th data-halign="center" data-sortable="true" class="n-t-col-xs-2">'.glo_translate("Terme").'</th>
            <th data-halign="center">'.glo_translate("Définition").'</th>
            <th data-halign="center" data-align="center" class="n-t-col-xs-2 align-middle">'.glo_translate("Fonctions").'</th>
         </tr>
         </thead>
         <tbody>';
   $TableRep=sql_query("SELECT * FROM ".$NPDS_Prefix."td_glossaire WHERE affiche!='0' ORDER BY gcat,nom");
   while (list($id_terme,$gcat,$lettre,$terme,$terme_def) = sql_fetch_row($TableRep)) {
      echo '
            <tr>
               <td>'.$gcat.'</td>
               <td>'.$terme.'</td>
               <td>'.$terme_def.'</td>
               <td>
                  <a class="mr-2" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_edit&amp;id='.$id_terme.'"><i class="fa fa-edit fa-lg" data-original-title="'.glo_translate("Editer").'" data-toggle="tooltip" data-placement="left"></i></a>
                  <a class="mr-2" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_meta&amp;id='.$id_terme.'"><span title="'.glo_translate("Meta").'" data-toggle="tooltip" data-placement="left">'.glo_translate("Meta").'</span></a>
                  <a class="" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=admin_supp&amp;id='.$id_terme.'&amp;typ=1"><i class="fas fa-trash fa-lg text-danger" title="'.glo_translate("Supprimer").'" data-toggle="tooltip" data-placement="left"></i></a>
               </td>
            </tr>';
   }
   echo '
      </tbody>
   </table>
   <p class="text-right my-3"><a class="btn btn-outline-primary btn-sm" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart=admin/glossadmin">'.glo_translate("Retour à l'administration").'</a></p>';
}

function admin_edit($id) {
   global $ModPath, $ModStart, $NPDS_Prefix;

   echo '<p class="lead">'.glo_translate("Edition d'une définition").'</p>';
   $TableRep=sql_query("SELECT * FROM ".$NPDS_Prefix."td_glossaire WHERE id='$id'");
   list($id_terme,$gcat,$lettre,$terme,$terme_def,$aff,$lien) = sql_fetch_row($TableRep);
   echo '
   <form action="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'" method="POST" name="adminForm">
      <div class="form-group row">
         <label class="col-form-label col-sm-3" for="terme">'.glo_translate("Terme").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" id="terme" name="terme" value="'.$terme.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-3" for="gcategory">'.glo_translate("Catégorie").'</label>
         <div class="col-sm-4">
            <input class="form-control" type="text" id="gcategory" name="gcategory" value="'.stripslashes($gcat).'" size="25" maxlength="30">
         </div>
         <div class="col-sm-4">
            <select class="form-control" name="sgcategory">';
   $result = sql_query("SELECT DISTINCT gcat FROM ".$NPDS_Prefix."td_glossaire ORDER BY gcat");
   while (list($dcategory) = sql_fetch_row($result)) {
      $dcategory=stripslashes($dcategory);
   echo '
               <option '.$sel.' value="'.$dcategory.'">'.$dcategory.'</option>';
   }
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-3" for="">'.glo_translate("Définition").'</label>
         <div class="col-sm-8">
            <textarea class="form-control tin" rows="10" name="content">'.$terme_def.'</textarea>';
//   echo aff_editeur("content", "true");
   echo '</div>
   </div>
   <div class="form-group row">
   <div class="col-sm-3"><label for="xurl">'.glo_translate("Site internet").'</div>
   <div class="col-sm-8">
      <input class="form-control" type="text" id="xurl" name="xurl" value="'.$lien.'" maxsize="255" />
      <span class="help-block text-muted text-right">exemple : http://npds.org</small>
   </div>
   </div>
   <input type="hidden" name="id" value="'.$id.'">
   <input type="hidden" name="subop" value="admin_modify">
   <input class="btn btn-outline-primary btn-sm" type="submit" value="'.glo_translate("Valider").'" />';
}
settype($subop,'string');
switch ($subop) {
 case "admin_supp":
   sql_query("DELETE FROM ".$NPDS_Prefix."td_glossaire WHERE id='$id'");
   if ($typ==1)
      redirect_url("admin.php?op=Extend-Admin-SubModule&amp;ModPath=$ModPath&ModStart=$ModStart&subop=admin_list");
   else
      redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart");
   die();
   break;

 case "admin_add":
   sql_query("UPDATE ".$NPDS_Prefix."td_glossaire SET affiche='".true."' WHERE id='$id'");
   redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart");
   die();
   break;

 case "admin_edit":
   admin_edit($id);
   break;

 case "admin_modify":
   $sgcategory=addslashes($sgcategory);
   if (!$gcategory) { $gcategory = $sgcategory; } else { $gcategory=addslashes($gcategory); }
   $lettre=substr(ucfirst($terme),0,1);
   if (!preg_match("#[A-Z]#",$lettre)) {$lettre="!AZ";}
   sql_query("UPDATE ".$NPDS_Prefix."td_glossaire SET gcat='$gcategory', lettre='$lettre', nom='$terme', definition='$content', lien='$xurl' WHERE id='$id'");
   redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart&subop=admin_list");
   die();
   break;

 case "admin_term":
   $sgcategory=addslashes($sgcategory);
   if (!$gcategory) $gcategory = $sgcategory; else $gcategory=addslashes($gcategory);
   if (($gcategory!="") and ($terme!="") and ($content!="")) {
      $lettre=substr(ucfirst($terme),0,1);
         if (!preg_match("#[A-Z]#",$lettre)) {$lettre="!AZ";}
      $result=sql_query("SELECT * FROM ".$NPDS_Prefix."td_glossaire WHERE gcat='$gcategory' AND nom='$terme' AND definition='$content'");
      list($id)=sql_fetch_row($result);
      if (!$id)
         sql_query("INSERT INTO ".$NPDS_Prefix."td_glossaire VALUES (NULL, '$gcategory', '$lettre', '".strip_tags($terme)."', '$content', '1', '$xurl')");
      else
         echo "<script type=\"text/javascript\">alert(\"".glo_translate("Une définition identique existe déjà !")."\")</script>";
   } else
      echo "<script type=\"text/javascript\">alert(\"".glo_translate("Merci de respecter les consignes")."\")</script>";
   redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart");
   die();
   break;

 case "admin_meta":
   $ibid=sql_query("SELECT nom, definition, lien FROM ".$NPDS_Prefix."td_glossaire WHERE id='$id'");
   list($terme,$terme_def,$lien) = sql_fetch_row($ibid);

   if ($lien) {
      $target="target=\"_blank\"";
      $href=$lien;
   } else {
      $target="";
      $href="#nogo";
   }
   $terme_def=str_replace("\r","",$terme_def);
   $terme_def=str_replace("\n","",$terme_def);
   $terme_def=str_replace("\t","",$terme_def);
   $terme_def=str_replace("'","\'",$terme_def);

   $Q = sql_query("SELECT def FROM ".$NPDS_Prefix."metalang WHERE def='".$terme."'");
   $Q = sql_fetch_assoc($Q);
   if ($Q[def])
      sql_query("UPDATE ".$NPDS_Prefix."metalang SET content='<a href=\"$href\" $target data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\" title=\"$terme_def\">$terme</a>' where def='$terme'");
   else
      sql_query("INSERT INTO ".$NPDS_Prefix."metalang SET def='".$terme."', content='<a href=\"$href\" $target data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\" title=\"$terme_def\">$terme</a>', type_meta='mot', type_uri='-', uri='', description='npds_glossaire import', obligatoire='0'");
   redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart&subop=admin_list");
   die();
   break;

 case "admin_settings":
   $file = fopen("modules/$ModPath/glossaire.conf.php", "w");
   $content = "<?php\n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* =====================================================================*/\n";
   $content .= "/* From Glossaire version 1.3 pour myPHPNuke 1.8                        */\n";
   $content .= "/* Copyright © 2001, Pascal Le Boustouller                              */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This version name NPDS Copyright (c) 2001-".date(Y)." by Philippe Brunier   */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* module npds_glossaire v 3.0 pour revolution 16                       */\n";
   $content .= "/* by team jpb/phr 2017                                                 */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/************************************************************************/\n";
   $content .= "\n";
   $content .= "// Nb d'affichage par page\n";
   $content .= "\$nb_affichage = '$nbaff_new';\n\n";
   $content .= "// Autorise la soumission\n";
   $content .= "\$ok_submit = $oksubmit_new;\n\n";
   $content .= "// Autorise la recherche\n";
   $content .= "\$activ_rech = $activrech_new;\n\n";
   $content .= "?>";
   fwrite($file, $content);
   fclose($file);
   redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart");
   die();
   break;

 case "admin_list":
   admin_list();
   break;

 default:
   admin_glo();
   break;
}
   include ("footer.php");
?>