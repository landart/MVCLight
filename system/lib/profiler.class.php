<?php
/**
 * MVCLight
 *
 * An open source application development framework for PHP
 * 
 * PHP version 5
 * 
 * LICENSE: This source file is subject to the license that is available
 * at the following url:
 * http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * If you did not receive a copy of the license and are unable to obtain
 * it through the web, please send a note to correo@jorgealbaladejo.com
 * so we can mail you a copy immediately.
 *
 * @category	System
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */

/**
 * system/lib/profiler.class.php
 * 
 * Profiler used to track application's time and resources consumption
 * 
 * @category	System
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
Class profiler
{	
	/**
	 * Keeps a log of registers happened during the execution
	 * 
	 * @access private
	 */
	private static $logs 		= array();
	
	/**
	 * General enabled, determined from Registry configuration
	 * 
	 * @access private
	 */
	private static $enabled 	= false;
	
	/**
	 * Ajax enabled output, configured in Registry
	 * 
	 * @access private
	 */
	private static $ajaxEnabled 	= true;
	
	/**
	 * Action to be executed, established by router class
	 * 
	 * @access private
	 */
	private static $action 		= '';
	
	/**
	 * Controller to be called, established by router class
	 * 
	 * @access private
	 */
	private static $controller	= '';
	
	/**
	 * Header template to be used for report
	 * 
	 * @access private
	 */
	private static $header		= 'PROFILER INFORMATION';
	
	/**
	 * Separator to be used in report
	 * 
	 * @access private
	 */
	private static $separator 	= '---------------------------------------------';
		
	/**
	 * Format for time display
	 * 
	 * @access private
	 */
	private static $format 		= '7.3';
	
	/**
	 * Line break to be used
	 * 
	 * @access private
	 */
	private static $lineBreak 	= "\n";
	
	/**
	 * Internal reference for instancing and initializing the class
	 * 
	 * @access private
	 */
	private static $profiler	= null;
	
	/**
	 * Serves as an internal marker to finish queries block and start views section
	 * TODO: enable true sections in this class by using nested associative arrays
	 * 
	 * @access public
	 */
	public static $firstView	= true;
	
	public function __construct()
	{
		// profiler is called during registry initialization
		// cannot use registry to get the configuration
		include (__APP_PATH . '/config/config.ini.php' );

		self::$enabled = ( isset($config['enable_profiler']) ? $config['enable_profiler'] : false ) && !PRODUCTION_SERVER;
		self::$ajaxEnabled = isset($config['enable_profiler_ajax']) ? $config['enable_profiler_ajax'] : false;    
	}
	
	/**
	 * Adds a log to the internal array
	 * 
	 * @param string $msg to show
	 * @param boolean $checkpoint if true, marks times with the last checkpoint, allowing blocks or sections
	 * @param boolean $finish this is the finish record, it outputs the total time
	 * @param float $start if used, then the time is measured incrementally since this value, instead of since last record 
	 * @param boolean $error if an error is marked, the output is labeled in red
	 * 
	 * @access public
	 */
	static public function addLog($msg = '', $checkpoint = false, $finish = false, $start = 0, $error = false)
	{
		// create the object for the very first time
        if (!self::$profiler)
        {
        	self::$profiler = new Profiler();
        }
        	
		// must be explicitly enabled
		if (self::$enabled)
        {
        	// check that the entry does not exist previously
        	foreach (self::$logs as $i=>$log)
        	{
        		if ($log['msg'] == $msg)
        		{
        			$msg = sprintf('Duplicates #%2.0F',$i);
        		}
        	}
        	
        	self::$logs[] = array('time'=>microtime(true),'msg'=>$msg, 'checkpoint'=>$checkpoint, 'finish'=>$finish, 'start'=>$start, 'error'=>$error);       	
        }		
	}
	
	/**
	 * Alias for addLog for checkpoints
	 * 
	 * @param string $msg
	 * 
	 * @access public
	 */
	static public function checkpoint($msg = '')
	{
		self::addLog($msg, true);       
	}
	
	/**
	 * Alias for addLog for the finish entry
	 * 
	 * @param string $msg
	 * 
	 * @access public
	 */
	static public function finish($msg = '')
	{
		self::addLog($msg, true, true);
	}
	
	/**
	 * Alias for addLog for queries
	 * 
	 * @param string $msg
	 * 
	 * @access public
	 */
	static public function query($qry = '', $start = 0)
	{
		self::addLog($qry,false,false,$start);
	}
	
	/**
	 * Alias for addLog for sqlErrors
	 * 
	 * @param string $msg
	 * 
	 * @access public
	 */
	static public function sqlError($err = '')
	{
		self::addLog($err,false,false,0,true);
	}
	
	/**
	 * Alias for addLog for general errors
	 * 
	 * @param string $msg
	 * 
	 * @access public
	 */
	static public function error($err = '')
	{
		self::addLog($err,false,false,0,true);
	}
	
	/**
	 * Produces an output
	 * 
	 * @access public
	 */
	static public function show()
	{
		$input = Input::getInstance();
		
		// must be explicitly enabled
		if (!self::$enabled || !self::$ajaxEnabled && ( self::$action == 'ajax' || strpos($input->server('REQUEST_URI'),'/ajax/') === 0 ) )
        {
        	return;        	
        }
		
		// calculate times
		$output		= '';
		$current	= TIME_START;
		$checkpoint = TIME_START;
		$line 		= '';
		//
				
		$output .= self::$lineBreak;
		$output .= self::$header . self::$lineBreak . self::$separator . self::$lineBreak;
		
		$output .= '<strong>Controller:</strong> <em>' . self::$controller . '</em>' . self::$lineBreak;
		$output .= '<strong>Action:</strong>     <em>' . self::$action . '</em>' . self::$lineBreak . self::$separator . self::$lineBreak;
		
		foreach (self::$logs as $i=>$log)
		{
			$log['msg'] = reduce($log['msg']);
			
			// checkpoints mark time since last checkpoint
			if ($log['checkpoint'])
			{
				if (!self::$logs[$i-1]['checkpoint'])
				{
					$output    .= sprintf ( self::$separator . self::$lineBreak );
				}					
				$diff		= $log['time'] - ( $log['finish'] ? TIME_START : $checkpoint );
				$checkpoint = $log['time'];				
			}
			// some processes mark the start time, so that the different is calculated based on that
			else if ($log['start'])
			{
				$diff = $log['time'] - $log['start'];
			}	
			// finally, check difference against last log		
			else
			{
				$diff = $log['time'] - $current;
			}
			
			// then generate line
			$line = sprintf ('#%2.0F %' . self::$format . 'F ms: ' . $log['msg'] . self::$lineBreak, $i, $diff*1000 );
			
			if ( $log['checkpoint'] )
			{
				$output .= '<strong>' . $line . '</strong>';
			}
			else if ( $log['error'] ) 
			{
				$output .= '<span style="color:red">' . $line . '</span>';
			}	
			else
			{
				$output .= $line;
			}			
			
			// add another separator for checkpoints for better readability
			if ($log['checkpoint'])
			{
				$output    .= sprintf ( self::$separator . self::$lineBreak );
			}
			
			$current = $log['time'];
		}
		
		$output = '<pre style="margin: 30px auto;width: 90%;clear:both; overflow: auto">' . str_replace( self::$separator . self::$lineBreak, '<hr />', 
																								str_replace( self::$header, '<strong>' . self::$header . '</strong>', $output) ) . '</pre>';			
		echo $output;
	}
	
	/**
	 * Used to set the action in real time
	 * 	Useful to choose an output mode
	 * 
	 * @param string the action
	 * 
	 * @access public
	 */
	public static function setAction($action = '')
	{
		self::$action = $action;
	}
	
	/**
	 * Used to set the controller in real time
	 * 	Given as debug information
	 * 
	 * @param string the action
	 * 
	 * @access public
	 */
	public static function setController($controller = '')
	{
		self::$controller = $controller;
	}
	
}

?>
