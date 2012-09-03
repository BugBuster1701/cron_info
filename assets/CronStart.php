<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   CronInfo 
 * @author    Glen Langer 
 * @license   LGPL 
 * @copyright Glen Langer 2012 
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require_once '../../../initialize.php';


/**
 * Class CronStart 
 *
 * @copyright  Glen Langer 2012 
 * @author     Glen Langer 
 * @package    CronInfo
 */
class CronStart extends Frontend
{

	/**
	 * Initialize the object (do not remove)
	 */
	public function __construct()
	{
		parent::__construct();

		// See #4099
		define('BE_USER_LOGGED_IN', false);
		define('FE_USER_LOGGED_IN', false);
	}


	/**
	 * Run the controller and parse the template
	 */
	public function run()
	{
		$strEncypt = Input::get('crcst');
		$arrDecypt = deserialize( Encryption::decrypt( base64_decode($strEncypt) ) );
		$class = $arrDecypt[0];
		$method = $arrDecypt[1];
		
		$this->loadLanguageFile('tl_cron_info');

		$this->Template = new BackendTemplate('mod_cron_start_be');
		$this->Template->referer = $this->getReferer(ENCODE_AMPERSANDS); //$this->Environment->get(httpReferer);
		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['helpWizardTitle']);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$this->Template->cronjob = $class.'.'.$method.'()';
		
		$CronStartTime = time();
		$this->Template->start_time = $CronStartTime;
		$this->log('Running cron job manually', 'CronStart run()', TL_CRON);
		//Cronjob starten
		$this->import($class);
		$this->$class->$method();
		$this->log('Manually cron job complete', 'CronStart run()', TL_CRON);
		
		$objLog = $this->Database->prepare("SELECT `id` FROM `tl_log` WHERE `tstamp` >=? AND `func`=? ORDER BY `id`")
								   ->limit(2)
								   ->execute($CronStartTime, 'CronStart run()');
		$startID = $objLog->id;
		$objLog->next();
		$endID = $objLog->id;

		$objLog = $this->Database->prepare("SELECT `tstamp`, `text` FROM `tl_log` WHERE `id`>=? AND `id`<=? ORDER BY `id`")
								 ->execute($startID,$endID);
		$this->Template->cronlog = '<ul>';
		while ($objLog->next()) 
		{
			$this->Template->cronlog .= '<li style="list-style: square inside none;">'. date('Y-m-d H:i:s', $objLog->tstamp) .' : '. $objLog->text .'</li>';
		}
		$this->Template->cronlog .= '</ul>';
		
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objCronStart = new CronStart();
$objCronStart->run();
