<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2026 by Philippe Brunier                     */
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

if (stristr($_SERVER['PHP_SELF'],'glossaire_bloc.php')) die();
global $language;
$ModPath = 'npds_glossaire';
$ModStart = 'index';
include_once 'modules/'.$ModPath.'/lang/glossaire-'.$language.'.php';

$content = '';
$content .= '
                              <div class="d-flex w-100 justify-content-center"><a href="modules.php?ModPath=npds_glossaire&amp;ModStart=index"><img src="modules/npds_glossaire/npds_glossaire.png" alt="icon_npds_glossaire" style="max-width:140px; max-height:140px;"></a></div>';
$content .= '
                              <p class="lead text-center">';

$alphabet = array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',glo_translate("Autres"));
$num = count($alphabet) - 1;
$counter = 0;
$listletter = '';

foreach($alphabet as $ltr) {
   $listletter .=  ($ltr!=glo_translate("Autres")) ?
      '<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;op=rech_lettre&amp;lettre='.$ltr.'&amp;gcat=">'.$ltr.'</a>' :
      '<a href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;op=rech_lettre&amp;lettre=!AZ&amp;gcat=">'.$ltr.'</a>' ;
   if ( $counter != $num )
      $listletter .= ' | ';
   $counter++;
}
$content .= $listletter.'</p>';
if ($admin) 
   $content .='
                              <div class="mt-2 text-end">
                                 <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart=admin/glossadmin" data-bs-toggle="tooltip" data-bs-placement="left" title="[french]Administration[/french][english]Admin[/english]"><i class="fa fa-cogs fa-lg" aria-hidden="true"></i></a>
                              </div>
                           ';
$content = aff_langue($content);

?>