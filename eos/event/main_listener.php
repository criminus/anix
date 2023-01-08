<?php
/**
 *
 * Extended Online Status. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2023, Anix, https://phpbbhacks.ro
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace anix\eos\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extended Online Status Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'							=> 'load_language_on_setup',
			'core.viewtopic_modify_post_row'			=> 'load_viewtopic_activity',
			'core.memberlist_view_profile'		=> 'load_viewprofile_activity',
		];
	}

	/** @var \phpbb\request\request */
	protected $request;
	
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(
		\phpbb\request\request $request, 
		\phpbb\user $user, 
		\phpbb\db\driver\driver_interface $db,
		\phpbb\template\template $template,
		\phpbb\language\language $language
	)
	{
		$this->request = $request;
		$this->user = $user;
		$this->db = $db;
		$this->template = $template;
		$this->language = $language;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'anix/eos',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function load_viewtopic_activity($event)
	{
		$poster_id = $event['poster_id'];

		$user_last_active = $this->getUserActivity($poster_id);
		$poster_last_active = $this->user->format_date($user_last_active, 'Y-m-d H:i:s');

		$event['post_row'] = array_merge($event['post_row'], [
			'EOS_ACTIVITY'			=> $this->getRelativeInterval($poster_last_active, 'Y-m-d H:i:s'),
		]);
	}

	public function load_viewprofile_activity($event)
	{
		$member = $event['member'];
		$user_id = (int) $member['user_id'];

		$user_last_active = $this->getUserActivity($user_id);
		$member_last_active = $this->user->format_date($user_last_active, 'Y-m-d H:i:s');

		$this->template->assign_vars([
			'EOS_ACTIVITY'			=> $this->getRelativeInterval($member_last_active, 'Y-m-d H:i:s'),
		]);
	}

	protected function getUserActivity($user) {

		global $db;
	
		$sql = 'SELECT u.user_lastvisit, IFNULL(s.session_time,"") as session_time
		FROM ' . USERS_TABLE . ' u
		LEFT JOIN	 ' . SESSIONS_TABLE . ' s ON s.session_user_id = u.user_id
		WHERE u.user_id = ' . $user;
	
		$result_activity = $db->sql_query($sql);
	
		$user = '';
	
		if ($row = $db->sql_fetchrow($result_activity))
		{
			$user = ($row['session_time']) ? $row['session_time'] : $row['user_lastvisit'];
		}
		$db->sql_freeresult($result_activity);
	
		return $user;
	}

	protected function getRelativeInterval($date, $format) {
		$date1 = \DateTime::createFromFormat($format, $date);
		$date2 = new \DateTime();
		$interval = $date1->diff($date2);
		
		$years = $interval->y;
		$months = $interval->m;
		$weeks = floor($interval->d / 7);
		$days = $interval->d % 7;
		$hours = $interval->h;
		$minutes = $interval->i;
		
		$intervalString = '';
		if ($years > 0) {
			$intervalString .= ' ' . $this->language->lang('EOS_YEAR', $years);
		}
		if ($months > 0) {
			if ($intervalString != '') {
				$intervalString .= ', ';
			}
			$intervalString .= ' ' . $this->language->lang('EOS_MONTH', $months);
		}
		if ($weeks > 0) {
			if ($intervalString != '') {
				$intervalString .= ', ';
			}
			$intervalString .= ' ' . $this->language->lang('EOS_WEEK', $weeks);
		}
		if ($days > 0) {
			if ($intervalString != '') {
				$intervalString .= ', ';
			}
			$intervalString .= ' ' . $this->language->lang('EOS_DAY', $days);
		}
		if ($hours > 0) {
			if ($intervalString != '') {
				$intervalString .= ', ';
			}
			$intervalString .= ' ' . $this->language->lang('EOS_HOUR', $hours);
		}
		if ($minutes > 0) {
			if ($intervalString != '') {
				$intervalString .= ', ';
			}
			$intervalString .= ' ' . $this->language->lang('EOS_MINUTE', $minutes);
		}
	
		if ($intervalString == '') {
			return $this->language->lang('EOS_ACTIVE_NOW');
		} else {
			return $this->language->lang('EOS_ACTIVE', $intervalString);
		}
	}
}
