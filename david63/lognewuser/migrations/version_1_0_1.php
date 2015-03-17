<?php
/**
*
* @package Log New User Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\lognewuser\migrations;

class version_1_0_1 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('david63\lognewuser\migrations\version_1_0_0');
	}

	public function effectively_installed()
	{
		return isset($this->config['lognewuser_version']) && version_compare($this->config['lognewuser_version'], '1.0.1', '>=');
	}

		public function update_data()
	{
		return array(
			array('config.update', array('lognewuser_version', '1.0.1')),
			array('config.remove', array('log_new_user')),
		);
	}
}
