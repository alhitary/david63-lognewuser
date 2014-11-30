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
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config $config phpBB config
	* @param \phpbb\request\request $request phpBB request
	* @access public
	*/
	public function __construct($config, $request)
	{
		$this->config	= $config;
		$this->request	= $request;

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
			'core.user_setup'					=> 'load_language_on_setup',
			'core.acp_board_config_edit_add'	=> 'acp_board_settings',
			'core.user_add_after'				=> 'add_new_user',
		);
	}

	/**
	* Set ACP board settings
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_board_settings($event)
	{
		if ($event['mode'] == 'registration')
		{
			$new_display_var = array(
				'title'	=> $event['display_vars']['title'],
				'vars'	=> array(),
			);

			foreach ($event['display_vars']['vars'] as $key => $content)
			{
				$new_display_var['vars'][$key] = $content;
				if ($key == 'chg_passforce')
				{
					$new_display_var['vars']['log_new_user'] = array(
						'lang'		=> 'LOG_NEW_USER',
						'validate'	=> 'bool',
						'type'		=> 'radio:yes_no',
						'explain' 	=> true,
					);
				}
			}

			$event->offsetSet('display_vars', $new_display_var);
		}
	}

	/**
	* Load common log new user language files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext	= $event['lang_set_ext'];
		$lang_set_ext[]	= array(
			'ext_name' => 'david63/lognewuser',
			'lang_set' => 'lognewuser',
		);
		$event['lang_set_ext'] = $lang_set_ext;
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

		if ($this->config['log_new_user'] == true)
		{
			$phpbb_log->add('user', $user_id, 'LOG_USER_CREATED', $user_row['username']);
		}
	}

}