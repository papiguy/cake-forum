<?php if ($this->Common->user()) { ?>
	<div class="forumOptions">
		<?php if ($this->Common->hasAccess('mod', $topic['Forum']['id'])) {
			echo $this->Html->link(__d('forum', 'Moderate', true), array('controller' => 'topics', 'action' => 'moderate', $topic['Topic']['id']));
		} ?>
		
		<?php if ($this->Common->hasAccess($topic['Forum']['accessPost'])) {
			echo $this->Html->link(__d('forum', 'Create Topic', true), array('controller' => 'topics', 'action' => 'add', $topic['Forum']['id']));
		} ?>
		
		<?php if ($this->Common->hasAccess($topic['Forum']['accessPoll'])) {
			echo $this->Html->link(__d('forum', 'Create Poll', true), array('controller' => 'topics', 'action' => 'add', $topic['Forum']['id'], 'poll'));
		} ?>
		
		<?php if ($this->Common->hasAccess($topic['Forum']['accessReply'])) {
			if ($topic['Topic']['status']) {
				echo $this->Html->link(__d('forum', 'Post Reply', true), array('controller' => 'posts', 'action' => 'add', $topic['Topic']['id']));
			} else {
				echo '<span>'. __d('forum', 'Closed', true) .'</span>';
			}	
		} ?>
	</div>
<?php } ?>