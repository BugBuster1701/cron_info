<?php 

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package    CronInfo 
 * @copyright  Glen Langer (BugBuster) 2012..2013
 * @author     BugBuster
 * @license    LGPL
 */


/**
 * Namespace
 */
namespace BugBuster\CronInfo;

/**
 * Class CronTimestamp 
 *
 * @copyright  Glen Langer (BugBuster) 2012..2013
 * @author     BugBuster 
 * @package    CronInfo
 */
class CronRegistry extends \BackendModule
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_cron_registry_be';
	

    /**
     * Generate the module
     */
    protected function compile()
    {
    	$this->loadLanguageFile('tl_cron_info');
    	
    	$this->Template->referer     = $this->getReferer(ENCODE_AMPERSANDS);
    	$this->Template->backTitle   = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
    	$this->Template->TitleTime   = $GLOBALS['TL_LANG']['CronInfo']['title_time'];
    	$this->Template->TitleReg    = $GLOBALS['TL_LANG']['CronInfo']['title_reg'];
    	
    	$this->Template->reg ='';
    	$this->Template->timeinfo ='';
    
    	$arrTimestampsCron =  $this->getCronExecutionTimeCron();

    	if (version_compare(VERSION, '3.3', '<'))
    	{
    	    $arrTimestampsLog  =  $this->getCronExecutionTimeLog();
        	$this->Template->timeinfo .='
        	<div id="tl_maintenance_cache">
          	<table>
          	<thead>
    	        <tr>
              		<th class="nw">'.$GLOBALS['TL_LANG']['CronInfo']['cron_time_interval'].'</th>
              		<th>&nbsp;</th>
              		<th>'.$GLOBALS['TL_LANG']['CronInfo']['cron_last_start'].' (tl_cron)</th>
              		<th>&nbsp;</th>
              		<th>'.$GLOBALS['TL_LANG']['CronInfo']['cron_last_start'].' (tl_log)</th>
            	</tr>
          	</thead>
          	<tbody>
    	        <tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_monthly'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['monthly'] .'</td>
              		<td> </td>
              		<td>'. $arrTimestampsLog['monthly'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_weekly'].'</strong></td>
              		<td> </td>
              		<td>';
        	if ($arrTimestampsCron['weekly_nr'] >0) 
        	{
        		$this->Template->timeinfo .= $GLOBALS['TL_LANG']['CronInfo']['calendar_week'].' '. $arrTimestampsCron['weekly_nr'] . '<br>(' . $arrTimestampsCron['weekly_from'] . ' - '.$arrTimestampsCron['weekly_to'].')';
        	}
        	else
        	{
        		$this->Template->timeinfo .= '--';
        	}
    		$this->Template->timeinfo .='</td>
    				<td> </td>
              		<td>'. $arrTimestampsLog['weekly'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_daily'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['daily'] .'</td>
              		<td> </td>
              		<td>'. $arrTimestampsLog['daily'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_hourly'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['hourly'] .'</td>
              		<td> </td>
              		<td>'. $arrTimestampsLog['hourly'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_minutely'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['minutely'] .'</td>
              		<td> </td>
              		<td>'. $arrTimestampsLog['minutely'] .'</td>
            	</tr>
          	</tbody>
          	</table>
        	</div>
';
    	}
    	else // ab Contao 3.3.0
    	{
    	    $this->Template->timeinfo .='
        	<div id="tl_maintenance_cache">
          	<table>
          	<thead>
    	        <tr>
              		<th class="nw">'.$GLOBALS['TL_LANG']['CronInfo']['cron_time_interval'].'</th>
              		<th>&nbsp;</th>
              		<th>'.$GLOBALS['TL_LANG']['CronInfo']['cron_last_start'].' (tl_cron)</th>
            	</tr>
          	</thead>
          	<tbody>
    	        <tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_monthly'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['monthly'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_weekly'].'</strong></td>
              		<td> </td>
              		<td>';
    	    if ($arrTimestampsCron['weekly_nr'] >0)
    	    {
    	        $this->Template->timeinfo .= $GLOBALS['TL_LANG']['CronInfo']['calendar_week'].' '. $arrTimestampsCron['weekly_nr'] . '<br>(' . $arrTimestampsCron['weekly_from'] . ' - '.$arrTimestampsCron['weekly_to'].')';
    	    }
    	    else
    	    {
    	        $this->Template->timeinfo .= '--';
    	    }
    	    $this->Template->timeinfo .='</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_daily'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['daily'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_hourly'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['hourly'] .'</td>
            	</tr>
            	<tr>
              		<td><strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_minutely'].'</strong></td>
              		<td> </td>
              		<td>'. $arrTimestampsCron['minutely'] .'</td>
            	</tr>
          	</tbody>
          	</table>
        	</div>
';    	    
    	}
		$this->Template->reg = $this->getCronRegistrations();
  	}
  
    protected function getCronExecutionTimeCron()
    {
        $arrTimestamps     = array('monthly'    =>'--', 
                                   'weekly_from'=>0, 
                                   'weekly_to'  =>0, 
                                   'weekly_nr'  =>0, 
                                   'daily'      =>'--', 
                                   'hourly'     =>'--', 
                                   'minutely'   =>'--'
                                );
        $arrTimestampsTemp = array();
      
        $objTimestamps = \Database::getInstance()
                            ->prepare("SELECT 
                                            * 
                                        FROM 
                                            `tl_cron` 
                                        WHERE 
                                            `name` != ? 
                                        AND 
                                            `value` > 0
                                    ")
                            ->execute('lastrun'); 
            
        while($objTimestamps->next())
        {
            switch ($objTimestamps->name)
            {
            	case 'monthly'  :
              		$arrTimestampsTemp['monthly'] = date('F Y',strtotime($objTimestamps->value .'01 0000'));
              		break;
            	case 'weekly'   :
            		$arrTimestampsTemp['weekly_from'] = date($GLOBALS['TL_CONFIG']['dateFormat'], $this->getWeekOffsetTimestamp(substr($objTimestamps->value,0,4), substr($objTimestamps->value,4,2)) );
            		$arrTimestampsTemp['weekly_to']   = date($GLOBALS['TL_CONFIG']['dateFormat'], $this->getWeekOffsetTimestamp(substr($objTimestamps->value,0,4), substr($objTimestamps->value,4,2)) + (6 * 24 * 3600) );
            		$arrTimestampsTemp['weekly_nr']   = substr($objTimestamps->value,4,2);
                	break;
            	case 'daily'    :
              		$arrTimestampsTemp['daily'] = date($GLOBALS['TL_CONFIG']['dateFormat'],strtotime($objTimestamps->value .' 0000'));
                	break;
            	case 'hourly'   :
              		$arrTimestampsTemp['hourly'] = date($GLOBALS['TL_CONFIG']['datimFormat'],strtotime($objTimestamps->value .'00'));
                	break;
            	case 'minutely' :
              		$arrTimestampsTemp['minutely'] = date($GLOBALS['TL_CONFIG']['datimFormat'],strtotime($objTimestamps->value));
                	break;
            }
        }
        $arrTimestamps = array_merge($arrTimestamps,$arrTimestampsTemp);
        return $arrTimestamps;
	}
	
	protected function getCronExecutionTimeLog()
	{
	    $arrTimestamps     = array('monthly'  =>'--', 
	                               'weekly'   =>'--', 
	                               'daily'    =>'--', 
	                               'hourly'   =>'--', 
	                               'minutely' =>'--'
	                            );
	    $arrTimestampsTemp = array();
		
		//last call monthly cron job
		$objTimestamps = \Database::getInstance()
                            ->prepare("SELECT 
                                            `tstamp` 
                                        FROM 
                                            `tl_log`
								        WHERE 
                                            `action` = 'CRON'
								        AND 
                                            `func` = 'CronJobs run()'
								        AND 
                                            `text` = ?
								        ORDER BY `tstamp` DESC
							        ")
					        ->limit(1)
					        ->execute('Running the monthly cron jobs');
		if ($objTimestamps->numRows == 1)
		{
		    $arrTimestampsTemp['monthly'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objTimestamps->tstamp);
		}
		//last call weekly cron job
		$objTimestamps = \Database::getInstance()
                            ->prepare("SELECT 
                                            `tstamp` 
                                        FROM 
                                            `tl_log`
								        WHERE 
                                            `action` = 'CRON'
								        AND 
                                            `func` = 'CronJobs run()'
								        AND 
                                            `text` = ?
								        ORDER BY `tstamp` DESC
							        ")
					        ->limit(1)
					        ->execute('Running the weekly cron jobs');
		if ($objTimestamps->numRows == 1)
		{
		    $arrTimestampsTemp['weekly'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objTimestamps->tstamp);
		}
		//last call daily cron job
		$objTimestamps = \Database::getInstance()
                            ->prepare("SELECT 
                                            `tstamp` 
                                        FROM 
                                            `tl_log`
								        WHERE 
                                            `action` = 'CRON'
								        AND 
                                            `func` = 'CronJobs run()'
								        AND 
                                            `text` = ?
								        ORDER BY `tstamp` DESC
							        ")
					        ->limit(1)
					        ->execute('Running the daily cron jobs');
		if ($objTimestamps->numRows == 1)
		{
		    $arrTimestampsTemp['daily'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objTimestamps->tstamp);
		}
		//last call hourly cron job
		$objTimestamps = \Database::getInstance()
                            ->prepare("SELECT 
                                            `tstamp` 
                                        FROM 
                                            `tl_log`
								        WHERE 
                                            `action` = 'CRON'
								        AND 
                                            `func` = 'CronJobs run()'
								        AND 
                                            `text` = ?
								        ORDER BY `tstamp` DESC
							        ")
					        ->limit(1)
					        ->execute('Running the hourly cron jobs');
		if ($objTimestamps->numRows == 1)
		{
		    $arrTimestampsTemp['hourly'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objTimestamps->tstamp);
		}
		//last call minutely cron job
		$objTimestamps = \Database::getInstance()
                            ->prepare("SELECT 
                                            `tstamp` 
                                        FROM 
                                            `tl_log`
								        WHERE 
                                            `action` = 'CRON'
								        AND 
                                            `func` = 'CronJobs run()'
								        AND 
                                            `text` = ?
								        ORDER BY `tstamp` DESC
							        ")
					        ->limit(1)
					        ->execute('Running the minutely cron jobs');
		if ($objTimestamps->numRows == 1)
		{
		    $arrTimestampsTemp['minutely'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objTimestamps->tstamp);
		}
		
		$arrTimestamps = array_merge($arrTimestamps,$arrTimestampsTemp);
		return $arrTimestamps;
	}
    
    /**
     * getWeekOffsetTimestamp - calculate the Unix timestamp of the start of a week
     * 
     * @param int $year
     * @param int $week
     * @param boolean $useGmt
     * @return  int Unix timestamp
     * @author gerben at gerbenwijnja dot nl from php.net
     */
    private function getWeekOffsetTimestamp($year, $week, $useGmt = false) 
    {
        if ($useGmt) 
        {
            // Backup timezone and set to GMT
            $timezoneSettingBackup = date_default_timezone_get();
            date_default_timezone_set("GMT");
        }
    
        // According to ISO-8601, January 4th is always in week 1
        $halfwayTheWeek = strtotime($year."0104 +".($week - 1)." weeks");
    
        // Subtract days to Monday
        $dayOfTheWeek = date("N", $halfwayTheWeek);
        $daysToSubtract = $dayOfTheWeek - 1;
    
        // Calculate the week's timestamp
        $unixTimestamp = strtotime("-$daysToSubtract day", $halfwayTheWeek);
    
        if ($useGmt) 
        {
            // Reset timezone to backup
            date_default_timezone_set($timezoneSettingBackup);
        }
    
        return $unixTimestamp;
    }
    
    protected function getCronRegistrations()
    {
    	$strRegistrations = '<div id="tl_maintenance_cache">
		<table>
		<thead>
		<tr>
		  <th class="nw">'.$GLOBALS['TL_LANG']['CronInfo']['cron_time_interval'].'</th>
		  <th>&nbsp;</th>
		  <th>'.$GLOBALS['TL_LANG']['CronInfo']['class'].'</th>
		  <th>&nbsp;</th>
		  <th>'.$GLOBALS['TL_LANG']['CronInfo']['method'].'</th>
		  <th>&nbsp;</th>
		  <th>'.$GLOBALS['TL_LANG']['CronInfo']['start'][0].'</th>
		</tr>
		</thead>
		<tbody>';
    	$row=0;
    	foreach ($GLOBALS['TL_CRON']['monthly'] as $reg )
    	{
    		$strEncypt = base64_encode( \Encryption::encrypt( serialize( array( $reg[0],$reg[1] ) ) ) );
    		$interval = (!$row) ? '<strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_monthly'].'</strong>' : '' ;
    		$strRegistrations .= '
    		<tr>
    			<td>'.$interval.'</td>
    			<td> </td>
    			<td>'.$reg[0].'</td>
    			<td> </td>
    			<td>'.$reg[1].'</td>
    			<td> </td>
    			<td style="text-align: center;"><a href="system/modules/cron_info/assets/CronStart.php?crcst='.$strEncypt.'" title="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['CronInfo']['start_confirm'].'\'))return false;Backend.openModalIframe({\'width\':735,\'height\':405,\'title\':\'Cronjob Start\',\'url\':this.href});return false"><img src="system/modules/cron_info/assets/cron_info_start_icon.png" width="16" height="16" alt="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" style="vertical-align:text-bottom"></a></td>
    		</tr>
    		';
    		$row++;
    	}
    	$row=0;
    	foreach ($GLOBALS['TL_CRON']['weekly'] as $reg )
    	{
    		$strEncypt = base64_encode( \Encryption::encrypt( serialize( array( $reg[0],$reg[1] ) ) ) );
    		$interval = (!$row) ? '<strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_weekly'].'</strong>' : '' ;
    		$strRegistrations .= '
    		<tr>
    			<td>'.$interval.'</td>
    			<td> </td>
    			<td>'.$reg[0].'</td>
    			<td> </td>
    			<td>'.$reg[1].'</td>
    			<td> </td>
    			<td style="text-align: center;"><a href="system/modules/cron_info/assets/CronStart.php?crcst='.$strEncypt.'" title="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['CronInfo']['start_confirm'].'\'))return false;Backend.openModalIframe({\'width\':735,\'height\':405,\'title\':\'Cronjob Start\',\'url\':this.href});return false"><img src="system/modules/cron_info/assets/cron_info_start_icon.png" width="16" height="16" alt="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" style="vertical-align:text-bottom"></a></td>
    		</tr>
    		';
    		$row++;
    	}
    	$row=0;
    	foreach ($GLOBALS['TL_CRON']['daily'] as $reg )
    	{
    		$strEncypt = base64_encode( \Encryption::encrypt( serialize( array( $reg[0],$reg[1] ) ) ) );
    		$interval = (!$row) ? '<strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_daily'].'</strong>' : '' ;
    		$strRegistrations .= '
    		<tr>
    			<td>'.$interval.'</td>
    			<td> </td>
    			<td>'.$reg[0].'</td>
    			<td> </td>
    			<td>'.$reg[1].'</td>
    			<td> </td>
    			<td style="text-align: center;"><a href="system/modules/cron_info/assets/CronStart.php?crcst='.$strEncypt.'" title="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['CronInfo']['start_confirm'].'\'))return false;Backend.openModalIframe({\'width\':735,\'height\':405,\'title\':\'Cronjob Start\',\'url\':this.href});return false"><img src="system/modules/cron_info/assets/cron_info_start_icon.png" width="16" height="16" alt="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" style="vertical-align:text-bottom"></a></td>
    		</tr>
    		';
    		$row++;
    	}
    	$row=0;
    	foreach ($GLOBALS['TL_CRON']['hourly'] as $reg )
    	{
    		$strEncypt = base64_encode( \Encryption::encrypt( serialize( array( $reg[0],$reg[1] ) ) ) );
    		$interval = (!$row) ? '<strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_hourly'].'</strong>' : '' ;
    		$strRegistrations .= '
    		<tr>
    			<td>'.$interval.'</td>
    			<td> </td>
    			<td>'.$reg[0].'</td>
    			<td> </td>
    			<td>'.$reg[1].'</td>
    			<td> </td>
    			<td style="text-align: center;"><a href="system/modules/cron_info/assets/CronStart.php?crcst='.$strEncypt.'" title="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['CronInfo']['start_confirm'].'\'))return false;Backend.openModalIframe({\'width\':735,\'height\':405,\'title\':\'Cronjob Start\',\'url\':this.href});return false"><img src="system/modules/cron_info/assets/cron_info_start_icon.png" width="16" height="16" alt="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" style="vertical-align:text-bottom"></a></td>
    		</tr>
    		';
    		$row++;
    	}
    	$row=0;
    	foreach ($GLOBALS['TL_CRON']['minutely'] as $reg )
    	{
    		$strEncypt = base64_encode( \Encryption::encrypt( serialize( array( $reg[0],$reg[1] ) ) ) );
    		$interval = (!$row) ? '<strong>'.$GLOBALS['TL_LANG']['CronInfo']['interval_minutely'].'</strong>' : '' ;
    		$strRegistrations .= '
    		<tr>
    			<td>'.$interval.'</td>
    			<td> </td>
    			<td>'.$reg[0].'</td>
    			<td> </td>
    			<td>'.$reg[1].'</td>
    			<td> </td>
    			<td style="text-align: center;"><a href="system/modules/cron_info/assets/CronStart.php?crcst='.$strEncypt.'" title="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['CronInfo']['start_confirm'].'\'))return false;Backend.openModalIframe({\'width\':735,\'height\':405,\'title\':\'Cronjob Start\',\'url\':this.href});return false"><img src="system/modules/cron_info/assets/cron_info_start_icon.png" width="16" height="16" alt="'.$GLOBALS['TL_LANG']['CronInfo']['start'][1].'" style="vertical-align:text-bottom"></a></td>
    		</tr>
    		';
    		$row++;
    	}
    	$strRegistrations .= '</tbody>
		</table>
		</div>';
    	return $strRegistrations;
    }
  
}
