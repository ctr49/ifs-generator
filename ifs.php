<?php
require_once '/usr/share/php/Michelf/Markdown.inc.php';
date_default_timezone_set('Europe/Berlin');

$content_template='IFS-Template.md';
$layout_template='IFS-Layout.html';
$RECHTSGRUNDLAGE_HGO = "des § 5 Abs. 1 der Hessischen Gemeindeordnung (HGO) in der Fassung der Bekanntmachung vom 7. März 2005 (GVBl. I S. 142), zuletzt geändert durch Artikel 1 des Gesetzes vom 20. Dezember 2015 (GVBl. S. 618)";
$RECHTSGRUNDLAGE_HKO = "des § 5 Abs. 1 der Hessischen Landkreisordnung (HKO) in der Fassung der Bekanntmachung vom 7. März 2005 (GVBl. I S. 183), zuletzt geändert durch Artikel 2 des Gesetzes vom 20. Dezember 2015 (GVBl. S. 618)";

$org_pattern = "/^GEMEINDE$|^STADT$|^STADT_GR$|^STADT_KF$|^LK$/";
$isb_pattern = "/^IFB$|^DSB$/";
$kosten_pattern = "/^NUR_PAPIER$|^SATZUNG_EIGEN$|^SATZUNG_HVwKostG$|^PAUSCHAL$/";

if (isset($_POST['ORG']))                       $ORG = filter_var($_POST['ORG'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$org_pattern)));
if (isset($_POST['NAME']))                      $NAME = filter_var($_POST['NAME'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($_POST['IFB']))                       $IFB_VALUE = filter_var($_POST['IFB'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>$isb_pattern)));
if (isset($_POST['EINFACHE_FRIST']))            $EINFACHE_FRIST = filter_var($_POST['EINFACHE_FRIST'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($_POST['FRIST']))                     $FRIST = filter_var($_POST['FRIST'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($_POST['KOSTEN']))                    $KOSTEN_VALUE = filter_var($_POST['KOSTEN'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$kosten_pattern)));
if (isset($_POST['PAUSCHALE']))                 $PAUSCHALE = filter_var($_POST['PAUSCHALE'], FILTER_VALIDATE_INT);
if (isset($_POST['INKRAFTTRETEN']))             $INKRAFTTRETEN = date_format(date_create(filter_var($_POST['INKRAFTTRETEN'])), 'd.m.Y');
if (isset($_POST['BEFRISTUNG_BIS']))            $BEFRISTUNG_BIS = filter_var($_POST['BEFRISTUNG_BIS'], FILTER_VALIDATE_BOOLEAN, array("flags"=>FILTER_NULL_ON_FAILURE));
if (isset($_POST['BEFRISTUNG_AB']))             $BEFRISTUNG_AB = filter_var($_POST['BEFRISTUNG_AB'], FILTER_VALIDATE_BOOLEAN, array("flags"=>FILTER_NULL_ON_FAILURE));
if (isset($_POST['BEFRISTUNG_BIS_DATE']))       $BEFRISTUNG_BIS_DATE = filter_var($_POST['BEFRISTUNG_BIS_DATE']);
if (isset($_POST['BEFRISTUNG_AB_DATE']))        $BEFRISTUNG_AB_DATE = date_format(date_create(filter_var($_POST['BEFRISTUNG_AB_DATE'])), 'd.m.Y');
if (isset($_POST['SITZUNG_DATE']))              $SITZUNG_DATE = date_format(date_create(filter_var($_POST['SITZUNG_DATE'])), 'd.m.Y');
if (isset($_POST['TEXT_ONLY']))                 $TEXT_ONLY = filter_var($_POST['TEXT_ONLY'], FILTER_VALIDATE_BOOLEAN, array("flags"=>FILTER_NULL_ON_FAILURE));


switch ($ORG) {
    case "GEMEINDE":
        $GV_STVOV = "Gemeindevertretung";
        $GV_STVOV_ART1 = "die";
        $OBERHAUPT = "den Bürgermeister";
        $ORGNAME = "Gemeinde $NAME";
        $ORGNAME_ART1 = "der";
        $ORGNAME_ART2 = "ie";
        $ORGNAME_ART3 = "in der";
        $RECHTSGRUNDLAGE = $RECHTSGRUNDLAGE_HGO;
        break;
    case "STADT":
        $GV_STVOV = "Stadtverordnetenversammlung";
        $GV_STVOV_ART1 = "die";
        $OBERHAUPT = " den Bürgermeister";
        $ORGNAME = "Stadt $NAME";
        $ORGNAME_ART1 = "der";
        $ORGNAME_ART2 = "ie";
        $ORGNAME_ART3 = "in der";
        $RECHTSGRUNDLAGE = $RECHTSGRUNDLAGE_HGO;
        break;
    case "STADT_GR":
        $GV_STVOV = "Stadtverordnetenversammlung";
        $GV_STVOV_ART1 = "die";
        $OBERHAUPT = "den Oberbürgermeister";
        $ORGNAME = "Stadt $NAME";
        $ORGNAME_ART1 = "der";
        $ORGNAME_ART2 = "ie";
        $ORGNAME_ART3 = "in der";
        $RECHTSGRUNDLAGE = $RECHTSGRUNDLAGE_HGO;
        break;
    case "STADT_KF":
        $GV_STVOV = "Stadtverordnetenversammlung";
        $GV_STVOV_ART1 = "die";
        $OBERHAUPT = "den Oberbürgermeister";
        $ORGNAME = "Stadt $NAME";
        $ORGNAME_ART1 = "der";
        $ORGNAME_ART2 = "ie";
        $ORGNAME_ART3 = "in der";
        $RECHTSGRUNDLAGE = $RECHTSGRUNDLAGE_HGO;
        break;
    case "LK":
        $GV_STVOV = "Kreistag";
        $GV_STVOV_ART1 = "der";
        $OBERHAUPT = "den Landrat";
        $ORGNAME = "Landkreis $NAME";
        $ORGNAME_ART1 = "des";
        $ORGNAME_ART2 = "er";
        $ORGNAME_ART3 = "im";
        $RECHTSGRUNDLAGE = $RECHTSGRUNDLAGE_HKO;
        break;
}

switch ($IFB_VALUE) {
    case "IFB":
        $IFB = "Die $ORGNAME ernennt eine Informationsfreiheitsbeauftragte oder einen Informationsfreiheitsbeauftragten.";
        break;
    case "DSB":
        $IFB = "Die Datenschutzbeauftragte oder der Datenschutzbeauftragte der $ORGNAME übernimmt außerdem die Funktion des Informationsfreiheitsbeauftragten.";
        break;
}

switch ($KOSTEN_VALUE) {
    case "NUR_PAPIER":
        $KOSTEN = "Mündlich, telefonisch und in elektronischer Form erteilte Auskünfte sowie die Einsicht in Akten sind kostenfrei. Für schriftliche Auskünfte in Papierform werden Kopier- und ggf. Versandkosten berechnet. Über die Höhe der Gebühren ist die Antragstellerin oder der Antragsteller vorab zu informieren.";
        break;
    case "SATZUNG_EIGEN":
        $KOSTEN = "Für Amtshandlungen aufgrund dieser Satzung werden Kosten (Gebühren und Auslagen) nach der in der $ORGNAME gültigen Kostensatzung erhoben.";
        break;
    case "SATZUNG_HVwKostG":
        $KOSTEN = "Für Amtshandlungen aufgrund dieser Satzung werden Kosten (Gebühren und Auslagen) nach dem Hessischen Verwaltungskostengesetz (HVwKostG) erhoben.";
        break;
    case "PAUSCHAL":
        $KOSTEN = "Für Amtshandlungen aufgrund dieser Satzung werden pauschal Kosten in Höhe von € $PAUSCHALE pro ... erhoben";
        break;
}

if (($BEFRISTUNG_BIS)  && !($BEFRISTUNG_AB)) {
        $BEFRISTUNGSKLAUSEL = "Die Gültigkeit der Satzung ist zunächst befristet bis $BEFRISTUNG_BIS_DATE nach Inkrafttreten.";
   } elseif (!($BEFRISTUNG_BIS) && !($BEFRISTUNG_AB)) {
        $BEFRISTUNGSKLAUSEL = "Die Satzung ist unbefristet gültig.";
   } elseif (!($BEFRISTUNG_BIS) && ($BEFRISTUNG_AB)) {
        $BEFRISTUNGSKLAUSEL = "Die Satzung ist unbefristet gültig, jedoch nur für Informationen die nach dem $BEFRISTUNG_AB_DATE angefallen sind.";
   } elseif (($BEFRISTUNG_BIS) && ($BEFRISTUNG_AB)) {
        $BEFRISTUNGSKLAUSEL = "Die Gültigkeit der Satzung ist zunächst befristet bis $BEFRISTUNG_BIS_DATE nach Inkrafttreten und nur gültig für Informationen die nach dem $BEFRISTUNG_AB_DATE angefallen sind.";
   }


if(file_exists($content_template)==false){
        /**
         * Datei gibt es nicht
         */
        print "Vorlage nicht gefunden";
        return false;
}

if(is_readable($content_template)==false){
        print "Vorlage nicht lesbar (1)";
        return false;
} else {
        $handle = fopen ($content_template, "r");
        if($handle===false){
                /**
                 * Kann die Datei nicht öffnen
                 */
                print "Vorlage nicht lesbar (2)";
                return false;
        }
        $text = fread($handle, filesize ($content_template));
        fclose ($handle);
}

if(file_exists($layout_template)==false){
        /**
         * Datei gibt es nicht
         */
        print "Vorlage nicht gefunden";
        return false;
}

if(is_readable($layout_template)==false){
        print "Vorlage nicht lesbar (1)";
        return false;
} else {
        $handle2 = fopen ($layout_template, "r");
        if($handle2===false){
                /**
                 * Kann die Datei nicht öffnen
                 */
                print "Vorlage nicht lesbar (2)";
                return false;
        }
        $layout = fread($handle2, filesize ($layout_template));
        fclose ($handle2);
}



/**
 * Text ersetzen
        */
$text=str_replace("°STADT°", $ORGNAME, $text);
$text=str_replace("°IFB°", $IFB, $text);
$text=str_replace("°FRIST°", $FRIST, $text);
$text=str_replace("°EINFACHE_FRIST°", $EINFACHE_FRIST, $text);
$text=str_replace("°INKRAFTTRETEN°", $INKRAFTTRETEN, $text);
$text=str_replace("°BEFRISTUNG_BIS°", $BEFRISTUNG_BIS, $text);
$text=str_replace("°BEFRISTUNG_AB°", $BEFRISTUNG_AB, $text);
$text=str_replace("°RECHTSGRUNDLAGE°", $RECHTSGRUNDLAGE, $text);
$text=str_replace("°OBERHAUPT°", $OBERHAUPT, $text);
$text=str_replace("°GV_STVOV°", $GV_STVOV, $text);
$text=str_replace("°KOSTEN°", $KOSTEN, $text);
$text=str_replace("°BEFRISTUNGSKLAUSEL°", $BEFRISTUNGSKLAUSEL, $text);
$text=str_replace("°ARTIKEL1_STADT°", $ORGNAME_ART1, $text);
$text=str_replace("°ARTIKEL2_STADT°", $ORGNAME_ART2, $text);
$text=str_replace("°ARTIKEL3_STADT°", $ORGNAME_ART3, $text);
$text=str_replace("°ARTIKEL1_ORGAN°", $GV_STVOV_ART1, $text);
$text=str_replace("°SITZUNG_DATE°", $SITZUNG_DATE, $text);
$my_html = Michelf\Markdown::defaultTransform($text);
$output=str_replace("°CONTENT°", $my_html, $layout);
print $output;
?>
