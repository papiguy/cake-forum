<?php
/**
 * Forum - Profile Model
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2006-2010, Miles Johnson, Inc.
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		http://milesj.me/resources/script/forum-plugin
 */

class Profile extends ForumAppModel {

	/**
	 * Belongs to.
	 *
	 * @access public
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Forum.User'
		)
	);

	/**
	 * Get the newest signup.
	 *
	 * @access public
	 * @return array
	 */
	public function getNewestUser() {
		return $this->find('first', array(
			'order' => array('Profile.created' => 'DESC'),
			'contain' => array('User'),
			'limit' => 1
		));
	}

	/**
	 * Grab the users profile. If it doesn't exist, create it!
	 *
	 * @access public
	 * @param int $user_id
	 * @return array
	 */
	public function getUserProfile($user_id) {
		$profile = $this->find('first', array(
			'conditions' => array('Profile.user_id' => $user_id)
		));

		// Update login and return
		if (!empty($profile)) {
			$this->login($profile['Profile']['id'], $profile['Profile']['currentLogin']);

			return $profile['Profile'];

		// Create new record
		} else {
			$this->create();
			$this->save(array('user_id' => $user_id), false);

			return $this->getUserProfile($user_id);
		}
	}

	/**
	 * Increase the post count.
	 *
	 * @access public
	 * @param int $id
	 * @return boolean
	 */
	public function increasePosts($id) {
		return $this->query("UPDATE `". $this->tablePrefix ."profiles` AS `Profile` SET `Profile`.`totalPosts` = `Profile`.`totalPosts` + 1 WHERE `Profile`.`id` = $id");
	}

	/**
	 * Increase the topic count.
	 *
	 * @access public
	 * @param int $id
	 * @return boolean
	 */
	public function increaseTopics($id) {
		return $this->query("UPDATE `". $this->tablePrefix ."profiles` AS `Profile` SET `Profile`.`totalTopics` = `Profile`.`totalTopics` + 1 WHERE `Profile`.`id` = $id");
	}
	
	/**
	 * Login the user and update records.
	 *
	 * @access public
	 * @param int $id
	 * @param string $login
	 * @return boolean
	 */
	public function login($id, $login) {
		$this->id = $id;

		return $this->save(array(
			'currentLogin' => date('Y-m-d H:i:s'),
			'lastLogin' => $login
		), false);
	}

	/**
	 * Get whos online within the past x minutes.
	 *
	 * @access public
	 * @param int $minutes
	 * @return array
	 */
	public function whosOnline($minutes = null) {
		if (!$minutes) {
			$minutes = Configure::read('Forum.settings.whos_online_interval');
		}
		
		return $this->find('all', array(
			'conditions' => array('Profile.currentLogin >' => date('Y-m-d H:i:s', strtotime('-'. $minutes .' minutes'))),
			'contain' => array('User')
		));
	}

}