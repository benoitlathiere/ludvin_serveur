<?php
//BL novembre 2013	//FIXME
if (strpos($_SERVER['SERVER_NAME'],"handiman")==!false) {
	header("Location: http://www.ludvin.org");
	die();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<?php
        switch ($_GET['page']) {
            case 'accueil':
                echo "<title>Projet LUD'VIN - accueil</title>";
                break;
            case 'presentation':
                echo "<title>Projet LUD'VIN - pr&eacute;sentation du projet</title>";
                break;
            case 'equipe':
                echo "<title>Projet LUD'VIN - pr&eacute;sentation de l'&eacute;quipe</title>";
                break;
            case 'downloads':
                echo "<title>Projet LUD'VIN - t&eacute;l&eacute;chargements</title>";
                break;
			case 'liens':
				echo "<title>Projet LUD'VIN - liens</title>";
				break;
			case 'plan':
				echo "<title>Projet LUD'VIN - plan du site</title>";
				break;
			default:
				echo "<title>Projet LUD'VIN - accueil</title>";
		}
	?>
        
        <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
        <link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 1024px)" href="mobile.css" />
        <meta name="viewport" content="width=device-width, initial-scale=0.7"/>  
        <link rel="icon" href="favicon.ico" />
		<script type="text/javascript">
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		  ga('create', 'UA-41673004-1', 'ludvin.org');
		  ga('send', 'pageview');
		</script>
    </head>
	
    <body>
        <div id="banner">
	  <a href="#contenu">Acc&eacute;der au contenu</a>
	</div>
        <div id="header">
	    <img style="height:80px;width:auto" src="images/logop8.png" alt="Logo de l'universit&eacute; Paris 8" />
	    <img style="height:80px;width:auto" src="images/logohandi.png" alt="Logo du master HANDI" />
            <h1>Projet LUD'VIN</h1>
            <h2>Localisation d'un Usager D&eacute;ficient Visuel en INt&eacute;rieur</h2>
        </div>
        <div id="content">
            <div id="menu">
                <h3   id="leftnav_label">Menu de navigation</h3>
                <ul >
				  <li><a class="<?=($_GET['page']=="accueil"?"actif":"inactif")?>" href="index.php?page=accueil" accesskey="1" title="Accueil (raccourci clavier 1)">Accueil</a></li>
				  <li><a class="<?=($_GET['page']=="presentation"?"actif":"inactif")?>" href="index.php?page=presentation"  accesskey="2" title="Le projet (raccourci clavier 2)">Le projet</a></li>
				  <li><a class="<?=($_GET['page']=="equipe"?"actif":"inactif")?>" href="index.php?page=equipe"  accesskey="3" title="L'&eacute;quipe (raccourci clavier 3)">L'&eacute;quipe</a></li>
				  <li><a class="<?=($_GET['page']=="downloads"?"actif":"inactif")?>" href="index.php?page=downloads"  accesskey="4" title="T&eacute;l&eacute;chargements (raccourci clavier 4)">T&eacute;l&eacute;chargements</a></li>  
                  <li><a class="<?=($_GET['page']=="liens"?"actif":"inactif")?>" href="index.php?page=liens" accesskey="5"  title="Liens (raccourci clavier 5)">Liens</a></li>
                  <li><a class="<?=($_GET['page']=="plan"?"actif":"inactif")?>" href="index.php?page=plan"  accesskey="0"  title="Plan du site (raccourci clavier 0)">Plan du site</a></li>
                </ul>
            </div>
            <div id="text">
                <?php include 'content.php'; ?>
            </div>
	    <div class="cl"></div>
        </div>
        <div id="footer">
            <p>Projet <abbr title="Localisation d'un Usager D&eacute;ficient Visuel en INt&eacute;rieur">LUD'VIN</abbr> - www.ludvin.org - <a href="mailto:project.ludvinATgmailDOTorg">Contact</a><br />
            Site gracieusement h&eacute;berg&eacute; par le <a href="http://www.master-handi.fr" title="Site du Master Technologie et Handicap">Master 2 Technologie et Handicap 2012-2013</a><br />
            <a href="http://www.univ-paris8.fr/" title="Site de l'Universit&eacute; Paris 8">Universit&eacute; Paris 8</a><br /></p>
	    <p>
	      <a class="aimg" href="http://validator.w3.org/check?uri=referer">
		  <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" height="31" width="88" />
	      </a>&nbsp;
	      <a class="aimg" href="http://jigsaw.w3.org/css-validator/check/referer/style.css">
		<img style="border:0;width:88px;height:31px"
		     src="http://jigsaw.w3.org/css-validator/images/vcss"
		     alt="CSS Valide !" />
	      </a>&nbsp;
		  <a class="aimg" >
		<img style="border:0;width:88px;height:31px"
		     src="http://www.w3.org/WAI/wcag2AA"
		     alt="WCAG 2.0 WAI-AA Valide !" />
	      </a>&nbsp;
	    </p>
        </div>
    </body>
</html>
