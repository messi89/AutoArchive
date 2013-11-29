<?php
/**
 * @copyright	Copyright (C) 2013 Messi89 - OverGen. All rights reserved.
 */

// no direct access
defined ( '_JEXEC' ) or die ();

// Require dependancies
jimport ( 'joomla.plugin.plugin' );

/**
 * Joomla 2.5! Content Auto Archiver
 */
class plgSystemAutoarchive extends JPlugin {
	
	/**
	 * @access private
	 * @var int The number of days age to archive
	 */
	private $_days;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	object	$subject The object to observe
	 * @param	array	$config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	public function __construct(& $subject, $config) {
		
		// init so that we get our params
		parent::__construct ( $subject, $config );
		
		// Get our current configuration
		$this->_days = $this->params->get ( 'days', 30 );
	}
	
	/**
	 * Called after the page is rendered, so low pri and doesnt effect performance - much
	 * @access public
	 * @return void
	 */
	public function onAfterRender() {
		
		// get the app
		$app = JFactory::getApplication ();
		
		// Dont do anything if admin or in debug mode
		if ($app->isAdmin () || JDEBUG) {
			return;
		}
		
		// How many days back was this date?
		$date = date ( 'Y-m-d', strtotime ( $this->_days . ' days ago', time () ) );
		
		// construct the sql 
		$query = "UPDATE #__content SET state = '2' ";
		$query .= " WHERE publish_up <= '" . $date . "' ";
		$query .= " OR (publish_down <> '0000-00-00 00:00:00' AND publish_down <= '" . date ( 'Y-m-d' ) . "' AND state <> '2')";
		
		// Run the sql
		$db = JFactory::getDBO ();
		$db->setQuery ( $query );
		$db->query ();	
	}
}
