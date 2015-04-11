<div class="row mbt">
	<div class="pt-header-line">
		<div class="pt-section bg1">
			<div class="pt-menu-content">
				<ul class="pt-list-menu-content">
					<li class="icon-index active">
						<a href="#" ><i class="fa fa-home"></i></a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                        'action' => 'getByType', 
                                        'slug_city' => Link::seoTitle($city_name), 
                                        'city_id' => $city_id,
                                        'slug_type' => Link::seoTitle('Hội Thảo'),
                                        'type_id' => TYPE_WORKSHOP )); ?>" class="text-link">Hội thảo 
                                        <?php if ($total_workshop > 0){ ?> <span class="color1"><?php echo $total_workshop; ?></span> <?php } ?>
                        </a>
					</li>
                    
					<li>
						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                        'action' => 'getByType', 
                                        'slug_city' => Link::seoTitle($city_name), 
                                        'city_id' => $city_id,
                                        'slug_type' => Link::seoTitle('Khuyến Mãi'),
                                        'type_id' => TYPE_PROMOTION )); ?>" class="text-link">Khuyến mãi 
                                        <?php if ($total_promotion > 0){ ?> <span class="color1"><?php echo $total_promotion; ?></span> <?php } ?> 
                        </a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                        'action' => 'getByType', 
                                        'slug_city' => Link::seoTitle($city_name), 
                                        'city_id' => $city_id,
                                        'slug_type' => Link::seoTitle('Đào Tạo'),
                                        'type_id' => TYPE_TRAINING )); ?>" class="text-link">Đào tạo 
                                        <?php if ($total_training > 0){ ?> <span class="color2"><?php echo $total_training; ?></span> <?php } ?> 
                        </a>
                                        
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                        'action' => 'getByType', 
                                        'slug_city' => Link::seoTitle($city_name), 
                                        'city_id' => $city_id,
                                        'slug_type' => Link::seoTitle('Giải Trí'),
                                        'type_id' => TYPE_ENTERTAINMENT )); ?>" class="text-link">Giải trí 
                                        <?php if ($total_entertainment > 0){ ?> <span><?php echo $total_entertainment; ?></span> <?php } ?>
                        </a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                        'action' => 'getByType', 
                                        'slug_city' => Link::seoTitle($city_name), 
                                        'city_id' => $city_id,
                                        'slug_type' => Link::seoTitle('Khai Trương'),
                                        'type_id' => TYPE_INAUGURATED )); ?>" class="text-link">Khai trương 
                            <span>09</span>
                        </a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Xã hội'),
                                                                    'type_id' => TYPE_SOCIAL )); ?>" class="text-link">Xã hội 
                            <span>09</span>
                        </a>
					</li>
					<li class="list-sup-menu">
						<a href="#" class="icon-index"><i class="fa fa-reorder"></i></a>
						<ul class="pt-list-menu-content-sup">
                            <!--Display mobile-->
                            <li class="list-pc">
        						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                'action' => 'getByType', 
                                                'slug_city' => Link::seoTitle($city_name), 
                                                'city_id' => $city_id,
                                                'slug_type' => Link::seoTitle('Hội Thảo'),
                                                'type_id' => TYPE_WORKSHOP )); ?>" class="text-link">Hội thảo 
                                                <?php if ($total_workshop > 0){ ?> <span class="color1"><?php echo $total_workshop; ?></span> <?php } ?>
                                </a>
        					</li>
        					<li class="list-pc">
        						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                'action' => 'getByType', 
                                                'slug_city' => Link::seoTitle($city_name), 
                                                'city_id' => $city_id,
                                                'slug_type' => Link::seoTitle('Khuyến Mãi'),
                                                'type_id' => TYPE_PROMOTION )); ?>" class="text-link">Khuyến mãi 
                                                <?php if ($total_promotion > 0){ ?> <span class="color1"><?php echo $total_promotion; ?></span> <?php } ?> 
                                </a>
        					</li>
        					<li class="list-pc">
        						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                'action' => 'getByType', 
                                                'slug_city' => Link::seoTitle($city_name), 
                                                'city_id' => $city_id,
                                                'slug_type' => Link::seoTitle('Đào Tạo'),
                                                'type_id' => TYPE_TRAINING )); ?>" class="text-link">Đào tạo 
                                                <?php if ($total_training > 0){ ?> <span class="color2"><?php echo $total_training; ?></span> <?php } ?> 
                                </a>
                                                
        					</li>
        					<li class="list-pc">
        						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                'action' => 'getByType', 
                                                'slug_city' => Link::seoTitle($city_name), 
                                                'city_id' => $city_id,
                                                'slug_type' => Link::seoTitle('Giải Trí'),
                                                'type_id' => TYPE_ENTERTAINMENT )); ?>" class="text-link">Giải trí 
                                                <?php if ($total_entertainment > 0){ ?> <span><?php echo $total_entertainment; ?></span> <?php } ?>
                                </a>
        					</li>
        					<li class="list-pc">
        						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                'action' => 'getByType', 
                                                'slug_city' => Link::seoTitle($city_name), 
                                                'city_id' => $city_id,
                                                'slug_type' => Link::seoTitle('Khai Trương'),
                                                'type_id' => TYPE_INAUGURATED )); ?>" class="text-link">Khai trương 
                                    <span>09</span>
                                </a>
        					</li>
        					<li class="list-pc">
        						<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                            'action' => 'getByType', 
                                                                            'slug_city' => Link::seoTitle($city_name), 
                                                                            'city_id' => $city_id,
                                                                            'slug_type' => Link::seoTitle('Xã hội'),
                                                                            'type_id' => TYPE_SOCIAL )); ?>" class="text-link">Xã hội 
                                    <span>09</span>
                                </a>
        					</li>
                            <!--Display normal and mobile-->
                            <li>
								<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Du lịch'),
                                                                    'type_id' => TYPE_TOURISM )); ?>" class="text-link">Du lịch 
                                    <span>9</span>
                                </a>
							</li>
							<li>
								<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Triển lãm'),
                                                                    'type_id' => TYPE_EXHIBITION )); ?>" class="text-link">Triển lãm 
                                    <span>09</span>
                                </a>
							</li>
							<li>
								<a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Văn hóa'),
                                                                    'type_id' => TYPE_CULTURE )); ?>" class="text-link">Văn hóa 
                                    <span>09</span>
                                </a>
							</li>
						</ul>
					</li>
				</ul>
				<a <?php  echo $logged_in ? 'onclick="addEvent()"  href="#"' : ' href="#login"' ?> class="add-event pull-right <?php  echo $logged_in ? '' : 'link-login' ?>"><i class="fa fa-plus-circle"></i> Đăng sự kiện mới</a>
			</div>
		</div>
	</div>	
</div>