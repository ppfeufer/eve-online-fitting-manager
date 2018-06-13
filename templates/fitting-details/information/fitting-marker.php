<?php
$isConcept = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_is_concept', true);
$isIdea = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_is_idea', true);
$isUnderDiscussion = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_is_under_discussion', true);

if($isConcept === '1' || $isIdea === '1' || $isUnderDiscussion === '1') {
    ?>
    <div class="bs-callout bs-callout-warning">
        <p class="small">
            <?php
            echo \__('This fitting is marked as:', 'eve-online-fitting-manager');

            if($isConcept === '1') {
                echo '<br>» ' . \__('Conceptual Fitting', 'eve-online-fitting-manager');
            }

            if($isIdea === '1') {
                echo '<br>» ' . \__('Fitting Idea', 'eve-online-fitting-manager');
            }

            if($isUnderDiscussion === '1') {
                echo '<br>» ' . \__('Under Discussion', 'eve-online-fitting-manager');
            }
            ?>
        </p>
        <p class="small">
            <?php echo \__('This means, this fitting might not be a part of any official doctrine and/or is still under discussion.', 'eve-online-fitting-manager'); ?>
        </p>
    </div>
    <?php
}
