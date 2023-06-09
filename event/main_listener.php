<?php
/**
 *
 * Categorize Topics extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\categorizetopics\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Categorize Topics event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language  $language
	 */
	public function __construct(\phpbb\language\language $language)
	{
		$this->language = $language;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.viewforum_get_announcement_topic_ids_data'	=> 'viewforum_get_announcement_topic_ids_data',
			'core.viewforum_modify_topicrow'					=> 'viewforum_modify_topicrow',
			'core.viewforum_topic_row_after'					=> 'viewforum_topic_row_after',
		];
	}

	public function viewforum_get_announcement_topic_ids_data($event)
	{
		$event->update_subarray('sql_ary', 'ORDER_BY', 't.topic_type DESC, t.topic_last_post_time DESC');
	}

	public function viewforum_modify_topicrow($event)
	{
		$this->language->add_lang('common', 'kaileymsnay/categorizetopics');

		$row = $event['row'];
		$s_type_switch = (int) $event['s_type_switch'];
		$s_type_switch_test = (int) $row['topic_type'];

		$event->update_subarray('topic_row', 'S_TOPIC_TYPE_SWITCH', ($s_type_switch == $s_type_switch_test) ? -1 : 0);
	}

	public function viewforum_topic_row_after($event)
	{
		$row = $event['row'];
		$s_type_switch = $row['topic_type'];
		$event['s_type_switch'] = $s_type_switch;
	}
}
