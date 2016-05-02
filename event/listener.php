<?php
/**
*
* @package Log New User Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\lognewuser\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\log\log */
	protected $log;

	/**
	* Constructor for listener
	*
	* @param \phpbb\log\log			$log		phpBB log
	*
	* @access public
	*/
	public function __construct(\phpbb\log\log $log)
	{
		$this->log = $log;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_add_after' => 'add_new_user',
		);
	}

	/**
	* Log the new user
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_new_user($event)
	{
		$user_id	= $event['user_id'];
		$user_row	= $event['user_row'];
		$user_type	= $event['user_type'];

		if ($user_type = USER_NORMAL)
		{
			$this->log->add('user', $user_id, $user_row['user_ip'], 'LOG_NEW_USER_CREATED', time(), array('reportee_id' => $user_id));
		}
	}
}
