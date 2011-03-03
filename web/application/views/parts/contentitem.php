<div class="container_content">
    <div class="content-item" id="<?php echo($content->id); ?>">
        <div class="left-column">
            <div class="notch"></div>
            <div class="veracity">
                <h2 class="<?php echo($content->source->id); ?>"><?php echo($content->source->score == "null")? "-" : $content->source->score;?></h2>
            </div>
            <?php if($enableActions) : ?>
                <ol class="actions">
                    <li class="star"><a href="javascript:listController.MarkContentAsAccurate('<?php echo($content->id); ?>')" title="Star this content item"><span>Star this content item</span></a></li>
                </ol>
            <?php endif; ?>
        </div>


        <div class="right-column">
            <p class="source"><a href="javascript:Content('<?php echo($content->source->name); ?>', '<?php echo($content->source->type); ?>', '<?php echo($content->source->ratings); ?>', '<?php echo($content->source->score); ?>', '<?php echo($content->source->link); ?>', '<?php echo($content->link); ?>')" class="<?php echo strtolower($content->source->type); ?>" title="View source details"><!--<?php echo strtolower($content->source->name); ?>--></a><!--source--></p>
            <div class="meta">
                <?php foreach($content->tags as $type => $tags) : ?>
                    <?php if(is_array($tags) && count($tags) > 0) : ?>
                        <?php if($type == 'where' && count($tags) > 0) : ?>
                            <p class="location">
                                <strong>
                                    <?php foreach($tags as $key => $tag) : ?>
                                        <?php if(strlen($tag) > 3) : ?>
                                            <?php echo ucfirst(strtolower($tag)) . ' '; ?>
                                        <?php else : ?>
                                            <?php echo strtoupper($tag) . ' '; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </strong>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <p class="date"><?php echo(date('D d M Y H:i (P\G\M\T)', $content->date)); ?></p>
            </div>
            <div class="languages">
                <?php for($i=0; $i<count($content->text); $i++): ?>
                    <div class="language <?php echo($content->text[$i]->languageCode); ?> <?php if($i>0) { echo ('more'); } ?>">
                        <?php
                            /* Add links to string */
                            $title = html_entity_decode($content->text[$i]->title);
                            $title = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a target=\"_blank\" href=\"\\2\">\\2</a>", $title);
                            $title = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a target=\"_blank\" href=\"http://\\2\">\\2</a>", $title);
                            /* Add link to twitter mention */
                            $title = preg_replace("/@(\w+)/", "<a target=\"_blank\" href=\"http://www.twitter.com/\\1\">@\\1</a>", $title);
                        ?>
                        <h2 class="title">
                            <?php echo($title); ?>
                        </h2>
                        <?php if(isset($content->text[$i]->text)) : ?>
                            <?php foreach($content->text[$i]->text as $text) : ?>
					            <!-- Hiding Text from View -->
                                <p style="display:none;"><?php echo($text); ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="tags">
                <?php foreach($content->tags as $type => $tags) : ?>
                    <?php if(is_array($tags) && count($tags) > 0) : ?>
                        <?php if($type != 'where' && count($tags) > 0) : ?>
                            <ol class="tag-list">
                                <?php foreach($tags as $key => $tag) : ?>
                                    <li id="<?php echo($content->id); ?>-<?php echo(str_replace(" ", "", strtolower($tag))); ?>">
                                        <a class="tag-remove" href="JavaScript:void();', '<?php echo($type); ?>', '<?php echo($tag); ?>', '<?php echo($content->id); ?>-<?php echo(str_replace(" ", "", strtolower($tag))); ?>');" title="Remove this tag"><span>&nbsp;</span></a>
                                        <a class="tag-select" href="JavaScript:void();"><?php echo strtolower($tag); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>