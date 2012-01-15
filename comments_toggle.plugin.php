<?php

class CommentsTogglePlugin extends Plugin
{
	public function filter_posts_manage_actions($page_actions)
	{
		$page_actions['enable_comments'] = array('action' => 'itemManage.update(\'enable_commenting\');return false;', 'title' => _t('Enable Commenting'), 'label' => _t('Enable Commenting') );
		$page_actions['disable_comments'] = array('action' => 'itemManage.update(\'disable_commenting\');return false;', 'title' => _t('Disable Commenting'), 'label' => _t('Disable Commenting') );
		return $page_actions;
	}

	public function action_admin_posts_action($response, $action, $posts)
	{
		switch($action) {
			case 'enable_commenting':
				$response->message = $this->set_commenting($posts, 0);
				break;
			case 'disable_commenting':
				$response->message = $this->set_commenting($posts, 1);
				break;
		}
	}

	private function set_commenting($posts, $onoff)
	{
		$changed = 0;
		foreach ( $posts as $post ) {
			if ( ACL::access_check( $post->get_access(), 'edit' ) ) {
				$post->info->comments_disabled = $onoff;
				$post->info->commit();
				$changed++;
			}
		}
		$return = '';
		if ( $changed != count( $posts ) ) {
			$return .= _t( "You did not have permission to modify some posts.\n" );
		}
		if(!$onoff) {
			$return .= sprintf(_n( 'Enabled commenting on %d post', 'Enabled commenting on %d posts', $changed ), $changed);
		}
		else {
			$return .= sprintf(_n( 'Disabled commenting on %d post', 'Disabled commenting on %d posts', $changed ), $changed);
		}
		return $return;
	}
}

?>