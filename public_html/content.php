<?php
    switch ($_GET['page']) {
        case 'accueil':
            include 'accueil.html';
            break;
        case 'presentation':
            include 'presentation.html';
            break;
        case 'equipe':
            include 'equipe.html';
            break;
		case 'downloads':
			include 'dl.html';
			break;
		case 'liens':
			include 'liens.html';
			break;
		case 'plan':
			include 'plandusite.html';
			break;
		default:
			include 'accueil.html';
    }
?>